# 🧪 Manual Testing Guide - Catering Service API

## 🚀 **Quick Setup**

### **Base URL**
```
http://your-domain.com/api/catering
```

### **Required Headers**
```
Content-Type: application/json
Accept: application/json
```

### **Admin Headers (for admin endpoints)**
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer YOUR_ADMIN_TOKEN
```

---

## 📋 **API Endpoints to Test**

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/api/catering/requests` | Create request | No |
| GET | `/api/catering/requests/{id}` | Get request | No |
| GET | `/api/catering/requests` | List requests | Yes (Admin) |
| PUT | `/api/catering/requests/{id}` | Update status | Yes (Admin) |

---

## 🎯 **Step-by-Step Manual Testing**

### **Step 1: Test Basic Request Creation**

#### **Test 1.1: Create Veg-Only Request**
**URL:** `POST http://your-domain.com/api/catering/requests`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Body:**
```json
{
    "name": "Rajesh Kumar",
    "mobile": "9876543210",
    "alternative_mobile": "8765432109",
    "email": "rajesh@example.com",
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

**✅ What to Check:**
- Status code: 201
- Response contains `id` and `reference_number`
- `email_sent` is true
- Check your email for admin notification

**📝 Save the `id` from response for next tests!**

---

#### **Test 1.2: Create Non-Veg Request**
**URL:** `POST http://your-domain.com/api/catering/requests`

**Body:**
```json
{
    "name": "Priya Sharma",
    "mobile": "8765432109",
    "alternative_mobile": "7654321098",
    "email": "priya@example.com",
    "place": "Taj Hotel, Delhi - Connaught Place",
    "date": "2024-12-30",
    "guests": 25,
    "function_type": "Corporate Event",
    "meal_preference": "non_veg",
    "special_requirements": "High quality non-vegetarian dishes required"
}
```

---

#### **Test 1.3: Create Both Veg & Non-Veg Request**
**URL:** `POST http://your-domain.com/api/catering/requests`

**Body:**
```json
{
    "name": "Amit Singh",
    "mobile": "7654321098",
    "alternative_mobile": "6543210987",
    "email": "amit@example.com",
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

#### **Test 1.4: Create Request Without Email**
**URL:** `POST http://your-domain.com/api/catering/requests`

**Body:**
```json
{
    "name": "Suresh Patel",
    "mobile": "6543210987",
    "alternative_mobile": "5432109876",
    "place": "Oberoi Hotel, Chennai - Marina Beach",
    "date": "2025-02-14",
    "guests": 30,
    "function_type": "Anniversary",
    "meal_preference": "veg"
}
```

---

### **Step 2: Test Validation Errors**

#### **Test 2.1: Invalid Mobile Number**
**URL:** `POST http://your-domain.com/api/catering/requests`

**Body:**
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

#### **Test 2.2: Invalid Alternative Mobile**
**URL:** `POST http://your-domain.com/api/catering/requests`

**Body:**
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

#### **Test 2.3: Past Date**
**URL:** `POST http://your-domain.com/api/catering/requests`

**Body:**
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

#### **Test 2.4: Missing Required Fields**
**URL:** `POST http://your-domain.com/api/catering/requests`

**Body:**
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

#### **Test 2.5: Invalid Email**
**URL:** `POST http://your-domain.com/api/catering/requests`

**Body:**
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

#### **Test 2.6: Missing Meal Counts for 'Both'**
**URL:** `POST http://your-domain.com/api/catering/requests`

**Body:**
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

#### **Test 2.7: Meal Count Mismatch**
**URL:** `POST http://your-domain.com/api/catering/requests`

**Body:**
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

### **Step 3: Test Security Features**

#### **Test 3.1: Spam Detection - Honeypot**
**URL:** `POST http://your-domain.com/api/catering/requests`

**Body:**
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

#### **Test 3.2: Spam Detection - Keywords**
**URL:** `POST http://your-domain.com/api/catering/requests`

**Body:**
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

### **Step 4: Test Get Request**

#### **Test 4.1: Get Request by ID (Success)**
**URL:** `GET http://your-domain.com/api/catering/requests/REQ1705123456789`
*(Use the ID from Step 1.1)*

**Headers:**
```
Accept: application/json
```

**Expected Response (200):**
```json
{
    "success": true,
    "data": {
        "id": "REQ1705123456789",
        "name": "Rajesh Kumar",
        "mobile": "9876543210",
        "alternative_mobile": "8765432109",
        "email": "rajesh@example.com",
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

#### **Test 4.2: Get Request by ID (Not Found)**
**URL:** `GET http://your-domain.com/api/catering/requests/nonexistent`

**Expected Response (404):**
```json
{
    "success": false,
    "message": "Request not found"
}
```

---

### **Step 5: Test Admin Endpoints**

#### **Test 5.1: Get All Requests (Admin)**
**URL:** `GET http://your-domain.com/api/catering/requests`

**Headers:**
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer YOUR_ADMIN_TOKEN
```

**Expected Response (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": "REQ1705123456789",
            "name": "Rajesh Kumar",
            "status": "pending",
            "reference_number": "CAT-2024-1234",
            "created_at": "2024-01-15T10:30:00Z"
        }
    ],
    "count": 1
}
```

---

#### **Test 5.2: Get All Requests with Filters**
**URL:** `GET http://your-domain.com/api/catering/requests?status=pending&date_from=2024-01-01&date_to=2024-12-31`

**Headers:**
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer YOUR_ADMIN_TOKEN
```

---

#### **Test 5.3: Update Request Status (Success)**
**URL:** `PUT http://your-domain.com/api/catering/requests/REQ1705123456789`
*(Use the ID from Step 1.1)*

**Headers:**
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer YOUR_ADMIN_TOKEN
```

**Body:**
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

#### **Test 5.4: Update Request Status (Invalid Status)**
**URL:** `PUT http://your-domain.com/api/catering/requests/REQ1705123456789`

**Headers:**
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer YOUR_ADMIN_TOKEN
```

**Body:**
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

#### **Test 5.5: Update Request Status (Not Found)**
**URL:** `PUT http://your-domain.com/api/catering/requests/nonexistent`

**Headers:**
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer YOUR_ADMIN_TOKEN
```

**Body:**
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

### **Step 6: Test Rate Limiting**

#### **Test 6.1: Rate Limiting Test**
**URL:** `POST http://your-domain.com/api/catering/requests`

**Body:**
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

**Instructions:**
1. Send this request 11 times quickly (limit is 10 per minute)
2. First 10 requests should return 201
3. 11th request should return 429

**Expected Response (429):**
```json
{
    "message": "Too Many Attempts."
}
```

---

## 📋 **Manual Testing Checklist**

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
- [ ] Invalid alternative mobile
- [ ] Past date
- [ ] Missing required fields
- [ ] Invalid email format
- [ ] Missing meal counts for 'both'
- [ ] Meal count mismatch

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

## 🎯 **Quick Test Sequence (5 minutes)**

1. **Test 1.1** - Create successful request → **Save the ID**
2. **Test 4.1** - Get the created request (use saved ID)
3. **Test 5.3** - Update status (admin, use saved ID)
4. **Test 5.1** - List all requests (admin)
5. **Test 2.1** - Test validation error

This covers all major functionality in minimal time!

---

## 🛠️ **Tools You Can Use**

### **Option 1: cURL Commands**
```bash
# Create request
curl -X POST http://your-domain.com/api/catering/requests \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","mobile":"9876543210","place":"Test Venue","date":"2024-12-25","guests":10,"function_type":"Wedding","meal_preference":"veg"}'

# Get request
curl -X GET http://your-domain.com/api/catering/requests/REQ1705123456789 \
  -H "Accept: application/json"
```

### **Option 2: Browser Extensions**
- **REST Client** (VS Code extension)
- **Thunder Client** (VS Code extension)
- **Insomnia** (Desktop app)
- **HTTPie** (Command line)

### **Option 3: Online Tools**
- **reqbin.com**
- **httpie.io**
- **postwoman.io**

---

## 📧 **Email Testing**

### **Check Admin Email**
After creating a request, check your admin email for:
- Subject: "New Catering Request - [Name] - [Date]"
- Content includes all request details
- Alternative mobile appears if provided

### **Email Content Example**
```
NEW CATERING REQUEST
Reference: CAT-2024-1234

Name: Rajesh Kumar
Mobile: 9876543210
Alternative Mobile: 8765432109
Email: rajesh@example.com
Venue: Grand Hotel, Mumbai - Near Airport
Date: 2024-12-25
Guests: 50 people
Event Type: Wedding
Meal Preference: Veg

Special Requirements: Vegetarian food should be Jain, no onion garlic
Submitted: Jan 15, 2024 10:30 AM

Please contact the customer within 24 hours.
JippyMart Catering Service
```

---

## 🚨 **Common Issues & Solutions**

### **Issue: 500 Internal Server Error**
- Check Laravel logs: `storage/logs/laravel.log`
- Verify database connection
- Check email configuration

### **Issue: Email Not Sending**
- Check `.env` file for mail settings
- Verify `MAIL_HOST`, `MAIL_USERNAME`, `MAIL_PASSWORD`
- Check admin email in config

### **Issue: Validation Not Working**
- Check mobile number format (must start with 6-9)
- Verify date is in future
- Ensure all required fields are provided

### **Issue: Admin Endpoints Not Working**
- Verify admin token is valid
- Check authentication middleware
- Ensure token is in Authorization header

---

## 🎯 **Success Criteria**

Your catering service is working correctly if:
- ✅ All success scenarios return 201/200 status
- ✅ All validation errors return 422 status
- ✅ Spam detection returns 400 status
- ✅ Rate limiting returns 429 status
- ✅ Admin emails are sent successfully
- ✅ Database stores requests correctly
- ✅ All endpoints respond within 2 seconds

This manual testing guide covers everything you need to test your catering service thoroughly! 🚀
