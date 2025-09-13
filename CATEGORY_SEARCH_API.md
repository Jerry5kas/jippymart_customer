# Category Search API Documentation

## Overview
This API provides optimized search functionality for mart categories stored in Firestore. It's designed for fast response times and lightweight payloads.

## Base URL
```
/api/search
```

## Endpoints

### 1. Search Categories
Search categories by title and description with pagination support.

**Endpoint:** `GET /api/search/categories`

**Parameters:**
- `q` (optional, string, max 100 chars): Search term to match against title and description
- `page` (optional, integer, 1-100): Page number for pagination (default: 1)
- `limit` (optional, integer, 1-50): Number of results per page (default: 20)

**Example Requests:**
```bash
# Search for "grocery"
GET /api/search/categories?q=grocery

# Search with pagination
GET /api/search/categories?q=food&page=2&limit=10

# Get all categories (no search term)
GET /api/search/categories?page=1&limit=20
```

**Response Format:**
```json
{
  "success": true,
  "message": "Categories retrieved successfully",
  "data": [
    {
      "id": "68b16f87cac4e",
      "title": "Groceries",
      "description": "Fresh groceries and daily essentials",
      "photo": "https://firebasestorage.googleapis.com/v0/b/...",
      "section": "Grocery & Kitchen",
      "category_order": 2,
      "section_order": 2,
      "show_in_homepage": true
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 20,
    "total": 25,
    "has_more": true
  },
  "search_term": "grocery"
}
```

### 2. Get Published Categories
Get all published categories that should be shown on the homepage.

**Endpoint:** `GET /api/search/categories/published`

**Parameters:**
- `limit` (optional, integer, 1-100): Maximum number of categories to return (default: 50)

**Example Request:**
```bash
GET /api/search/categories/published?limit=30
```

**Response Format:**
```json
{
  "success": true,
  "message": "Published categories retrieved successfully",
  "data": [
    {
      "id": "68b16f87cac4e",
      "title": "Groceries",
      "description": "Fresh groceries and daily essentials",
      "photo": "https://firebasestorage.googleapis.com/v0/b/...",
      "section": "Grocery & Kitchen",
      "category_order": 2,
      "section_order": 2
    }
  ],
  "count": 15
}
```

## Error Responses

### Validation Error (422)
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "limit": ["The limit may not be greater than 50."],
    "page": ["The page must be at least 1."]
  }
}
```

### Server Error (500)
```json
{
  "success": false,
  "message": "An error occurred while searching categories",
  "data": []
}
```

## Firestore Collection Structure

**Collection:** `mart_categories`

**Required Fields:**
- `publish` (boolean): Must be `true` for categories to appear in search results
- `title` (string): Category title
- `description` (string): Category description
- `photo` (string): URL to category image
- `section` (string): Section name (e.g., "Grocery & Kitchen")
- `category_order` (number): Order within section
- `section_order` (number): Order of sections
- `show_in_homepage` (boolean): Whether to show on homepage

**Example Document:**
```json
{
  "category_order": 2,
  "description": "Groceries",
  "id": "68b16f87cac4e",
  "photo": "https://firebasestorage.googleapis.com/v0/b/jippymart-27c08.firebasestorage.app/o/images%2Fgroc_1756460123245.jpg?alt=media&token=68038522-7865-40dd-bf5f-0f8bc64db7c1",
  "publish": true,
  "review_attributes": [],
  "section": "Grocery & Kitchen",
  "section_order": 2,
  "show_in_homepage": true,
  "title": "Groceries"
}
```

## Performance Optimizations

### 1. Lightweight Payload
- Only returns essential fields to minimize response size
- Excludes heavy fields like `review_attributes` from search results

### 2. Efficient Queries
- Uses Firestore's native filtering for `publish: true`
- Implements proper pagination to limit data transfer
- Sorts results by `section_order` and `category_order` for consistent ordering
- **Avoids Firestore index requirements** by sorting in PHP instead of using `orderBy` in queries

### 3. Search Implementation
- Case-insensitive search in PHP for better performance
- Searches both `title` and `description` fields
- Uses `strpos()` for fast substring matching
- **Note**: Search is case-insensitive but requires exact word matches (e.g., "grocery" won't match "Groceries")

### 4. Caching Strategy
- Results are sorted consistently for potential caching
- Pagination allows for efficient data loading

### 5. Firestore Index Optimization
- Avoids composite index requirements by fetching all published categories first
- Filters and sorts in PHP to prevent "index required" errors
- More efficient for small to medium datasets

## Usage Examples

### JavaScript/Fetch API
```javascript
// Search categories
async function searchCategories(query, page = 1, limit = 20) {
  try {
    const response = await fetch(`/api/search/categories?q=${encodeURIComponent(query)}&page=${page}&limit=${limit}`);
    const data = await response.json();
    
    if (data.success) {
      console.log('Categories:', data.data);
      console.log('Pagination:', data.pagination);
      return data;
    } else {
      console.error('Error:', data.message);
      return null;
    }
  } catch (error) {
    console.error('Network error:', error);
    return null;
  }
}

// Get published categories for homepage
async function getPublishedCategories(limit = 50) {
  try {
    const response = await fetch(`/api/search/categories/published?limit=${limit}`);
    const data = await response.json();
    
    if (data.success) {
      return data.data;
    }
    return [];
  } catch (error) {
    console.error('Error fetching published categories:', error);
    return [];
  }
}
```

### cURL Examples
```bash
# Search for "grocery"
curl -X GET "http://localhost:8000/api/search/categories?q=grocery" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json"

# Get published categories
curl -X GET "http://localhost:8000/api/search/categories/published?limit=30" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json"
```

### PHP/Laravel
```php
// In a controller or service
use App\Services\FirebaseService;

public function searchCategories($searchTerm = '', $page = 1, $limit = 20)
{
    $firebaseService = app(FirebaseService::class);
    $offset = ($page - 1) * $limit;
    
    return $firebaseService->searchCategories($searchTerm, $limit, $offset);
}
```

## Best Practices

1. **Pagination**: Always use pagination for large result sets
2. **Search Terms**: Keep search terms reasonable (max 100 characters)
3. **Limits**: Use appropriate limits (1-50 for search, 1-100 for published)
4. **Error Handling**: Always check the `success` field in responses
5. **Caching**: Consider caching results for frequently accessed data

## Rate Limiting
Currently no rate limiting is implemented, but consider adding it for production use.

## Security
- No authentication required for these endpoints
- Input validation prevents injection attacks
- Error messages don't expose sensitive information
