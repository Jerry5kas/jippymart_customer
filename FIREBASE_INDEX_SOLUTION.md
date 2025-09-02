# Firebase Index Solution for Mart API

## Overview
This document outlines the Firebase composite indexes required for optimal performance of the Mart API endpoints. Without these indexes, queries will fail with `FAILED_PRECONDITION` errors and may cause timeouts.

## Current Status
- **Total Endpoints Fixed**: 28+
- **Global Search Endpoint**: ✅ Implemented with timeout protection
- **Firebase Indexes Required**: ⚠️ Missing (causing query failures)

## Required Firebase Indexes

### 1. Mart Items Indexes

#### Basic Search Index
```
Collection: mart_items
Fields: publish + name + __name__
Order: publish (Ascending), name (Ascending), __name__ (Ascending)
```

#### Advanced Search Index
```
Collection: mart_items
Fields: publish + isAvailable + name + __name__
Order: publish (Ascending), isAvailable (Ascending), name (Ascending), __name__ (Ascending)
```

#### Category Filtered Search
```
Collection: mart_items
Fields: publish + categoryID + name + __name__
Order: publish (Ascending), categoryID (Ascending), name (Ascending), __name__ (Ascending)
```

#### Vendor Filtered Search
```
Collection: mart_items
Fields: publish + vendorID + name + __name__
Order: publish (Ascending), vendorID (Ascending), name (Ascending), __name__ (Ascending)
```

### 2. Mart Categories Indexes

#### Basic Search Index
```
Collection: mart_categories
Fields: publish + title + __name__
Order: publish (Ascending), title (Ascending), __name__ (Ascending)
```

#### Homepage Filter Index
```
Collection: mart_categories
Fields: publish + show_in_homepage + title + __name__
Order: publish (Ascending), show_in_homepage (Ascending), title (Ascending), __name__ (Ascending)
```

### 3. Mart Subcategories Indexes

#### Basic Search Index
```
Collection: mart_subcategories
Fields: publish + title + __name__
Order: publish (Ascending), title (Ascending), __name__ (Ascending)
```

#### Homepage Filter Index
```
Collection: mart_subcategories
Fields: publish + show_in_homepage + title + __name__
Order: publish (Ascending), show_in_homepage (Ascending), title (Ascending), __name__ (Ascending)
```

#### Category Filtered Index
```
Collection: mart_subcategories
Fields: publish + categoryID + title + __name__
Order: publish (Ascending), categoryID (Ascending), title (Ascending), __name__ (Ascending)
```

### 4. Mart Vendors Indexes

#### Basic Search Index
```
Collection: vendors
Fields: vType + publish + title + __name__
Order: vType (Ascending), publish (Ascending), title (Ascending), __name__ (Ascending)
```

#### Category Filtered Index
```
Collection: vendors
Fields: vType + categoryID + publish + __name__
Order: vType (Ascending), categoryID (Ascending), publish (Ascending), __name__ (Ascending)
```

## Global Search Endpoint Specific Indexes

The new `/api/mart/all-search` endpoint requires these specific indexes for optimal performance:

### Items Search Index
```
Collection: mart_items
Fields: publish + isAvailable + name + __name__
Order: publish (Ascending), isAvailable (Ascending), name (Ascending), __name__ (Ascending)
```

### Categories Search Index
```
Collection: mart_categories
Fields: publish + show_in_homepage + title + __name__
Order: publish (Ascending), show_in_homepage (Ascending), title (Ascending), __name__ (Ascending)
```

### Subcategories Search Index
```
Collection: mart_subcategories
Fields: publish + show_in_homepage + title + __name__
Order: publish (Ascending), show_in_homepage (Ascending), title (Ascending), __name__ (Ascending)
```

### Vendors Search Index
```
Collection: vendors
Fields: vType + publish + title + __name__
Order: vType (Ascending), publish (Ascending), title (Ascending), __name__ (Ascending)
```

## Timeout Protection Implementation

The Global Search endpoint now includes timeout protection to prevent `Maximum execution time exceeded` errors:

- **Items Search**: 15-second timeout
- **Categories Search**: 10-second timeout  
- **Subcategories Search**: 10-second timeout
- **Vendors Search**: 10-second timeout

Each search operation is executed with a custom timeout, and if it fails, the system gracefully falls back to empty results with informative error messages.

## Creating Indexes

### Method 1: Direct Links (Recommended)
Click the direct links provided in error messages to create indexes automatically.

### Method 2: Manual Creation
1. Go to [Firebase Console](https://console.firebase.google.com)
2. Select your project: `jippymart-27c08`
3. Navigate to Firestore Database → Indexes
4. Click "Create Index"
5. Add the required fields in the specified order

### Method 3: Firebase CLI
```bash
firebase deploy --only firestore:indexes
```

## Index Creation Time
- **Simple indexes**: 1-2 minutes
- **Composite indexes**: 2-10 minutes
- **Large collections**: May take longer

## Monitoring Index Status
1. Check Firebase Console → Firestore → Indexes
2. Look for "Building" status
3. Wait for "Enabled" status before testing

## Testing After Index Creation
1. Wait for indexes to finish building
2. Test the Global Search endpoint: `POST /api/mart/all-search`
3. Verify no more `FAILED_PRECONDITION` errors
4. Check response times are under 5 seconds

## Fallback Behavior
When indexes are missing:
- ✅ API returns `200 OK` status
- ✅ Empty results with informative notes
- ✅ No timeouts or crashes
- ⚠️ Performance degraded (fallback queries)
- 📝 Clear error messages about missing indexes

## Performance Expectations
- **With indexes**: < 2 seconds response time
- **Without indexes**: 10-15 seconds (timeout protected)
- **Fallback mode**: < 1 second (empty results)

## Next Steps
1. Create the required Firebase indexes
2. Test the Global Search endpoint
3. Monitor performance improvements
4. Update other endpoints if needed

## Support
If you encounter issues:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify Firebase index status
3. Test with smaller datasets first
4. Contact development team for assistance
