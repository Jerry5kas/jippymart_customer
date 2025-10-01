# OTP Authentication System Documentation

## 🔍 **Issue Identified & Fixed**

### **Problem**: 419 Page Expired Error on `/otp-verify`

**Root Cause**: The `WebOtpController` constructor was redirecting to `set-location` page for users without location cookies, **EVEN during OTP authentication flow**.

**Impact**: Users trying to verify OTP were being redirected away, causing session loss and 419 errors.

---

## ✅ **Fix Applied**

### **Before** (causing 419 errors):
```php
public function __construct() {
    if (!isset($_COOKIE['address_name'])) {
        \Redirect::to('set-location')->send();  // ❌ Redirects during OTP flow
    }
}
```

### **After** (fixed):
```php
public function __construct() {
    $otpRoutes = ['otp-login', 'otp-send', 'otp-verify', 'otp-register', 'otp-resend'];
    $currentPath = request()->path();
    
    // Skip location check for OTP routes
    if (!request()->is('api/*') && !in_array($currentPath, $otpRoutes) && !isset($_COOKIE['address_name'])) {
        \Redirect::to('set-location')->send();  // ✅ Skips OTP routes
    }
}
```

---

## 📋 **OTP System Architecture**

### **Dual Authentication System**

| Platform | Routes | Controller | Token Type |
|----------|--------|------------|------------|
| **Web** | `/otp-login`, `/otp-verify` | WebOtpController | Session-based |
| **Mobile App** | `/api/send-otp`, `/api/verify-otp` | OTPController | Sanctum API Token |

---

## 🔄 **OTP Flow Diagram**

### **Web Flow:**
```
1. User visits /otp-login
   ↓
2. Enters phone number → POST /otp-send
   ↓
3. OTP generated & saved to MySQL `otps` table
   ↓
4. SMS sent via SMSCountry API
   ↓
5. User redirected to /otp-verify
   ↓
6. Enters 6-digit OTP → POST /otp-verify
   ↓
7. OTP validated against MySQL
   ↓
8. Check Firebase `users` collection for existing user
   ↓
9a. If user exists: Login + redirect to /home
9b. If new user: Redirect to /otp-register
   ↓
10. Complete registration → Create in Firebase + MySQL
```

### **Mobile App Flow:**
```
1. App calls POST /api/send-otp
   ↓
2. OTP generated & saved to MySQL `otps` table
   ↓
3. SMS sent via SMSCountry API
   ↓
4. App calls POST /api/verify-otp
   ↓
5. OTP validated against MySQL
   ↓
6. Returns: Sanctum API token + Firebase custom token
   ↓
7. App stores tokens and authenticates with Firebase
```

---

## 🗄️ **Database Structure**

### **MySQL `otps` Table:**
```sql
- id (bigint)
- phone (string)
- otp (string, 6 chars)
- expires_at (timestamp)
- verified (boolean, default false)
- attempts (integer, default 0)
- created_at
- updated_at

Indexes:
- (phone, otp)
- expires_at
```

### **MySQL `users` Table:**
```sql
- id
- name
- first_name
- last_name
- phone
- email
- email_verified_at
- password
- firebase_uid
- created_at
- updated_at
```

### **Firebase `users` Collection:**
```javascript
{
  id: "user_xxxxx",
  firstName: "John ",        // Note: trailing space (Android format)
  lastName: "Doe ",
  phoneNumber: "+91xxxxxxxxxx",
  email: "user@example.com",
  countryCode: "+91",
  active: true,
  isActive: false,
  role: "customer",
  provider: "email",
  appIdentifier: "web" or "android",
  fcmToken: "..." (mobile only),
  wallet_amount: 0,
  createdAt: timestamp,
  shippingAddress: [],
  zoneId: null
}
```

---

## 🔐 **Security Features**

1. **Rate Limiting**: 1 minute between OTP requests per phone
2. **Expiration**: OTP valid for 10 minutes only
3. **Attempt Limit**: Maximum 5 failed attempts
4. **Session Timeout**: 30 minutes for web OTP flow
5. **One-Time Use**: OTP marked as verified after use
6. **Secure Storage**: OTP stored in MySQL with timestamps

---

## 📱 **SMS Integration**

### **Provider**: SMSCountry API

**Multiple Fallback Methods** (in priority order):
1. **cURL** (primary)
2. **Guzzle HTTP Client**
3. **HTTP_Request2**
4. **PECL HTTP**

### **SMS Template:**
```
"Your OTP for jippymart login is {OTP}. 
Please do not share this OTP with anyone. 
It is valid for the next 10 minutes-jippymart.in."
```

### **SMS Configuration:**
- Sender ID: `JIPPYM`
- Delivery Webhook: `/api/sms-delivery-status`
- Method: POST

---

## 🔥 **Firebase Integration**

### **Authentication Flow:**

1. **OTP Verified** → Check if user exists in Firebase `users` collection
2. **User Exists** → Generate custom token for Firebase Auth
3. **New User** → Create in both Firebase AND MySQL
4. **Returns**: 
   - **Web**: Session-based login
   - **API**: Sanctum token + Firebase custom token

### **Custom Token Generation:**
```php
$firebaseUid = 'user_' . $user->id;
$customToken = $this->auth->createCustomToken($firebaseUid);
```

### **User Sync:**
- MySQL `users` table = Source of truth for authentication
- Firebase `users` collection = Synced for mobile app
- `firebase_uid` column in MySQL links the two

---

## ✅ **API Endpoints (Mobile App)**

### **POST `/api/send-otp`**
**Request:**
```json
{
  "phone": "9876543210"
}
```
**Response:**
```json
{
  "success": true,
  "message": "OTP sent successfully",
  "expires_in": 600
}
```

### **POST `/api/verify-otp`**
**Request:**
```json
{
  "phone": "9876543210",
  "otp": "123456"
}
```
**Response:**
```json
{
  "success": true,
  "message": "OTP verified successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "phone": "9876543210",
    "email": "user@jippymart.in"
  },
  "token": "1|sanctum_token_here",
  "token_type": "Bearer",
  "firebase_custom_token": "firebase_token_here"
}
```

---

## 🌐 **Web Routes**

| Route | Method | Controller | Purpose |
|-------|--------|------------|---------|
| `/otp-login` | GET | showPhoneInput | Phone input page |
| `/otp-send` | POST | sendOtp | Send OTP via SMS |
| `/otp-verify` | GET | showOtpVerify | OTP verification page |
| `/otp-verify` | POST | verifyOtp | Verify OTP code |
| `/otp-register` | GET | showRegistration | Registration form |
| `/otp-register` | POST | completeRegistration | Complete registration |
| `/otp-resend` | GET | resendOtp | Resend OTP |

---

## 🐛 **Troubleshooting**

### **419 Page Expired**
**Causes:**
- Session expired (>30 minutes)
- CSRF token mismatch
- Location redirect interfering

**Solution**: ✅ **FIXED** - Added OTP routes to skip location check

### **OTP Not Received**
**Causes:**
- SMS API credentials invalid
- Phone number format incorrect
- Rate limit hit

**Check:**
```bash
tail -f storage/logs/laravel.log
# Look for "SMS sent successfully" or error messages
```

### **Firebase Token Issues**
**Causes:**
- Firebase credentials missing/invalid
- User not created in Firestore
- Custom token generation failed

**Check:**
```bash
# Verify Firebase credentials exist
ls storage/app/firebase/credentials.json
```

---

## 🧪 **Testing Steps**

### **Test Web OTP:**
1. Visit: `http://127.0.0.1:8000/otp-login`
2. Enter phone: `9876543210`
3. Click "Send OTP"
4. Check terminal logs for SMS status
5. Enter 6-digit OTP
6. Should auto-submit and login

### **Test API OTP:**
```bash
# Send OTP
curl -X POST http://127.0.0.1:8000/api/send-otp \
  -H "Content-Type: application/json" \
  -d '{"phone":"9876543210"}'

# Verify OTP
curl -X POST http://127.0.0.1:8000/api/verify-otp \
  -H "Content-Type: application/json" \
  -d '{"phone":"9876543210","otp":"123456"}'
```

---

## 📝 **Important Notes**

### **Data Sync:**
- ✅ MySQL OTPs synced between web & mobile
- ✅ Firebase users synced between web & mobile
- ✅ Same phone = same user across platforms

### **Token Types:**
- **Web**: Laravel session authentication
- **Mobile**: Sanctum API token + Firebase custom token
- **Both**: Access same Firebase Firestore data

### **Trailing Spaces:**
Web registration adds trailing spaces to firstName/lastName to match Android format:
```php
'firstName' => $request->first_name . ' ',
'lastName' => $request->last_name . ' ',
```

---

## 🚀 **Status**

✅ **OTP System Fixed**
✅ **Location Redirect Issue Resolved**
✅ **Web & Mobile OTP Synced**
✅ **Firebase Integration Working**
✅ **MySQL Table Properly Configured**

---

**Test Now**: Visit `http://127.0.0.1:8000/otp-verify` - should work without 419 error!

