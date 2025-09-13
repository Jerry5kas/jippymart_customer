# JippyMart Search API

**Base URL:** `https://jippymart.in/api/search`

## Endpoints

### 1. Search Categories
```
GET /api/search/categories
```
**Parameters:**
- `q` - Search term (optional)
- `page` - Page number (default: 1)
- `limit` - Items per page (default: 20)

**Example:**
```bash
GET /api/search/categories?q=groceries&limit=10
```

### 2. Get Published Categories
```
GET /api/search/categories/published
```
**Parameters:**
- `limit` - Max categories (default: 50)

### 3. Search Mart Items
```
GET /api/search/items
```
**Parameters:**
- `search` - Search term
- `category` - Category filter
- `subcategory` - Subcategory filter
- `vendor` - Vendor filter
- `min_price` - Minimum price
- `max_price` - Maximum price
- `veg` - Vegetarian filter (true/false)
- `isAvailable` - Available items (true/false)
- `isBestSeller` - Best sellers (true/false)
- `isFeature` - Featured items (true/false)
- `page` - Page number (default: 1)
- `limit` - Items per page (default: 20)

**Example:**
```bash
GET /api/search/items?search=juice&category=beverages&veg=true
```

### 4. Get Featured Items
```
GET /api/search/items/featured
```
**Parameters:**
- `type` - Type: `best_seller`, `trending`, `featured`, `new`, `spotlight` (default: featured)
- `limit` - Items per page (default: 20)

### 5. Health Check
```
GET /api/search/health
```

## Response Format

**Success:**
```json
{
  "success": true,
  "message": "Success message",
  "data": [...],
  "pagination": {
    "current_page": 1,
    "per_page": 20,
    "total": 100,
    "has_more": true
  }
}
```

**Error:**
```json
{
  "success": false,
  "message": "Error message"
}
```

## Rate Limits
- 60 requests/minute per IP
- Health check: No limit

## Examples

**JavaScript:**
```javascript
// Search categories
fetch('/api/search/categories?q=groceries')
  .then(response => response.json())
  .then(data => console.log(data));

// Search items
fetch('/api/search/items?search=juice&veg=true')
  .then(response => response.json())
  .then(data => console.log(data));
```

**cURL:**
```bash
curl "https://jippymart.in/api/search/categories?q=groceries"
curl "https://jippymart.in/api/search/items?search=juice&veg=true"
```
