# Mart API Documentation

## Overview
This document describes the Mart API endpoints designed for mobile applications. These endpoints provide access to user profiles, vendor information, mart categories, and items for grocery/mart applications.

## Base URL
```
https://your-domain.com/api/mart
```

## Authentication
Some endpoints require authentication using Laravel Sanctum. Include the Bearer token in the Authorization header:
```
Authorization: Bearer {your-token}
```

## API Endpoints

### User Management

#### 1. Get User Profile
**GET** `/api/mart/user-profile`

Get the current authenticated user's profile information.

**Authentication required**

#### Response
```json
{
    "success": true,
    "data": {
        "id": "JGlhDUru0mWBTFgCmuWWhNIW2UJ3",
        "active": true,
        "appIdentifier": "web",
        "countryCode": "91",
        "createdAt": "2025-08-21T06:09:56.000Z",
        "email": "jippymart24@gmail.com",
        "firstName": "Jippy",
        "lastName": "Mart",
        "isDocumentVerify": true,
        "phoneNumber": "9390579864",
        "profilePictureURL": "",
        "provider": "email",
        "role": "vendor",
        "subscriptionExpiryDate": null,
        "subscriptionPlanId": null,
        "subscription_plan": null,
        "vType": "mart",
        "vendorID": "4ir2OLhuMEc2yg9L1YxX"
    }
}
```

#### 2. Update User Profile
**POST** `/api/mart/update-user-profile`

Update the current authenticated user's profile information.

**Authentication required**

#### Request Body
```json
{
    "firstName": "Jippy",
    "lastName": "Mart",
    "phoneNumber": "9390579864",
    "profilePictureURL": "https://example.com/profile.jpg",
    "countryCode": "91"
}
```

#### Parameters
- `firstName` (optional): User's first name
- `lastName` (optional): User's last name
- `phoneNumber` (optional): User's phone number
- `profilePictureURL` (optional): URL to profile picture
- `countryCode` (optional): Country code

#### Response
```json
{
    "success": true,
    "message": "Profile updated successfully"
}
```

### Vendor Management

#### 3. Get All Mart Vendors
**GET** `/api/mart/vendors`

Get all mart vendors with optional filtering and pagination.

**No authentication required**

#### Query Parameters
- `is_open` (optional): Filter by open status (true/false)
- `enabled_delivery` (optional): Filter by delivery availability (true/false)
- `category_id` (optional): Filter by category ID
- `search` (optional): Search vendors by title
- `page` (optional): Page number for pagination (default: 1)
- `limit` (optional): Number of vendors per page (default: 20, max: 100)

#### Example Request
```
GET /api/mart/vendors?is_open=true&enabled_delivery=true&page=1&limit=10
```

#### Response
```json
{
    "success": true,
    "data": [
        {
            "id": "4ir2OLhuMEc2yg9L1YxX",
            "title": "Jippy Mart",
            "description": "-",
            "location": "7th Line Ram Nagar",
            "phonenumber": "9390579864",
            "latitude": 15.486759,
            "longitude": 80.049118,
            "coordinates": [15.486759, 80.049118],
            "isOpen": true,
            "enabledDelivery": false,
            "vType": "mart",
            "categoryID": ["68a46e8810c9d"],
            "categoryTitle": ["Groceries"],
            "workingHours": [...],
            "specialDiscount": [...],
            "adminCommission": {
                "commissionType": "Percent",
                "fix_commission": 0,
                "isEnabled": true
            },
            "filters": {...},
            "photos": [],
            "createdAt": "2025-08-21T06:46:37.000Z"
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 10,
        "total": 25,
        "has_more": true,
        "filters_applied": {
            "vType": "mart",
            "isOpen": true,
            "enabledDelivery": true
        }
    }
}
```

#### 4. Get Vendor Details
**GET** `/api/mart/vendor-details/{vendor_id}`

Get detailed information about a specific vendor.

**No authentication required**

#### URL Parameters
- `vendor_id` (required): The unique identifier of the vendor

#### Example Request
```
GET /api/mart/vendor-details/4ir2OLhuMEc2yg9L1YxX
```

#### Response
```json
{
    "success": true,
    "data": {
        "id": "4ir2OLhuMEc2yg9L1YxX",
        "adminCommission": {
            "commissionType": "Percent",
            "fix_commission": 0,
            "isEnabled": true
        },
        "author": "JGlhDUru0mWBTFgCmuWWhNIW2UJ3",
        "authorName": "Jippy Mart",
        "authorProfilePic": null,
        "categoryID": ["68a46e8810c9d"],
        "categoryTitle": ["Groceries"],
        "coordinates": [15.486759, 80.049118],
        "countryCode": "91",
        "createdAt": "2025-08-21T06:46:37.000Z",
        "description": "-",
        "enabledDelivery": false,
        "filters": {
            "Free Wi-Fi": "No",
            "Good for Breakfast": "No",
            "Good for Dinner": "No",
            "Good for Lunch": "No",
            "Live Music": "No",
            "Outdoor Seating": "No",
            "Takes Reservations": "No",
            "Vegetarian Friendly": "No"
        },
        "hidephotos": false,
        "isOpen": true,
        "latitude": 15.486759,
        "location": "7th Line Ram Nagar",
        "longitude": 80.049118,
        "phonenumber": "9390579864",
        "photo": null,
        "photos": [],
        "restaurantCost": 0,
        "restaurantMenuPhotos": [],
        "specialDiscount": [],
        "specialDiscountEnable": false,
        "subscriptionExpiryDate": null,
        "subscriptionPlanId": null,
        "subscriptionTotalOrders": null,
        "subscription_plan": null,
        "title": "Jippy Mart",
        "vType": "mart",
        "workingHours": [
            {
                "day": "Monday",
                "timeslot": [
                    {
                        "from": "09:30",
                        "to": "22:00"
                    }
                ]
            }
        ],
        "zoneId": "BmSTwRFzmP13PnVNFJZJ"
    }
}
```

#### 4. Get Nearby Vendors
**POST** `/api/mart/nearby-vendors`

Get vendors within a specified radius from the given coordinates.

**No authentication required**

#### Request Body
```json
{
    "latitude": 15.486759,
    "longitude": 80.049118,
    "radius": 10,
    "limit": 20
}
```

#### Parameters
- `latitude` (required): User's latitude
- `longitude` (required): User's longitude
- `radius` (optional): Search radius in kilometers (default: 10)
- `limit` (optional): Maximum number of results (default: 20)

#### Response
```json
{
    "success": true,
    "data": [
        {
            "id": "4ir2OLhuMEc2yg9L1YxX",
            "title": "Jippy Mart",
            "location": "7th Line Ram Nagar",
            "latitude": 15.486759,
            "longitude": 80.049118,
            "isOpen": true,
            "distance": 0.5,
            "photo": null,
            "categoryTitle": ["Groceries"]
        }
    ],
    "meta": {
        "latitude": 15.486759,
        "longitude": 80.049118,
        "radius": 10,
        "count": 1
    }
}
```

#### 5. Get Vendor Working Hours
**POST** `/api/mart/vendor-working-hours`

Get working hours for a specific vendor.

**No authentication required**

#### Request Body
```json
{
    "vendor_id": "4ir2OLhuMEc2yg9L1YxX"
}
```

#### Response
```json
{
    "success": true,
    "data": {
        "is_open": true,
        "working_hours": [
            {
                "day": "Monday",
                "timeslot": [
                    {
                        "from": "09:30",
                        "to": "22:00"
                    }
                ]
            },
            {
                "day": "Tuesday",
                "timeslot": [
                    {
                        "from": "09:30",
                        "to": "22:00"
                    }
                ]
            }
        ]
    }
}
```

#### 6. Get Vendor Special Discounts
**POST** `/api/mart/vendor-special-discounts`

Get special discounts offered by a vendor.

**No authentication required**

#### Request Body
```json
{
    "vendor_id": "4ir2OLhuMEc2yg9L1YxX"
}
```

#### Response
```json
{
    "success": true,
    "data": {
        "enabled": false,
        "discounts": []
    }
}
```

### Categories Management

#### 7. Get Mart Categories
**GET** `/api/mart/categories`

Get all mart categories with optional filters.

**No authentication required**

#### Query Parameters
- `vendor_id` (optional): Filter by vendor ID
- `publish` (optional): Filter by publish status (true/false)

#### Example Request
```
GET /api/mart/categories?publish=true
```

#### Response
```json
{
    "success": true,
    "data": [
        {
            "id": "68a46e8810c9d",
            "description": "groceries",
            "photo": "",
            "publish": true,
            "review_attributes": [],
            "show_in_homepage": false,
            "title": "Groceries"
        }
    ]
}
```

### Items Management

#### 8. Get Mart Items
**GET** `/api/mart/items`

Get mart items with pagination and filters.

**No authentication required**

#### Query Parameters
- `vendor_id` (optional): Filter by vendor ID
- `category_id` (optional): Filter by category ID
- `is_available` (optional): Filter by availability (true/false)
- `publish` (optional): Filter by publish status (true/false)
- `search` (optional): Search by item name
- `page` (optional): Page number (default: 1)
- `limit` (optional): Items per page (default: 20, max: 50)

#### Example Request
```
GET /api/mart/items?vendor_id=4ir2OLhuMEc2yg9L1YxX&category_id=68a46e8810c9d&page=1&limit=20
```

#### Response
```json
{
    "success": true,
    "data": [
        {
            "id": "KiyXchVCORnhxB6IAYXn",
            "addOnsPrice": [],
            "addOnsTitle": [],
            "calories": 0,
            "categoryID": "68a46e8810c9d",
            "createdAt": "2025-08-20T01:10:25.000Z",
            "description": "-",
            "disPrice": "0",
            "fats": 0,
            "grams": 0,
            "isAvailable": true,
            "item_attribute": null,
            "name": "Veggies",
            "nonveg": false,
            "photo": "",
            "photos": [],
            "price": "299",
            "product_specification": {},
            "proteins": 0,
            "publish": true,
            "quantity": -1,
            "takeawayOption": false,
            "veg": true,
            "vendorID": "k38CTXHYHUXp9sdIoclp"
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 20,
        "total": 1,
        "has_more": false
    }
}
```

#### 9. Get Item Details
**POST** `/api/mart/item-details`

Get detailed information about a specific item.

**No authentication required**

#### Request Body
```json
{
    "item_id": "KiyXchVCORnhxB6IAYXn"
}
```

#### Response
```json
{
    "success": true,
    "data": {
        "id": "KiyXchVCORnhxB6IAYXn",
        "addOnsPrice": [],
        "addOnsTitle": [],
        "calories": 0,
        "categoryID": "68a46e8810c9d",
        "createdAt": "2025-08-20T01:10:25.000Z",
        "description": "-",
        "disPrice": "0",
        "fats": 0,
        "grams": 0,
        "isAvailable": true,
        "item_attribute": null,
        "name": "Veggies",
        "nonveg": false,
        "photo": "",
        "photos": [],
        "price": "299",
        "product_specification": {},
        "proteins": 0,
        "publish": true,
        "quantity": -1,
        "takeawayOption": false,
        "veg": true,
        "vendorID": "k38CTXHYHUXp9sdIoclp"
    }
}
```

#### 10. Search Items
**POST** `/api/mart/search-items`

Search for items by name with optional filters.

**No authentication required**

#### Request Body
```json
{
    "query": "veggies",
    "vendor_id": "4ir2OLhuMEc2yg9L1YxX",
    "category_id": "68a46e8810c9d",
    "page": 1,
    "limit": 20
}
```

#### Parameters
- `query` (required): Search term (min: 2 characters, max: 100)
- `vendor_id` (optional): Filter by vendor ID
- `category_id` (optional): Filter by category ID
- `page` (optional): Page number (default: 1)
- `limit` (optional): Items per page (default: 20, max: 50)

#### Response
```json
{
    "success": true,
    "data": [
        {
            "id": "KiyXchVCORnhxB6IAYXn",
            "name": "Veggies",
            "price": "299",
            "description": "-",
            "photo": "",
            "isAvailable": true,
            "publish": true
        }
    ],
    "meta": {
        "query": "veggies",
        "current_page": 1,
        "per_page": 20,
        "total": 1,
        "has_more": false
    }
}
```

#### 11. Get Vendor Items by Category
**POST** `/api/mart/vendor-items-by-category`

Get items from a specific vendor filtered by category.

**No authentication required**

#### Request Body
```json
{
    "vendor_id": "4ir2OLhuMEc2yg9L1YxX",
    "category_id": "68a46e8810c9d",
    "page": 1,
    "limit": 20
}
```

#### Parameters
- `vendor_id` (required): Vendor ID
- `category_id` (required): Category ID
- `page` (optional): Page number (default: 1)
- `limit` (optional): Items per page (default: 20, max: 50)

#### Response
```json
{
    "success": true,
    "data": [
        {
            "id": "KiyXchVCORnhxB6IAYXn",
            "name": "Veggies",
            "price": "299",
            "description": "-",
            "photo": "",
            "isAvailable": true,
            "publish": true,
            "categoryID": "68a46e8810c9d",
            "vendorID": "4ir2OLhuMEc2yg9L1YxX"
        }
    ],
    "meta": {
        "vendor_id": "4ir2OLhuMEc2yg9L1YxX",
        "category_id": "68a46e8810c9d",
        "current_page": 1,
        "per_page": 20,
        "total": 1,
        "has_more": false
    }
}
```

## Error Responses

All endpoints return consistent error responses:

### Validation Error (422)
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "vendor_id": ["The vendor id field is required."]
    }
}
```

### Authentication Error (401)
```json
{
    "success": false,
    "message": "User not authenticated"
}
```

### Not Found Error (404)
```json
{
    "success": false,
    "message": "Vendor not found"
}
```

### Server Error (500)
```json
{
    "success": false,
    "message": "Failed to get vendor details: Internal server error"
}
```

## Mobile App Integration Examples

### React Native Example

```javascript
// Get user profile
const getUserProfile = async () => {
    const response = await fetch('/api/mart/user-profile', {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
        }
    });
    
    return await response.json();
};

// Get nearby vendors
const getNearbyVendors = async (latitude, longitude, radius = 10) => {
    const response = await fetch('/api/mart/nearby-vendors', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            latitude,
            longitude,
            radius
        })
    });
    
    return await response.json();
};

// Get vendor items
const getVendorItems = async (vendorId, categoryId = null, page = 1) => {
    let url = `/api/mart/items?vendor_id=${vendorId}&page=${page}`;
    if (categoryId) {
        url += `&category_id=${categoryId}`;
    }
    
    const response = await fetch(url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    });
    
    return await response.json();
};

// Search items
const searchItems = async (query, vendorId = null) => {
    const response = await fetch('/api/mart/search-items', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            query,
            vendor_id: vendorId
        })
    });
    
    return await response.json();
};
```

### Flutter Example

```dart
// Get user profile
Future<Map<String, dynamic>> getUserProfile() async {
  final response = await http.get(
    Uri.parse('$baseUrl/api/mart/user-profile'),
    headers: {
      'Authorization': 'Bearer $token',
      'Content-Type': 'application/json',
    },
  );
  
  return json.decode(response.body);
}

// Get nearby vendors
Future<Map<String, dynamic>> getNearbyVendors(
  double latitude, 
  double longitude, 
  {double radius = 10}
) async {
  final response = await http.post(
    Uri.parse('$baseUrl/api/mart/nearby-vendors'),
    headers: {'Content-Type': 'application/json'},
    body: json.encode({
      'latitude': latitude,
      'longitude': longitude,
      'radius': radius,
    }),
  );
  
  return json.decode(response.body);
}

// Get vendor items
Future<Map<String, dynamic>> getVendorItems(
  String vendorId, 
  {String? categoryId, int page = 1}
) async {
  String url = '$baseUrl/api/mart/items?vendor_id=$vendorId&page=$page';
  if (categoryId != null) {
    url += '&category_id=$categoryId';
  }
  
  final response = await http.get(
    Uri.parse(url),
    headers: {'Content-Type': 'application/json'},
  );
  
  return json.decode(response.body);
}
```

## Data Models

### User Model
```json
{
    "id": "string",
    "active": "boolean",
    "appIdentifier": "string",
    "countryCode": "string",
    "createdAt": "timestamp",
    "email": "string",
    "firstName": "string",
    "lastName": "string",
    "isDocumentVerify": "boolean",
    "phoneNumber": "string",
    "profilePictureURL": "string",
    "provider": "string",
    "role": "string",
    "subscriptionExpiryDate": "timestamp|null",
    "subscriptionPlanId": "string|null",
    "subscription_plan": "object|null",
    "vType": "string",
    "vendorID": "string"
}
```

### Vendor Model
```json
{
    "id": "string",
    "adminCommission": {
        "commissionType": "string",
        "fix_commission": "number",
        "isEnabled": "boolean"
    },
    "author": "string",
    "authorName": "string",
    "authorProfilePic": "string|null",
    "categoryID": ["string"],
    "categoryTitle": ["string"],
    "coordinates": [number, number],
    "countryCode": "string",
    "createdAt": "timestamp",
    "description": "string",
    "enabledDelivery": "boolean",
    "filters": "object",
    "hidephotos": "boolean",
    "isOpen": "boolean",
    "latitude": "number",
    "location": "string",
    "longitude": "number",
    "phonenumber": "string",
    "photo": "string|null",
    "photos": ["string"],
    "restaurantCost": "number",
    "restaurantMenuPhotos": ["string"],
    "specialDiscount": ["object"],
    "specialDiscountEnable": "boolean",
    "subscriptionExpiryDate": "timestamp|null",
    "subscriptionPlanId": "string|null",
    "subscriptionTotalOrders": "number|null",
    "subscription_plan": "object|null",
    "title": "string",
    "vType": "string",
    "workingHours": ["object"],
    "zoneId": "string"
}
```

### Category Model
```json
{
    "id": "string",
    "description": "string",
    "photo": "string",
    "publish": "boolean",
    "review_attributes": ["object"],
    "show_in_homepage": "boolean",
    "title": "string"
}
```

### Item Model
```json
{
    "id": "string",
    "addOnsPrice": ["string"],
    "addOnsTitle": ["string"],
    "calories": "number",
    "categoryID": "string",
    "createdAt": "timestamp",
    "description": "string",
    "disPrice": "string",
    "fats": "number",
    "grams": "number",
    "isAvailable": "boolean",
    "item_attribute": "object|null",
    "name": "string",
    "nonveg": "boolean",
    "photo": "string",
    "photos": ["string"],
    "price": "string",
    "product_specification": "object",
    "proteins": "number",
    "publish": "boolean",
    "quantity": "number",
    "takeawayOption": "boolean",
    "veg": "boolean",
    "vendorID": "string"
}
```

## Best Practices

1. **Pagination**: Always use pagination for large datasets
2. **Caching**: Cache vendor and category data on the client side
3. **Error Handling**: Implement proper error handling for network failures
4. **Loading States**: Show loading indicators during API calls
5. **Offline Support**: Cache essential data for offline functionality
6. **Image Optimization**: Use appropriate image sizes for different screen densities

## Rate Limiting

- Public endpoints: 100 requests per minute per IP
- Authenticated endpoints: 1000 requests per minute per user
- Search endpoints: 50 requests per minute per IP

## Support

For technical support or questions about the API:
- Email: support@yourdomain.com
- Documentation: https://yourdomain.com/api/docs
- Status Page: https://status.yourdomain.com
