# Enhanced Mart Components Documentation

## Overview
The mart components have been enhanced to integrate with Firebase Firestore collections (`mart_categories` and `mart_subcategories`) while maintaining a minimalistic and user-friendly design. The components now provide dynamic data loading, enhanced UX, and fallback demo data.

## Components Updated

### 1. top-cat-items.blade.php
**Purpose**: Dynamic category tabs and subcategory display with filtering

**Features**:
- Dynamic tabs based on `mart_categories` collection
- Real-time filtering of subcategories based on selected category
- Smooth scrolling and enhanced UX
- Loading states and error handling
- Fallback demo data when Firebase is unavailable

**Props**:
```php
<x-mart.top-cat-items :categories="$categories" :subcategories="$subcategories"/>
```

**Data Structure Expected**:
```php
// Categories should have:
[
    'id' => 'string',
    'title' => 'string',
    'description' => 'string',
    'photo' => 'string',
    'publish' => boolean,
    'show_in_homepage' => boolean,
    'category_order' => number,
    'section' => 'string',
    'section_order' => number
]

// Subcategories should have:
[
    'id' => 'string',
    'title' => 'string',
    'description' => 'string',
    'photo' => 'string',
    'publish' => boolean,
    'show_in_homepage' => boolean,
    'parent_category_id' => 'string',
    'parent_category_title' => 'string',
    'subcategory_order' => number,
    'category_order' => number,
    'section' => 'string',
    'section_order' => number
]
```

### 2. top-cat-card.blade.php
**Purpose**: Individual subcategory display card with enhanced UX

**Features**:
- Dynamic image and title from props
- Loading states with skeleton screens
- Error handling with fallback icons
- Hover effects and smooth transitions
- Description display on hover
- Responsive design

**Props**:
```php
<x-mart.top-cat-card 
    :image="subcategory.photo" 
    :title="subcategory.title"
    :description="subcategory.description"
    :id="subcategory.id" />
```

## Usage Examples

### Basic Implementation
```php
// In your controller
use App\Http\Controllers\MartController;

public function index()
{
    $martController = new MartController();
    $categories = $martController->getHomepageCategories();
    $subcategories = $martController->getAllHomepageSubcategories();
    
    return view('mart.index', compact('categories', 'subcategories'));
}

// In your view
<x-mart.top-cat-items :categories="$categories" :subcategories="$subcategories"/>
```

### With Custom Data
```php
// If you have custom data sources
$categories = collect([
    [
        'id' => 'custom-1',
        'title' => 'Custom Category',
        'description' => 'Custom description',
        'photo' => 'https://example.com/image.jpg',
        'publish' => true,
        'show_in_homepage' => true,
        'category_order' => 1,
        'section' => 'Custom Section',
        'section_order' => 1,
        'review_attributes' => []
    ]
]);

$subcategories = collect([
    [
        'id' => 'custom-sub-1',
        'title' => 'Custom Subcategory',
        'description' => 'Custom subcategory description',
        'photo' => 'https://example.com/sub-image.jpg',
        'publish' => true,
        'show_in_homepage' => true,
        'parent_category_id' => 'custom-1',
        'parent_category_title' => 'Custom Category',
        'subcategory_order' => 1,
        'category_order' => 1,
        'section' => 'Custom Section',
        'section_order' => 1,
        'review_attributes' => []
    ]
]);
```

## Features

### Dynamic Filtering
- Categories are automatically filtered by `publish = true` and `show_in_homepage = true`
- Subcategories are filtered by the same criteria plus `parent_category_id` matching
- All data is sorted by their respective order fields
- Real-time filtering when switching between category tabs

### Enhanced UX
- Smooth scrolling between categories
- Loading states with skeleton screens
- Hover effects and transitions
- Responsive design
- Image error handling with fallbacks
- Empty state handling

### Fallback System
- Demo data provided when Firebase is unavailable
- Graceful degradation
- Comprehensive error logging
- Cache management for performance

### Performance
- 5-minute caching for categories and subcategories
- Lazy loading of images
- Optimized Alpine.js implementation
- Minimal DOM manipulation

## CSS Classes Added

### Utility Classes
- `.line-clamp-1`, `.line-clamp-2`, `.line-clamp-3` - Text truncation
- `.scroll-smooth` - Smooth scrolling behavior
- `.category-tab` - Category tab transitions
- `.card-hover` - Card hover effects

### Animation Classes
- `.animate-pulse` - Loading animation
- `.transition-all` - Smooth transitions
- `.hover:scale-105` - Hover scaling
- `.hover:shadow-lg` - Hover shadow effects

## Browser Support
- Modern browsers with ES6+ support
- Alpine.js 3.x required
- Tailwind CSS 2.x+ required
- CSS Grid and Flexbox support

## Troubleshooting

### Common Issues

1. **No Data Displayed**
   - Check if Firebase credentials are properly configured
   - Verify `FIREBASE_PROJECT_ID` in `.env`
   - Check browser console for JavaScript errors
   - Verify data structure matches expected format

2. **Images Not Loading**
   - Check image URLs are accessible
   - Verify CORS settings for external images
   - Check network tab for failed requests

3. **Filtering Not Working**
   - Verify `parent_category_id` matches between categories and subcategories
   - Check Alpine.js is properly loaded
   - Verify data structure integrity

### Debug Mode
Use the test route to verify data:
```
GET /mart/test-data
```

This will return JSON with:
- Firebase connection status
- Data counts
- Environment information
- Error details if any

## Future Enhancements
- Search functionality
- Pagination for large datasets
- Advanced filtering options
- Animation presets
- Accessibility improvements
- Performance monitoring
