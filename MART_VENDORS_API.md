# Mart Vendors API Documentation

## Overview
The Mart Vendors API provides comprehensive access to mart vendor data including vendor details, location-based searches, working hours, special discounts, and more. All endpoints are designed with fallback logic to ensure functionality even when Firebase indexes are missing.

## Base URL
```
http://127.0.0.1:8000/api/mart/vendors
```

## Authentication
All endpoints are **public** and do not require authentication.

## Endpoints

### 1. Get All Mart Vendors
**GET** `/api/mart/vendors`

Get all mart vendors with filtering, pagination, and sorting.

#### Query Parameters
- `publish` (optional): Filter by publish status (true/false)
- `is_open` (optional): Filter by open status (true/false)
- `enabled_delivery` (optional): Filter by delivery availability (true/false)
- `category_id` (optional): Filter by category ID
- `zone_id` (optional): Filter by zone ID
- `search` (optional): Search in title, description, or location
- `page` (optional): Page number (default: 1)
- `limit` (optional): Items per page (default: 20, max: 100)
- `sort_by` (optional): Sort field (title, createdAt, restaurantCost)
- `sort_order` (optional): Sort order (asc, desc)

#### Example Request
```bash
GET /api/mart/vendors?publish=true&is_open=true&limit=10&sort_by=title&sort_order=asc
```

#### Example Response
```json
{
    "success": true,
    "data": [
        {
            "id": "4ir2OLhuMEc2yg9L1YxX",
            "title": "Jippy Mart",
            "description": "-",
            "vType": "mart",
            "publish": true,
            "isOpen": true,
            "enabledDelivery": false,
            "latitude": 15.486759,
            "longitude": 80.049118,
            "location": "7th Line Ram Nagar",
            "categoryID": ["68b16f87cac4e"],
            "categoryTitle": ["Groceries"],
            "zoneId": "BmSTwRFzmP13PnVNFJZJ"
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 20,
        "total": 1,
        "has_more": false,
        "filters_applied": {
            "vType": "mart",
            "publish": true
        },
        "sort_by": "title",
        "sort_order": "asc",
        "note": "Using fallback query due to missing Firebase index"
    }
}
```

### 2. Get Vendor Details
**GET** `/api/mart/vendors/{vendor_id}`

Get detailed information about a specific mart vendor.

#### Path Parameters
- `vendor_id` (required): The vendor's unique identifier

#### Example Request
```bash
GET /api/mart/vendors/4ir2OLhuMEc2yg9L1YxX
```

#### Example Response
```json
{
    "success": true,
    "data": {
        "id": "4ir2OLhuMEc2yg9L1YxX",
        "title": "Jippy Mart",
        "description": "-",
        "vType": "mart",
        "publish": true,
        "isOpen": true,
        "enabledDelivery": false,
        "latitude": 15.486759,
        "longitude": 80.049118,
        "location": "7th Line Ram Nagar",
        "phonenumber": "9390579864",
        "countryCode": "91",
        "coordinates": [15.486759, 80.049118],
        "categoryID": ["68b16f87cac4e"],
        "categoryTitle": ["Groceries"],
        "zoneId": "BmSTwRFzmP13PnVNFJZJ",
        "workingHours": [...],
        "specialDiscount": [...],
        "adminCommission": {...}
    }
}
```

### 3. Get Nearby Vendors
**POST** `/api/mart/vendors/nearby`

Find mart vendors within a specified radius of given coordinates.

#### Request Body
```json
{
    "latitude": 15.486759,
    "longitude": 80.049118,
    "radius": 10,
    "limit": 20,
    "category_id": "68b16f87cac4e",
    "enabled_delivery": true
}
```

#### Request Parameters
- `latitude` (required): Latitude coordinate (-90 to 90)
- `longitude` (required): Longitude coordinate (-180 to 180)
- `radius` (optional): Search radius in kilometers (default: 10, max: 50)
- `limit` (optional): Maximum number of results (default: 20, max: 100)
- `category_id` (optional): Filter by category ID
- `enabled_delivery` (optional): Filter by delivery availability

#### Example Response
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
            "distance": 0.0
        }
    ],
    "meta": {
        "latitude": 15.486759,
        "longitude": 80.049118,
        "radius_km": 10,
        "count": 1,
        "filters_applied": {
            "category_id": "68b16f87cac4e",
            "enabled_delivery": true
        }
    }
}
```

### 4. Get Vendors by Category
**POST** `/api/mart/vendors/by-category`

Get all mart vendors that belong to a specific category.

#### Request Body
```json
{
    "category_id": "68b16f87cac4e",
    "page": 1,
    "limit": 20,
    "sort_by": "title",
    "sort_order": "asc"
}
```

#### Request Parameters
- `category_id` (required): The category ID to filter by
- `page` (optional): Page number (default: 1)
- `limit` (optional): Items per page (default: 20, max: 100)
- `sort_by` (optional): Sort field (title, createdAt, restaurantCost)
- `sort_order` (optional): Sort order (asc, desc)

#### Example Response
```json
{
    "success": true,
    "data": [
        {
            "id": "4ir2OLhuMEc2yg9L1YxX",
            "title": "Jippy Mart",
            "categoryID": ["68b16f87cac4e"],
            "categoryTitle": ["Groceries"]
        }
    ],
    "meta": {
        "category_id": "68b16f87cac4e",
        "current_page": 1,
        "per_page": 20,
        "total": 1,
        "has_more": false,
        "sort_by": "title",
        "sort_order": "asc",
        "note": "Using fallback query due to missing Firebase index"
    }
}
```

### 5. Get Vendor Working Hours
**POST** `/api/mart/vendors/working-hours`

Get the working hours and operational status of a specific vendor.

#### Request Body
```json
{
    "vendor_id": "4ir2OLhuMEc2yg9L1YxX"
}
```

#### Request Parameters
- `vendor_id` (required): The vendor's unique identifier

#### Example Response
```json
{
    "success": true,
    "data": {
        "vendor_id": "4ir2OLhuMEc2yg9L1YxX",
        "vendor_title": "Jippy Mart",
        "is_open": true,
        "open_dine_time": null,
        "close_dine_time": null,
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

### 6. Get Vendor Special Discounts
**POST** `/api/mart/vendors/special-discounts`

Get special discounts and promotional offers from a specific vendor.

#### Request Body
```json
{
    "vendor_id": "4ir2OLhuMEc2yg9L1YxX"
}
```

#### Request Parameters
- `vendor_id` (required): The vendor's unique identifier

#### Example Response
```json
{
    "success": true,
    "data": {
        "vendor_id": "4ir2OLhuMEc2yg9L1YxX",
        "vendor_title": "Jippy Mart",
        "special_discount_enable": false,
        "special_discounts": [
            {
                "day": "Monday",
                "timeslot": []
            },
            {
                "day": "Tuesday",
                "timeslot": []
            }
        ]
    }
}
```

### 7. Search Vendors
**POST** `/api/mart/vendors/search`

Search mart vendors by name, description, or location with additional filters.

#### Request Body
```json
{
    "query": "Jippy",
    "publish": true,
    "is_open": true,
    "enabled_delivery": false,
    "category_id": "68b16f87cac4e",
    "page": 1,
    "limit": 20
}
```

#### Request Parameters
- `query` (required): Search term (min: 2, max: 100 characters)
- `publish` (optional): Filter by publish status
- `is_open` (optional): Filter by open status
- `enabled_delivery` (optional): Filter by delivery availability
- `category_id` (optional): Filter by category ID
- `page` (optional): Page number (default: 1)
- `limit` (optional): Items per page (default: 20, max: 50)

#### Example Response
```json
{
    "success": true,
    "data": [
        {
            "id": "4ir2OLhuMEc2yg9L1YxX",
            "title": "Jippy Mart",
            "description": "-",
            "location": "7th Line Ram Nagar"
        }
    ],
    "meta": {
        "query": "Jippy",
        "current_page": 1,
        "per_page": 20,
        "total": 1,
        "has_more": false,
        "note": "Using fallback query due to missing Firebase index"
    }
}
```

## Data Structure

### Vendor Object
```json
{
    "id": "string",
    "title": "string",
    "description": "string",
    "vType": "mart",
    "publish": "boolean",
    "isOpen": "boolean",
    "enabledDelivery": "boolean",
    "latitude": "number",
    "longitude": "number",
    "location": "string",
    "phonenumber": "string",
    "countryCode": "string",
    "coordinates": "geopoint",
    "categoryID": ["string"],
    "categoryTitle": ["string"],
    "zoneId": "string",
    "workingHours": "array",
    "specialDiscount": "array",
    "adminCommission": "object",
    "filters": "object",
    "createdAt": "timestamp",
    "updatedAt": "timestamp"
}
```

### Working Hours Structure
```json
{
    "day": "string",
    "timeslot": [
        {
            "from": "string",
            "to": "string"
        }
    ]
}
```

### Special Discount Structure
```json
{
    "day": "string",
    "timeslot": "array"
}
```

### Admin Commission Structure
```json
{
    "commissionType": "string",
    "fix_commission": "number",
    "isEnabled": "boolean"
}
```

## Error Responses

### Validation Error (422)
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "category_id": ["The category id field is required."]
    }
}
```

### Not Found Error (404)
```json
{
    "success": false,
    "message": "Vendor not found"
}
```

### Bad Request Error (400)
```json
{
    "success": false,
    "message": "Vendor is not a mart vendor"
}
```

### Server Error (500)
```json
{
    "success": false,
    "message": "Failed to get mart vendors: [error details]"
}
```

## Fallback Logic

All endpoints that require Firebase composite indexes include fallback logic:

1. **Primary Query**: Attempts to use Firebase with filters (requires index)
2. **Fallback Detection**: If no results, tries broader query without filters
3. **PHP-side Filtering**: Applies filters, sorting, and pagination in PHP
4. **Fallback Note**: Response includes note when fallback is used

## Firebase Index Requirements

### Required Indexes for Optimal Performance
- **Mart Vendors Index**: `vType` + `publish` + `title`
- **Vendors by Category Index**: `vType` + `categoryID` + `publish`
- **Vendors by Zone Index**: `vType` + `zoneId` + `publish`
- **Vendors by Status Index**: `vType` + `isOpen` + `enabledDelivery`

## Testing Examples

### PowerShell/Windows
```powershell
# Get all vendors
Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/mart/vendors" -Method GET -Headers @{"Accept"="application/json"}

# Search vendors
Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/mart/vendors/search" -Method POST -Headers @{"Accept"="application/json"; "Content-Type"="application/json"} -Body '{"query": "Jippy", "publish": true}'

# Get nearby vendors
Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/mart/vendors/nearby" -Method POST -Headers @{"Accept"="application/json"; "Content-Type"="application/json"} -Body '{"latitude": 15.486759, "longitude": 80.049118, "radius": 10}'
```

### cURL
```bash
# Get all vendors
curl -X GET "http://127.0.0.1:8000/api/mart/vendors" \
  -H "Accept: application/json"

# Search vendors
curl -X POST "http://127.0.0.1:8000/api/mart/vendors/search" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"query": "Jippy", "publish": true}'

# Get nearby vendors
curl -X POST "http://127.0.0.1:8000/api/mart/vendors/nearby" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"latitude": 15.486759, "longitude": 80.049118, "radius": 10}'
```

## Notes

- All endpoints automatically filter for `vType: "mart"` to ensure only mart vendors are returned
- Fallback logic ensures functionality even when Firebase indexes are missing
- Geographic searches use Firebase's built-in geospatial queries
- All endpoints include comprehensive error handling and validation
- Response metadata includes information about filters applied and fallback usage
