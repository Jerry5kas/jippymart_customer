# JippyMart API Authentication Guide

## 🔐 Authentication System Overview

JippyMart uses a **two-step authentication system**:
1. **SMS Country OTP Verification** - Phone number verification via SMS
2. **Laravel Sanctum Token** - API authentication token for subsequent requests

---

## 📱 Step 1: SMS Country OTP Authentication

### 1.1 Send OTP
**Endpoint:** `POST /api/send-otp`

**Request Body:**
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

### 1.2 Verify OTP
**Endpoint:** `POST /api/verify-otp`

**Request Body:**
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
    "id": 123,
    "name": "User_3210",
    "phone": "9876543210",
    "email": "9876543210@jippymart.in"
  },
  "token": "1|abc123def456...", // Laravel Sanctum Token
  "token_type": "Bearer",
  "firebase_custom_token": "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9..."
}
```

---

## 🔑 Step 2: Using Laravel Sanctum Token

### 2.1 Token Format
- **Token Type:** `Bearer`
- **Header:** `Authorization: Bearer {token}`
- **Source:** Obtained from `/api/verify-otp` response

### 2.2 Example Usage
```bash
curl -X GET "https://jippymart.in/api/mart/user-profile" \
  -H "Authorization: Bearer 1|abc123def456..." \
  -H "Accept: application/json"
```

---

## 🛡️ Protected vs Public Endpoints

### ✅ **Public Endpoints (No Authentication Required)**
These endpoints work without any authentication token:

| Endpoint | Method | Purpose |
|----------|--------|---------|
| `/api/mart/categories` | GET | Get mart categories |
| `/api/mart/items` | GET | Get mart items |
| `/api/mart/item-details` | POST | Get item details |
| `/api/mart/search-items` | POST | Search items |
| `/api/mart/vendor-details` | POST | Get vendor details |
| `/api/mart/nearby-vendors` | POST | Get nearby vendors |
| `/api/mart/vendor-working-hours` | POST | Get vendor working hours |
| `/api/mart/vendor-special-discounts` | POST | Get vendor discounts |
| `/api/mart/vendor-items-by-category` | POST | Get vendor items by category |

### 🔒 **Protected Endpoints (Authentication Required)**
These endpoints require the Laravel Sanctum token:

| Endpoint | Method | Purpose |
|----------|--------|---------|
| `/api/mart/user-profile` | GET | Get user profile |
| `/api/mart/update-user-profile` | POST | Update user profile |

---

## 🚨 **Mobile App Authentication Issues**

### Problem: Categories Not Loading
If your mobile app is having trouble fetching categories, check:

1. **No Authentication Required**: Categories endpoint is **PUBLIC**
   ```bash
   GET https://jippymart.in/api/mart/categories
   ```
   - No `Authorization` header needed
   - No token required

2. **Common Issues**:
   - ❌ **Wrong:** Sending auth token to public endpoints
   - ❌ **Wrong:** Missing `Accept: application/json` header
   - ✅ **Correct:** Direct API call without authentication

### Problem: User Profile Not Loading
If user profile endpoints fail:

1. **Check Token Validity**:
   ```bash
   # Test token with a simple request
   curl -X GET "https://jippymart.in/api/user" \
     -H "Authorization: Bearer YOUR_TOKEN" \
     -H "Accept: application/json"
   ```

2. **Token Expired**: Get new token via OTP verification
3. **Invalid Token Format**: Ensure `Bearer ` prefix

---

## 🔄 Token Refresh Process

### When Token Expires:
1. **Call OTP verification again**:
   ```bash
   POST /api/verify-otp
   {
     "phone": "9876543210",
     "otp": "123456"
   }
   ```

2. **Get new token** from response
3. **Update mobile app** with new token

### Firebase Token Refresh:
```bash
POST /api/refresh-firebase-token
Authorization: Bearer YOUR_SANCTUM_TOKEN
```

---

## 📋 Postman Collection Setup

### 1. Environment Variables
```json
{
  "base_url": "https://jippymart.in",
  "auth_token": "your_laravel_sanctum_token_here"
}
```

### 2. Authentication Flow
1. **Send OTP**: `POST {{base_url}}/api/send-otp`
2. **Verify OTP**: `POST {{base_url}}/api/verify-otp`
3. **Copy Token**: From verify-otp response
4. **Set Variable**: Update `auth_token` in environment
5. **Test Protected Endpoints**: Use `Bearer {{auth_token}}`

---

## 🛠️ Troubleshooting

### Common Error Responses

#### 401 Unauthorized
```json
{
  "success": false,
  "message": "User not authenticated"
}
```
**Solution**: Get valid token via OTP verification

#### 422 Validation Error
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "phone": ["The phone field is required."]
  }
}
```
**Solution**: Check request body format

#### 404 Not Found
```json
{
  "success": false,
  "message": "User not found"
}
```
**Solution**: User doesn't exist, create via OTP verification

---

## 📞 SMS Country Integration

### SMS Configuration
- **Provider**: SMS Country
- **API URL**: `https://restapi.smscountry.com/v0.1/Accounts/...`
- **Sender ID**: `JIPPYM`
- **OTP Validity**: 10 minutes
- **Rate Limit**: 1 OTP per minute per phone

### SMS Text Format
```
Your OTP for jippymart login is {OTP}. Please do not share this OTP with anyone. It is valid for the next 10 minutes-jippymart.in.
```

---

## 🔐 Security Features

1. **Rate Limiting**: 1 OTP request per minute
2. **OTP Expiration**: 10 minutes validity
3. **Attempt Limiting**: Max 5 failed attempts
4. **Token Security**: Laravel Sanctum with expiration
5. **Firebase Integration**: Custom tokens for mobile apps

---

## 📱 Mobile App Integration

### Android/iOS Implementation
1. **Store Token Securely**: Use secure storage (Keychain/Keystore)
2. **Auto-refresh**: Implement token refresh logic
3. **Error Handling**: Handle 401 responses gracefully
4. **Offline Support**: Cache public data (categories, items)

### Token Management
```javascript
// Store token after OTP verification
const token = response.data.token;
await SecureStore.setItemAsync('auth_token', token);

// Use token in API calls
const headers = {
  'Authorization': `Bearer ${token}`,
  'Accept': 'application/json'
};
```

---

## ✅ Summary

- **Categories endpoint is PUBLIC** - No authentication needed
- **User profile endpoints are PROTECTED** - Require Sanctum token
- **Token comes from SMS Country OTP verification**
- **Mobile apps should handle both public and protected endpoints correctly**

For any authentication issues, ensure you're following the correct flow: **SMS OTP → Sanctum Token → API Calls**.

