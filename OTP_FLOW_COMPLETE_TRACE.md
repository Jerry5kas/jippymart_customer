# Complete OTP Authentication System - End-to-End Flow

## 🔍 **Problem Analysis: 419 Page Expired**

### **Root Causes Identified:**

1. **Direct Access to /otp-verify** - Accessing verification page without going through send OTP flow
2. **Session Expiration** - Session lost between send and verify
3. **CSRF Token Timeout** - Token expired if user waits too long
4. **No Session Persistence** - Session not carrying over between requests

---

## 📱 **Complete OTP Flow (Step-by-Step)**

### **WEB FLOW (Browser)**

#### **Step 1: Phone Input** (`/otp-login`)
```
User Action: Visit /otp-login page
Controller: WebOtpController@showPhoneInput()
Response: Shows phone input form
Session: Empty
```

#### **Step 2: Send OTP** (`POST /otp-send`)
```
User Action: Enter phone + submit
Controller: WebOtpController@sendOtp()

Process:
1. Validate phone number format
2. Check rate limiting (1 min cooldown)
3. Generate 6-digit OTP
4. Save to MySQL otps table:
   {
     phone: "9876543210",
     otp: "123456",
     expires_at: now() + 10 mins,
     verified: false,
     attempts: 0
   }
5. Send SMS via SMSCountry API (multiple fallbacks)
6. Store in session:
   session(['otp_phone' => $phone])
   session(['otp_sent_at' => now()])
7. Redirect to /otp-verify

Response: Redirect to /otp-verify with success message
Session: Contains otp_phone, otp_sent_at
```

#### **Step 3: Verify OTP Page** (`GET /otp-verify`)
```
User Action: Redirected here after send
Controller: WebOtpController@showOtpVerify()

Checks:
1. session('otp_phone') exists? → NO = redirect to otp-login
2. Session < 30 mins old? → NO = redirect to otp-login
3. User already logged in? → YES = redirect to home

Response: Shows OTP input form (6 boxes)
Session: MUST have otp_phone
```

#### **Step 4: Submit OTP** (`POST /otp-verify`)
```
User Action: Enter 6-digit OTP + submit
Controller: WebOtpController@verifyOtp()

Process:
1. Get phone from session('otp_phone')
2. Get OTP from form input
3. Query MySQL otps table:
   WHERE phone = ? 
   AND otp = ? 
   AND expires_at > now()
   AND verified = false
4. If not found → increment attempts, return error
5. If attempts >= 5 → return error
6. Mark OTP as verified in MySQL
7. Check Firebase users collection by phoneNumber
8a. User exists in Firebase:
   - Check MySQL users table
   - If not exists: create from Firebase data
   - Login user
   - Redirect to /home
8b. User NOT in Firebase:
   - Redirect to /otp-register
   - Must complete registration

Response: Login + redirect OR redirect to register
Session: Cleared (otp_phone removed)
```

#### **Step 5: Registration** (`GET/POST /otp-register`) [IF NEW USER]
```
User Action: Fill registration form
Controller: WebOtpController@completeRegistration()

Process:
1. Get phone from session('otp_phone')
2. Create user in Firebase users collection
3. Create user in MySQL users table
4. Login user
5. Clear session

Response: Login + redirect to /home
```

---

### **MOBILE APP FLOW (API)**

#### **Step 1: Send OTP** (`POST /api/send-otp`)
```json
Request:
{
  "phone": "9876543210"
}

Process:
1. Validate phone
2. Check rate limiting
3. Generate OTP
4. Save to MySQL otps table (SAME TABLE AS WEB)
5. Send SMS

Response:
{
  "success": true,
  "message": "OTP sent successfully",
  "expires_in": 600
}
```

#### **Step 2: Verify OTP** (`POST /api/verify-otp`)
```json
Request:
{
  "phone": "9876543210",
  "otp": "123456"
}

Process:
1. Validate inputs
2. Check MySQL otps table (SAME AS WEB)
3. If valid:
   - Mark as verified
   - Find or create user in MySQL
   - Generate Sanctum API token
   - Generate Firebase custom token
   - Return both tokens

Response:
{
  "success": true,
  "user": {...},
  "token": "sanctum_token",
  "token_type": "Bearer",
  "firebase_custom_token": "firebase_token"
}
```

---

## 🗄️ **Database Tables Analysis**

### **1. MySQL `otps` Table**
```sql
Purpose: Store OTP codes for verification
Shared: YES - Web & Mobile use same table

Columns:
- id (PK)
- phone (indexed)
- otp (6 chars, indexed)
- expires_at (timestamp, indexed)
- verified (boolean)
- attempts (integer, max 5)
- created_at
- updated_at

Lifecycle:
1. Created when OTP sent
2. Updated when verified
3. Deleted on resend
4. Can be reused for same phone (updateOrCreate)
```

### **2. MySQL `users` Table**
```sql
Purpose: Store user accounts
Shared: YES - Web & Mobile create users here

Columns:
- id (PK)
- name
- first_name
- last_name  
- phone (unique)
- email (unique)
- password (hashed, random for OTP users)
- email_verified_at
- firebase_uid (links to Firebase)
- remember_token
- created_at
- updated_at

Relationships:
- firebase_uid → Firebase users collection 'id'
- phone → otps table 'phone'
- id → personal_access_tokens 'tokenable_id'
```

### **3. MySQL `personal_access_tokens` Table (Sanctum)**
```sql
Purpose: Store API tokens for mobile app
Used By: Mobile app only (not web)

Columns:
- id (PK)
- tokenable_type ('App\\Models\\User')
- tokenable_id (user id)
- name ('otp-auth')
- token (hashed)
- abilities (JSON)
- last_used_at
- expires_at
- created_at
- updated_at

Usage:
- Created when mobile verifies OTP
- Used for API authentication
- Bearer token in headers
```

### **4. MySQL `vendor_users` Table**
```sql
Purpose: Vendor/admin accounts
OTP Relevance: NONE - Uses separate authentication
Note: Not related to customer OTP system
```

---

## 🔥 **Firebase Authentication Integration**

### **NOT Using Firebase Phone Auth!**

**What We DON'T Use:**
- ❌ Firebase Phone Authentication (`signInWithPhoneNumber`)
- ❌ Firebase reCAPTCHA verification
- ❌ Firebase SMS sending

**What We DO Use:**
- ✅ MySQL-based OTP verification
- ✅ SMSCountry for SMS sending
- ✅ Firebase Custom Tokens for app authentication
- ✅ Firebase Firestore `users` collection for data sync

### **Why Custom Implementation?**

Firebase Phone Auth has limitations:
- Requires reCAPTCHA (not mobile-friendly)
- SMS costs via Firebase
- Less control over OTP format
- Can't use existing SMS provider

Our approach:
- **MySQL** = OTP storage & verification
- **SMSCountry** = SMS delivery (cheaper, more control)
- **Firebase** = User data sync & mobile app auth

---

## 🔐 **Firebase Custom Token Flow**

### **For Mobile App:**

```javascript
1. Mobile calls /api/verify-otp
   ↓
2. Server validates OTP against MySQL
   ↓
3. Server generates:
   - Sanctum API token (for Laravel API)
   - Firebase custom token (for Firebase Auth)
   ↓
4. Mobile receives both tokens
   ↓
5. Mobile uses Firebase SDK:
   firebase.auth().signInWithCustomToken(customToken)
   ↓
6. Mobile authenticated with Firebase
   ↓
7. Can access Firestore data with user's UID
```

### **Custom Token Creation:**
```php
// In OTPController@verifyOtp (API)
$firebaseUid = 'user_' . $user->id;
$customToken = $this->firebaseService->createCustomToken($firebaseUid);

// Returns JWT token that mobile app uses to authenticate with Firebase
```

---

## 🐛 **Why 419 Error Happens**

### **Common Causes:**

| Cause | Explanation | Solution |
|-------|-------------|----------|
| **Direct Access** | Visiting /otp-verify without /otp-send first | Must start from /otp-login |
| **Session Lost** | Session expired or not maintained | Check session config |
| **CSRF Expired** | Page loaded, waited >2 hours | Refresh page before submit |
| **Cookie Issues** | Browser blocking cookies | Enable cookies |
| **HTTPS/HTTP Mix** | Switching protocols | Use consistent protocol |

### **Your Specific Issue:**

You're accessing `/otp-verify` **directly** in the URL bar. This means:
1. No session('otp_phone') exists
2. Controller redirects you back to /otp-login
3. But the redirect might not be happening properly
4. Form submits without session
5. → 419 error

---

## ✅ **SOLUTION: Complete OTP System Improvements**

Let me create a robust fix that handles all edge cases:

