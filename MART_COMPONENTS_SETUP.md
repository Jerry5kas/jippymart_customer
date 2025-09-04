# Mart Components Setup Guide

## Overview
This document describes the setup and configuration for the new dynamic mart components that integrate with Firebase Firestore collections.

## Components Updated

### 1. top-cat-items.blade.php
- **Purpose**: Dynamic category tabs and subcategory display
- **Features**: 
  - Dynamic tabs based on `mart_categories` collection
  - Filtered subcategories based on selected category
  - Smooth scrolling and enhanced UX
  - Loading states and error handling

### 2. top-cat-card.blade.php
- **Purpose**: Individual subcategory display card
- **Features**:
  - Dynamic image and title from props
  - Hover effects and transitions
  - Image error handling with fallback
  - Loading states

### 3. MartController.php
- **Purpose**: Backend controller for mart data
- **Features**:
  - Firebase Firestore integration
  - Caching for performance
  - Error handling and logging
  - Filtered queries for published and homepage items

## Firebase Configuration

### Required Environment Variables
Add these to your `.env` file:

```env
FIREBASE_PROJECT_ID=jippymart-27c08
FIREBASE_API_KEY=your_firebase_api_key_here
FIREBASE_URL=https://firestore.googleapis.com/v1/projects/jippymart-27c08/databases/(default)/documents
```

### Firebase Credentials
Ensure you have the Firebase service account credentials file at:
```
storage/app/firebase/credentials.json
```

## Data Structure Requirements

### mart_categories Collection
Documents must have these fields:
- `publish`: boolean (true for published categories)
- `show_in_homepage`: boolean (true to show on homepage)
- `category_order`: number (for sorting)
- `title`: string (category name)
- `photo`: string (category image URL)
- `description`: string (category description)

### mart_subcategories Collection
Documents must have these fields:
- `publish`: boolean (true for published subcategories)
- `show_in_homepage`: boolean (true to show on homepage)
- `subcategory_order`: number (for sorting)
- `parent_category_id`: string (reference to parent category)
- `title`: string (subcategory name)
- `photo`: string (subcategory image URL)
- `description`: string (subcategory description)

## Usage

### In Views
```php
<x-mart.top-cat-items :categories="$categories" :subcategories="$subcategories"/>
```

### In Controllers
```php
use App\Http\Controllers\MartController;

class YourController extends Controller
{
    public function index()
    {
        $martController = new MartController();
        $categories = $martController->getHomepageCategories();
        $subcategories = $martController->getAllHomepageSubcategories();
        
        return view('your.view', compact('categories', 'subcategories'));
    }
}
```

## Features

### Dynamic Filtering
- Categories are automatically filtered by `publish = true` and `show_in_homepage = true`
- Subcategories are filtered by the same criteria plus `parent_category_id` matching
- All data is sorted by their respective order fields

### Caching
- Categories are cached for 5 minutes (300 seconds)
- Subcategories are cached per category for 5 minutes
- Cache keys are automatically managed

### Error Handling
- Graceful fallbacks when Firebase is unavailable
- Comprehensive logging for debugging
- Empty state handling in the UI

### UX Enhancements
- Smooth scrolling between categories
- Loading states and skeleton screens
- Hover effects and transitions
- Responsive design
- Image error handling with fallbacks

## Troubleshooting

### Common Issues

1. **Firebase Connection Failed**
   - Check if `storage/app/firebase/credentials.json` exists
   - Verify `FIREBASE_PROJECT_ID` in `.env`
   - Check Firebase project permissions

2. **No Data Displayed**
   - Verify documents have `publish: true` and `show_in_homepage: true`
   - Check browser console for JavaScript errors
   - Verify data structure matches requirements

3. **Images Not Loading**
   - Check image URLs in Firebase documents
   - Verify image accessibility (CORS, authentication)
   - Check fallback image URL

### Debugging
- Check Laravel logs for Firebase errors
- Use browser developer tools to inspect network requests
- Verify Alpine.js data binding in browser console

## Performance Considerations

- Data is cached for 5 minutes to reduce Firebase calls
- Images are loaded asynchronously with fallbacks
- Smooth scrolling is optimized for performance
- Component re-renders are minimized

## Future Enhancements

- Real-time updates using Firebase listeners
- Lazy loading for large datasets
- Advanced filtering and search
- Analytics tracking for user interactions
- A/B testing for different layouts


