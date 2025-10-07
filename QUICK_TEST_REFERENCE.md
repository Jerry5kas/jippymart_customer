# 🚀 Quick Test Reference - Catering API

## 📋 **Essential Test Data**

### **Valid Mobile Numbers (Start with 6-9)**
```
9876543210, 8765432109, 7654321098, 6543210987
```

### **Invalid Mobile Numbers (Will Fail)**
```
1234567890, 0123456789, 987654321, 98765432100
```

### **Future Dates (Will Pass)**
```
2024-12-25, 2025-01-15, 2025-02-14, 2025-03-20
```

### **Past Dates (Will Fail)**
```
2023-01-01, 2022-12-25, 2021-06-15
```

---

## 🎯 **5-Minute Quick Test**

### **1. Create Request (2 minutes)**
**URL:** `POST http://your-domain.com/api/catering/requests`

**Body:**
```json
{
    "name": "Test User",
    "mobile": "9876543210",
    "alternative_mobile": "8765432109",
    "email": "test@example.com",
    "place": "Test Hotel, Mumbai",
    "date": "2024-12-25",
    "guests": 50,
    "function_type": "Wedding",
    "meal_preference": "veg",
    "special_requirements": "Test requirements"
}
```

**✅ Expected:** Status 201, save the `id` from response

---

### **2. Get Request (1 minute)**
**URL:** `GET http://your-domain.com/api/catering/requests/{id}`
*(Use ID from step 1)*

**✅ Expected:** Status 200, returns request details

---

### **3. Test Validation Error (1 minute)**
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

**✅ Expected:** Status 422, validation error

---

### **4. Test Spam Detection (1 minute)**
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

**✅ Expected:** Status 400, spam blocked

---

## 🛠️ **Testing Tools**

### **Option 1: cURL (Command Line)**
```bash
# Create request
curl -X POST http://your-domain.com/api/catering/requests \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","mobile":"9876543210","place":"Test Venue","date":"2024-12-25","guests":10,"function_type":"Wedding","meal_preference":"veg"}'

# Get request
curl -X GET http://your-domain.com/api/catering/requests/REQ1705123456789 \
  -H "Accept: application/json"
```

### **Option 2: Online Tools**
- **reqbin.com** - Simple HTTP client
- **httpie.io** - User-friendly HTTP client
- **postwoman.io** - Postman alternative

### **Option 3: Browser Extensions**
- **REST Client** (VS Code)
- **Thunder Client** (VS Code)
- **Insomnia** (Desktop app)

---

## 📧 **Email Testing**

### **Check Admin Email After Creating Request**
Look for email with:
- **Subject:** "New Catering Request - [Name] - [Date]"
- **Content:** All request details including alternative mobile
- **From:** Your configured admin email

---

## 🚨 **Common Issues**

### **500 Error**
- Check `storage/logs/laravel.log`
- Verify database connection
- Check email configuration

### **Email Not Sending**
- Check `.env` file mail settings
- Verify `MAIL_HOST`, `MAIL_USERNAME`, `MAIL_PASSWORD`

### **Validation Errors**
- Mobile must start with 6-9
- Date must be future
- All required fields must be provided

---

## ✅ **Success Checklist**

- [ ] Create request returns 201
- [ ] Get request returns 200
- [ ] Validation errors return 422
- [ ] Spam detection returns 400
- [ ] Admin email is sent
- [ ] All responses under 2 seconds

---

## 🎯 **Test Scenarios Summary**

| Test | Method | URL | Expected Status | Purpose |
|------|--------|-----|-----------------|---------|
| Create Success | POST | `/requests` | 201 | Basic functionality |
| Get Success | GET | `/requests/{id}` | 200 | Retrieve data |
| Validation Error | POST | `/requests` | 422 | Input validation |
| Spam Detection | POST | `/requests` | 400 | Security |
| Rate Limiting | POST | `/requests` | 429 | Performance |

This quick reference covers everything you need for manual testing! 🚀
