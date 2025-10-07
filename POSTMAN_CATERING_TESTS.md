# Postman Testing Guide - Catering API

## 🚀 **Environment Setup**

### **Base URL**
```
{{base_url}}/api/catering
```

### **Headers (for all requests)**
```
Content-Type: application/json
Accept: application/json
```

---

## 📝 **Request Fields**

### **Required Fields:**
- `name` - Customer name (2-255 characters)
- `mobile` - Primary mobile number (10 digits, starts with 6-9)
- `place` - Venue details (10-1000 characters)
- `date` - Event date (must be future date)
- `guests` - Number of guests (1-10000)
- `function_type` - Type of event (3-100 characters)
- `meal_preference` - veg, non_veg, or both

### **Optional Fields:**
- `alternative_mobile` - Alternative mobile number (10 digits, starts with 6-9)
- `email` - Customer email (valid email format)
- `veg_count` - Vegetarian count (required if meal_preference = both)
- `nonveg_count` - Non-vegetarian count (required if meal_preference = both)
- `special_requirements` - Special requirements (max 2000 characters)

### **Hidden Fields (Security):**
- `website` - Honeypot field (must be empty)

---

## 📋 **Test Cases**

### **Test 1: Create Request - Success (Veg Only)**

**Method:** `POST`  
**URL:** `{{base_url}}/api/catering/requests`  
**Headers:** `Content-Type: application/json`

**Body (JSON):**
```json
{
    "name": "John Doe",
    "mobile": "9876543210",
    "alternative_mobile": "8765432109",
    "email": "john@example.com",
    "place": "Grand Hotel, Mumbai - Near Airport",
    "date": "2024-12-25",
    "guests": 50,
    "function_type": "Wedding",
    "meal_preference": "veg",
    "special_requirements": "Vegetarian food should be Jain, no onion garlic"
}
```

**Expected Response (201):**
```json
{
    "success": true,
    "message": "Catering request submitted successfully",
    "data": {
        "id": "REQ1705123456789",
        "reference_number": "CAT-2024-1234",
        "status": "pending",
        "created_at": "2024-01-15T10:30:00Z",
        "email_sent": true
    }
}
```

**Save Response:** Copy the `id` from response for next tests.

---

### **Test 2: Create Request - Success (Non-Veg Only)**

**Method:** `POST`  
**URL:** `{{base_url}}/api/catering/requests`

**Body (JSON):**
```json
{
    "name": "Jane Smith",
    "mobile": "8765432109",
    "alternative_mobile": "7654321098",
    "email": "jane@example.com",
    "place": "Taj Hotel, Delhi - Connaught Place",
    "date": "2024-12-30",
    "guests": 25,
    "function_type": "Corporate Event",
    "meal_preference": "non_veg",
    "special_requirements": "High quality non-vegetarian dishes required"
}
```

---

### **Test 3: Create Request - Success (Both Veg & Non-Veg)**

**Method:** `POST`  
**URL:** `{{base_url}}/api/catering/requests`

**Body (JSON):**
```json
{
    "name": "Rajesh Kumar",
    "mobile": "7654321098",
    "alternative_mobile": "6543210987",
    "email": "rajesh@example.com",
    "place": "Leela Palace, Bangalore - Electronic City",
    "date": "2025-01-15",
    "guests": 100,
    "function_type": "Birthday Party",
    "meal_preference": "both",
    "veg_count": 60,
    "nonveg_count": 40,
    "special_requirements": "Mix of North and South Indian cuisine"
}
```

---

### **Test 4: Create Request - Without Email (Optional)**

**Method:** `POST`  
**URL:** `{{base_url}}/api/catering/requests`

**Body (JSON):**
```json
{
    "name": "Priya Sharma",
    "mobile": "6543210987",
    "place": "Oberoi Hotel, Chennai - Marina Beach",
    "date": "2025-02-14",
    "guests": 30,
    "function_type": "Anniversary",
    "meal_preference": "veg"
}
```

---

## ❌ **Validation Error Tests**

### **Test 5: Validation Error - Invalid Mobile**

**Method:** `POST`  
**URL:** `{{base_url}}/api/catering/requests`

**Body (JSON):**
```json
{
    "name": "Test User",
    "mobile": "1234567890",
    "place": "Test Venue",
    "date": "2024-12-25",
    "guests": 10,
    "function_type": "Wedding",
    "meal_preference": "veg"
}
```

**Expected Response (422):**
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "mobile": ["The mobile format is invalid."]
    }
}
```

---

### **Test 6: Validation Error - Past Date**

**Method:** `POST`  
**URL:** `{{base_url}}/api/catering/requests`

**Body (JSON):**
```json
{
    "name": "Test User",
    "mobile": "9876543210",
    "place": "Test Venue",
    "date": "2023-01-01",
    "guests": 10,
    "function_type": "Wedding",
    "meal_preference": "veg"
}
```

**Expected Response (422):**
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "date": ["The date must be a date after today."]
    }
}
```

---

### **Test 7: Validation Error - Missing Required Fields**

**Method:** `POST`  
**URL:** `{{base_url}}/api/catering/requests`

**Body (JSON):**
```json
{
    "name": "Test User"
}
```

**Expected Response (422):**
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "mobile": ["The mobile field is required."],
        "place": ["The place field is required."],
        "date": ["The date field is required."],
        "guests": ["The guests field is required."],
        "function_type": ["The function type field is required."],
        "meal_preference": ["The meal preference field is required."]
    }
}
```

---

### **Test 8: Validation Error - Invalid Email**

**Method:** `POST`  
**URL:** `{{base_url}}/api/catering/requests`

**Body (JSON):**
```json
{
    "name": "Test User",
    "mobile": "9876543210",
    "email": "invalid-email",
    "place": "Test Venue",
    "date": "2024-12-25",
    "guests": 10,
    "function_type": "Wedding",
    "meal_preference": "veg"
}
```

**Expected Response (422):**
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "email": ["The email must be a valid email address."]
    }
}
```

---

### **Test 9: Validation Error - Both Meal Preference Missing Counts**

**Method:** `POST`  
**URL:** `{{base_url}}/api/catering/requests`

**Body (JSON):**
```json
{
    "name": "Test User",
    "mobile": "9876543210",
    "place": "Test Venue",
    "date": "2024-12-25",
    "guests": 50,
    "function_type": "Wedding",
    "meal_preference": "both"
}
```

**Expected Response (422):**
```json
{
    "success": false,
    "message": "Vegetarian and non-vegetarian counts are required"
}
```

---

### **Test 10: Validation Error - Meal Count Mismatch**

**Method:** `POST`  
**URL:** `{{base_url}}/api/catering/requests`

**Body (JSON):**
```json
{
    "name": "Test User",
    "mobile": "9876543210",
    "place": "Test Venue",
    "date": "2024-12-25",
    "guests": 50,
    "function_type": "Wedding",
    "meal_preference": "both",
    "veg_count": 30,
    "nonveg_count": 10
}
```

**Expected Response (422):**
```json
{
    "success": false,
    "message": "Vegetarian + non-vegetarian count must equal total guests"
}
```

---

### **Test 10.1: Validation Error - Invalid Alternative Mobile**

**Method:** `POST`  
**URL:** `{{base_url}}/api/catering/requests`

**Body (JSON):**
```json
{
    "name": "Test User",
    "mobile": "9876543210",
    "alternative_mobile": "1234567890",
    "place": "Test Venue",
    "date": "2024-12-25",
    "guests": 10,
    "function_type": "Wedding",
    "meal_preference": "veg"
}
```

**Expected Response (422):**
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "alternative_mobile": ["The alternative mobile format is invalid."]
    }
}
```

---

## 🚫 **Spam Detection Tests**

### **Test 11: Spam Detection - Honeypot Field**

**Method:** `POST`  
**URL:** `{{base_url}}/api/catering/requests`

**Body (JSON):**
```json
{
    "name": "Test User",
    "mobile": "9876543210",
    "place": "Test Venue",
    "date": "2024-12-25",
    "guests": 10,
    "function_type": "Wedding",
    "meal_preference": "veg",
    "website": "http://spam.com"
}
```

**Expected Response (400):**
```json
{
    "success": false,
    "message": "Request blocked"
}
```

---

### **Test 12: Spam Detection - Spam Keywords**

**Method:** `POST`  
**URL:** `{{base_url}}/api/catering/requests`

**Body (JSON):**
```json
{
    "name": "Spam User",
    "mobile": "9876543210",
    "place": "Test Venue for spam",
    "date": "2024-12-25",
    "guests": 10,
    "function_type": "Wedding",
    "meal_preference": "veg"
}
```

**Expected Response (400):**
```json
{
    "success": false,
    "message": "Request blocked"
}
```

---

## 📊 **Get Request Tests**

### **Test 13: Get Request by ID - Success**

**Method:** `GET`  
**URL:** `{{base_url}}/api/catering/requests/REQ1705123456789`  
*(Use ID from Test 1 response)*

**Expected Response (200):**
```json
{
    "success": true,
    "data": {
        "id": "REQ1705123456789",
        "name": "John Doe",
        "mobile": "9876543210",
        "email": "john@example.com",
        "place": "Grand Hotel, Mumbai - Near Airport",
        "date": "2024-12-25",
        "guests": 50,
        "function_type": "Wedding",
        "meal_preference": "veg",
        "special_requirements": "Vegetarian food should be Jain, no onion garlic",
        "status": "pending",
        "reference_number": "CAT-2024-1234",
        "created_at": "2024-01-15T10:30:00Z",
        "updated_at": "2024-01-15T10:30:00Z"
    }
}
```

---

### **Test 14: Get Request by ID - Not Found**

**Method:** `GET`  
**URL:** `{{base_url}}/api/catering/requests/nonexistent`

**Expected Response (404):**
```json
{
    "success": false,
    "message": "Request not found"
}
```

---

## 👨‍💼 **Admin Tests (Require Authentication)**

### **Test 15: Get All Requests - Success**

**Method:** `GET`  
**URL:** `{{base_url}}/api/catering/requests`  
**Headers:** 
```
Content-Type: application/json
Authorization: Bearer {{admin_token}}
```

**Expected Response (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": "REQ1705123456789",
            "name": "John Doe",
            "status": "pending",
            "reference_number": "CAT-2024-1234",
            "created_at": "2024-01-15T10:30:00Z"
        }
    ],
    "count": 1
}
```

---

### **Test 16: Get All Requests with Filters**

**Method:** `GET`  
**URL:** `{{base_url}}/api/catering/requests?status=pending&date_from=2024-01-01&date_to=2024-12-31`  
**Headers:** 
```
Content-Type: application/json
Authorization: Bearer {{admin_token}}
```

---

### **Test 17: Update Request Status - Success**

**Method:** `PUT`  
**URL:** `{{base_url}}/api/catering/requests/REQ1705123456789`  
**Headers:** 
```
Content-Type: application/json
Authorization: Bearer {{admin_token}}
```

**Body (JSON):**
```json
{
    "status": "confirmed"
}
```

**Expected Response (200):**
```json
{
    "success": true,
    "message": "Request status updated successfully",
    "data": {
        "id": "REQ1705123456789",
        "status": "confirmed",
        "updated_at": "2024-01-15T11:00:00Z"
    }
}
```

---

### **Test 18: Update Request Status - Invalid Status**

**Method:** `PUT`  
**URL:** `{{base_url}}/api/catering/requests/REQ1705123456789`  
**Headers:** 
```
Content-Type: application/json
Authorization: Bearer {{admin_token}}
```

**Body (JSON):**
```json
{
    "status": "invalid_status"
}
```

**Expected Response (422):**
```json
{
    "success": false,
    "message": "Invalid status",
    "errors": {
        "status": ["The selected status is invalid."]
    }
}
```

---

### **Test 19: Update Request Status - Not Found**

**Method:** `PUT`  
**URL:** `{{base_url}}/api/catering/requests/nonexistent`  
**Headers:** 
```
Content-Type: application/json
Authorization: Bearer {{admin_token}}
```

**Body (JSON):**
```json
{
    "status": "confirmed"
}
```

**Expected Response (404):**
```json
{
    "success": false,
    "message": "Request not found or update failed"
}
```

---

## 🚦 **Rate Limiting Tests**

### **Test 20: Rate Limiting - Too Many Requests**

**Method:** `POST`  
**URL:** `{{base_url}}/api/catering/requests`

**Body (JSON):**
```json
{
    "name": "Rate Limit Test",
    "mobile": "9876543210",
    "place": "Test Venue",
    "date": "2024-12-25",
    "guests": 10,
    "function_type": "Wedding",
    "meal_preference": "veg"
}
```

**Note:** Send this request 11 times quickly (limit is 10 per minute)

**Expected Response (429):**
```json
{
    "message": "Too Many Attempts."
}
```

---

## 📋 **Testing Checklist**

### **✅ Success Scenarios**
- [ ] Create request (veg only)
- [ ] Create request (non-veg only)  
- [ ] Create request (both veg & non-veg)
- [ ] Create request without email
- [ ] Get request by ID
- [ ] Get all requests (admin)
- [ ] Update request status (admin)

### **✅ Validation Error Scenarios**
- [ ] Invalid mobile number
- [ ] Past date
- [ ] Missing required fields
- [ ] Invalid email format
- [ ] Missing meal counts for 'both'
- [ ] Meal count mismatch
- [ ] Invalid alternative mobile

### **✅ Security Scenarios**
- [ ] Spam detection (honeypot)
- [ ] Spam detection (keywords)
- [ ] Rate limiting

### **✅ Error Scenarios**
- [ ] Get non-existent request
- [ ] Update non-existent request
- [ ] Invalid status update
- [ ] Unauthorized admin access

---

## 🎯 **Quick Test Sequence**

1. **Test 1** - Create successful request
2. **Test 13** - Get the created request
3. **Test 17** - Update status (admin)
4. **Test 15** - List all requests (admin)
5. **Test 5** - Test validation error
6. **Test 11** - Test spam detection

This covers all major functionality in minimal time!
