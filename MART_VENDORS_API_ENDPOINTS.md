# Mart Vendors API Endpoints

## Overview
This document provides comprehensive information about the Mart Vendors API endpoints for retrieving vendor information in your grocery/mart application.

## Available Endpoints

### 1. Get All Mart Vendors
**GET** `/api/mart/vendors`

Retrieve all mart vendors with optional filtering and pagination.

#### Features
- ✅ **Filtering**: Filter by open status, delivery availability, and category
- ✅ **Search**: Search vendors by title
- ✅ **Pagination**: Page-based pagination with configurable limits
- ✅ **Caching**: GET method allows for browser and CDN caching
- ✅ **No Authentication**: Public endpoint, no authentication required

#### Query Parameters

| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| `is_open` | boolean | No | - | Filter by open status (true/false) |
| `enabled_delivery` | boolean | No | - | Filter by delivery availability (true/false) |
| `category_id` | string | No | - | Filter by category ID |
| `search` | string | No | - | Search vendors by title (min: 2 chars) |
| `page` | integer | No | 1 | Page number for pagination |
| `limit` | integer | No | 20 | Number of vendors per page (max: 100) |

#### Example Requests

**Basic Request:**
```bash
GET /api/mart/vendors
```

**With Filters:**
```bash
GET /api/mart/vendors?is_open=true&enabled_delivery=true&page=1&limit=10
```

**With Search:**
```bash
GET /api/mart/vendors?search=Jippy&page=1&limit=20
```

**With Category Filter:**
```bash
GET /api/mart/vendors?category_id=68a46e8810c9d&is_open=true
```

#### Response Format

```json
{
    "success": true,
    "data": [
        {
            "id": "4ir2OLhuMEc2yg9L1YxX",
            "title": "Jippy Mart",
            "description": "Fresh groceries and household items",
            "location": "7th Line Ram Nagar",
            "phonenumber": "9390579864",
            "latitude": 15.486759,
            "longitude": 80.049118,
            "coordinates": [15.486759, 80.049118],
            "isOpen": true,
            "enabledDelivery": true,
            "vType": "mart",
            "categoryID": ["68a46e8810c9d"],
            "categoryTitle": ["Groceries"],
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
            "specialDiscount": [],
            "adminCommission": {
                "commissionType": "Percent",
                "fix_commission": 0,
                "isEnabled": true
            },
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

### 2. Get Vendor Details by ID
**GET** `/api/mart/vendor-details/{vendor_id}`

Retrieve detailed information about a specific mart vendor.

#### Features
- ✅ **Specific Vendor**: Get complete vendor information by ID
- ✅ **Caching**: GET method allows for browser and CDN caching
- ✅ **No Authentication**: Public endpoint, no authentication required
- ✅ **Error Handling**: Proper 404 responses for invalid vendor IDs

#### URL Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `vendor_id` | string | Yes | The unique identifier of the vendor |

#### Example Request

```bash
GET /api/mart/vendor-details/4ir2OLhuMEc2yg9L1YxX
```

#### Response Format

```json
{
    "success": true,
    "data": {
        "id": "4ir2OLhuMEc2yg9L1YxX",
        "title": "Jippy Mart",
        "description": "Fresh groceries and household items",
        "location": "7th Line Ram Nagar",
        "phonenumber": "9390579864",
        "latitude": 15.486759,
        "longitude": 80.049118,
        "coordinates": [15.486759, 80.049118],
        "isOpen": true,
        "enabledDelivery": true,
        "vType": "mart",
        "categoryID": ["68a46e8810c9d"],
        "categoryTitle": ["Groceries"],
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
        "specialDiscount": [],
        "adminCommission": {
            "commissionType": "Percent",
            "fix_commission": 0,
            "isEnabled": true
        },
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
        "photos": [],
        "createdAt": "2025-08-21T06:46:37.000Z"
    }
}
```

## Vendor Data Structure

### Core Vendor Fields

| Field | Type | Description |
|-------|------|-------------|
| `id` | string | Unique vendor identifier |
| `title` | string | Vendor name/title |
| `description` | string | Vendor description |
| `location` | string | Physical address |
| `phonenumber` | string | Contact phone number |
| `latitude` | number | GPS latitude coordinate |
| `longitude` | number | GPS longitude coordinate |
| `coordinates` | array | [latitude, longitude] array |
| `isOpen` | boolean | Whether vendor is currently open |
| `enabledDelivery` | boolean | Whether delivery is enabled |
| `vType` | string | Vendor type (always "mart") |

### Business Information

| Field | Type | Description |
|-------|------|-------------|
| `categoryID` | array | Array of category IDs |
| `categoryTitle` | array | Array of category names |
| `workingHours` | array | Working hours for each day |
| `specialDiscount` | array | Special discount information |
| `adminCommission` | object | Commission structure |
| `filters` | object | Vendor-specific filters |

### Media and Metadata

| Field | Type | Description |
|-------|------|-------------|
| `photos` | array | Array of vendor photos |
| `createdAt` | string | ISO timestamp of creation |

## Usage Examples

### Mobile App Integration (React Native)

```javascript
// Get all mart vendors
const getAllMartVendors = async (filters = {}) => {
    try {
        const queryParams = new URLSearchParams(filters);
        const response = await fetch(`/api/mart/vendors?${queryParams}`);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching vendors:', error);
        throw error;
    }
};

// Get specific vendor details
const getVendorDetails = async (vendorId) => {
    try {
        const response = await fetch(`/api/mart/vendors/${vendorId}`);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching vendor details:', error);
        throw error;
    }
};

// Usage examples
const openVendors = await getAllMartVendors({ is_open: true });
const vendorDetails = await getVendorDetails('4ir2OLhuMEc2yg9L1YxX');
```

### Web Application Integration

```javascript
// Get all mart vendors with filters
const fetchVendors = async (filters = {}) => {
    const params = new URLSearchParams();
    
    if (filters.isOpen !== undefined) params.append('is_open', filters.isOpen);
    if (filters.enabledDelivery !== undefined) params.append('enabled_delivery', filters.enabledDelivery);
    if (filters.categoryId) params.append('category_id', filters.categoryId);
    if (filters.search) params.append('search', filters.search);
    if (filters.page) params.append('page', filters.page);
    if (filters.limit) params.append('limit', filters.limit);
    
    const response = await fetch(`/api/mart/vendors?${params}`);
    return await response.json();
};

// Get vendor details
const fetchVendorDetails = async (vendorId) => {
    const response = await fetch(`/api/mart/vendor-details/${vendorId}`);
    return await response.json();
};
```

### cURL Examples

```bash
# Get all open mart vendors
curl -X GET "http://your-domain.com/api/mart/vendors?is_open=true"

# Get vendors with delivery enabled
curl -X GET "http://your-domain.com/api/mart/vendors?enabled_delivery=true&page=1&limit=10"

# Search for vendors
curl -X GET "http://your-domain.com/api/mart/vendors?search=Jippy"

# Get specific vendor details
curl -X GET "http://your-domain.com/api/mart/vendor-details/4ir2OLhuMEc2yg9L1YxX"
```

## Error Handling

### Common Error Responses

**400 Bad Request:**
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "limit": ["The limit must be between 1 and 100."]
    }
}
```

**404 Not Found:**
```json
{
    "success": false,
    "message": "Vendor not found"
}
```

**500 Internal Server Error:**
```json
{
    "success": false,
    "message": "Failed to get mart vendors: Database connection error"
}
```

## Performance Considerations

### Caching Strategy
- **Browser Caching**: GET requests are cacheable by default
- **CDN Caching**: Can be cached at CDN level for better performance
- **Cache Headers**: Consider setting appropriate cache headers

### Pagination Best Practices
- **Default Limit**: 20 vendors per page
- **Maximum Limit**: 100 vendors per page
- **Page Numbers**: Start from 1
- **Metadata**: Includes total count and has_more flag

### Filtering Performance
- **Indexed Queries**: Uses Firestore indexes for efficient filtering
- **Combined Filters**: Multiple filters work together efficiently
- **Search Optimization**: Text search uses Firestore's built-in search

## Security Features

### Input Validation
- **Parameter Validation**: All query parameters are validated
- **Type Checking**: Ensures correct data types
- **Range Validation**: Limits are enforced (e.g., max 100 per page)

### Data Access Control
- **Public Access**: No authentication required for vendor data
- **Read-Only**: These endpoints are read-only
- **No Sensitive Data**: Only public vendor information is exposed

## Integration with Existing System

### Firebase Firestore Integration
- **Collection**: Uses `vendors` collection
- **Filtering**: Automatically filters by `vType: "mart"`
- **Indexes**: Requires proper Firestore indexes for efficient queries

### Laravel Integration
- **Controller**: `MartController@getAllMartVendors`
- **Service**: `FirebaseService@getAllMartVendors`
- **Validation**: Laravel validation rules applied

## Testing

### Postman Collection
The endpoints are included in the Postman collection with:
- Example requests
- Sample responses
- Environment variables
- Test cases

### Unit Testing
```php
// Example test for getAllMartVendors
public function test_get_all_mart_vendors()
{
    $response = $this->get('/api/mart/vendors?is_open=true');
    
    $response->assertStatus(200)
             ->assertJsonStructure([
                 'success',
                 'data' => [
                     '*' => [
                         'id',
                         'title',
                         'vType',
                         'isOpen'
                     ]
                 ],
                 'meta'
             ]);
}
```

## Conclusion

The Mart Vendors API provides comprehensive access to vendor information with:

1. **Flexible Filtering**: Multiple filter options for precise queries
2. **Efficient Pagination**: Page-based pagination with metadata
3. **Search Capability**: Text search by vendor title
4. **RESTful Design**: Follows REST principles with GET methods
5. **Performance Optimized**: Caching-friendly and efficient queries
6. **Comprehensive Documentation**: Complete API documentation and examples

These endpoints are ready for production use and can be easily integrated into mobile and web applications.
