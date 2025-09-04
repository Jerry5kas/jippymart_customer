# Database Query Guide - Getting Specific Data

## 🎯 **Available Data in Database**

Based on the current database, here are the **8 published categories** available:

| ID | Title | Section | Homepage | Order |
|----|-------|---------|----------|-------|
| `68b17af92183b` | Beverages (Non-Alcoholic) | Beverages & Juices | ❌ | 1 |
| `68b16f87cac4e` | Groceries | Grocery & Kitchen | ✅ | 2 |
| `68b18c40e2a7a` | Meat, Fish & eggs | Grocery & Kitchen | ❌ | 2 |
| `68b18dd16845a` | Masala & Dry Fruits | Grocery & Kitchen | ❌ | 3 |
| `68b1701de42a2` | Medicine | Pharmacy & Health | ✅ | 5 |
| `68b6dc7c76741` | Personal Care | General | ❌ | 6 |
| `68b1703bc7556` | Pet | Pet Care | ✅ | 7 |
| `68b17cd28f318` | Smoking Essentials | General | ❌ | 8 |

## 🔍 **Query Methods**

### **1. Get All Categories**
```bash
GET /api/search/categories
```
**Returns**: All 8 published categories
**Use Case**: Display complete category list

### **2. Search by Exact Title**
```bash
GET /api/search/categories?q=Medicine
```
**Returns**: 1 result (Medicine category)
**Use Case**: Find specific category by exact name

### **3. Search by Partial Title**
```bash
GET /api/search/categories?q=groc
```
**Returns**: 1 result (Groceries category)
**Use Case**: Auto-complete, fuzzy search

### **4. Search by Description**
```bash
GET /api/search/categories?q=health
```
**Returns**: Categories containing "health" in title or description
**Use Case**: Content-based search

### **5. Get Homepage Categories Only**
```bash
GET /api/search/categories/published
```
**Returns**: 3 categories (Groceries, Medicine, Pet)
**Use Case**: Homepage display, featured categories

### **6. Paginated Results**
```bash
GET /api/search/categories?page=1&limit=3
```
**Returns**: First 3 categories
**Use Case**: Load more functionality, mobile optimization

## 📊 **Response Data Structure**

Each category contains:
```json
{
  "id": "68b16f87cac4e",
  "title": "Groceries",
  "description": "Groceries",
  "photo": "https://firebasestorage.googleapis.com/...",
  "section": "Grocery & Kitchen",
  "category_order": 2,
  "section_order": 2,
  "show_in_homepage": true
}
```

## 🎯 **Specific Use Cases**

### **Frontend Implementation Examples**

#### **1. Homepage Category Display**
```javascript
// Get categories for homepage
async function getHomepageCategories() {
  const response = await fetch('/api/search/categories/published');
  const data = await response.json();
  
  if (data.success) {
    return data.data; // Returns 3 categories
  }
  return [];
}
```

#### **2. Search with Auto-complete**
```javascript
// Search as user types
async function searchCategories(query) {
  if (query.length < 2) return [];
  
  const response = await fetch(`/api/search/categories?q=${encodeURIComponent(query)}`);
  const data = await response.json();
  
  if (data.success) {
    return data.data;
  }
  return [];
}
```

#### **3. Category Grid with Pagination**
```javascript
// Load categories with pagination
async function loadCategories(page = 1, limit = 6) {
  const response = await fetch(`/api/search/categories?page=${page}&limit=${limit}`);
  const data = await response.json();
  
  if (data.success) {
    return {
      categories: data.data,
      hasMore: data.pagination.has_more,
      currentPage: data.pagination.current_page
    };
  }
  return { categories: [], hasMore: false, currentPage: 1 };
}
```

#### **4. Find Specific Category by ID**
```javascript
// Find category by ID (client-side filtering)
async function getCategoryById(categoryId) {
  const response = await fetch('/api/search/categories');
  const data = await response.json();
  
  if (data.success) {
    return data.data.find(category => category.id === categoryId);
  }
  return null;
}
```

## 🔧 **Advanced Querying**

### **1. Filter by Section**
```javascript
// Get all categories from a specific section
async function getCategoriesBySection(sectionName) {
  const response = await fetch('/api/search/categories');
  const data = await response.json();
  
  if (data.success) {
    return data.data.filter(category => 
      category.section === sectionName
    );
  }
  return [];
}

// Usage: getCategoriesBySection('Grocery & Kitchen')
// Returns: Groceries, Meat Fish & eggs, Masala & Dry Fruits
```

### **2. Sort by Custom Criteria**
```javascript
// Sort categories by custom criteria
async function getSortedCategories(sortBy = 'category_order') {
  const response = await fetch('/api/search/categories');
  const data = await response.json();
  
  if (data.success) {
    return data.data.sort((a, b) => {
      switch (sortBy) {
        case 'title':
          return a.title.localeCompare(b.title);
        case 'section':
          return a.section.localeCompare(b.section);
        case 'category_order':
        default:
          return a.category_order - b.category_order;
      }
    });
  }
  return [];
}
```

### **3. Group by Section**
```javascript
// Group categories by section
async function getCategoriesGroupedBySection() {
  const response = await fetch('/api/search/categories');
  const data = await response.json();
  
  if (data.success) {
    const grouped = {};
    data.data.forEach(category => {
      if (!grouped[category.section]) {
        grouped[category.section] = [];
      }
      grouped[category.section].push(category);
    });
    return grouped;
  }
  return {};
}

// Returns:
// {
//   "Beverages & Juices": [...],
//   "Grocery & Kitchen": [...],
//   "Pharmacy & Health": [...],
//   "General": [...],
//   "Pet Care": [...]
// }
```

## 📱 **Mobile Optimization**

### **1. Lazy Loading**
```javascript
// Load categories in batches
class CategoryLoader {
  constructor() {
    this.page = 1;
    this.limit = 10;
    this.hasMore = true;
  }
  
  async loadMore() {
    if (!this.hasMore) return [];
    
    const response = await fetch(
      `/api/search/categories?page=${this.page}&limit=${this.limit}`
    );
    const data = await response.json();
    
    if (data.success) {
      this.page++;
      this.hasMore = data.pagination.has_more;
      return data.data;
    }
    return [];
  }
}
```

### **2. Search with Debouncing**
```javascript
// Search with debouncing to avoid too many requests
class CategorySearch {
  constructor() {
    this.timeout = null;
    this.debounceDelay = 300;
  }
  
  async search(query) {
    clearTimeout(this.timeout);
    
    return new Promise((resolve) => {
      this.timeout = setTimeout(async () => {
        const response = await fetch(`/api/search/categories?q=${encodeURIComponent(query)}`);
        const data = await response.json();
        resolve(data.success ? data.data : []);
      }, this.debounceDelay);
    });
  }
}
```

## 🚀 **Performance Tips**

### **1. Cache Results**
```javascript
// Cache results to avoid repeated API calls
class CategoryCache {
  constructor() {
    this.cache = new Map();
    this.cacheTimeout = 5 * 60 * 1000; // 5 minutes
  }
  
  get(key) {
    const cached = this.cache.get(key);
    if (cached && Date.now() - cached.timestamp < this.cacheTimeout) {
      return cached.data;
    }
    return null;
  }
  
  set(key, data) {
    this.cache.set(key, {
      data,
      timestamp: Date.now()
    });
  }
}
```

### **2. Preload Important Data**
```javascript
// Preload homepage categories
async function preloadHomepageCategories() {
  try {
    const response = await fetch('/api/search/categories/published');
    const data = await response.json();
    
    if (data.success) {
      // Store in localStorage for offline access
      localStorage.setItem('homepage_categories', JSON.stringify(data.data));
    }
  } catch (error) {
    console.warn('Failed to preload categories:', error);
  }
}
```

## 🎯 **Common Query Patterns**

| Use Case | Query | Result |
|----------|-------|--------|
| Homepage display | `/api/search/categories/published` | 3 featured categories |
| Search box | `/api/search/categories?q={term}` | Filtered results |
| Category grid | `/api/search/categories?page=1&limit=6` | Paginated results |
| All categories | `/api/search/categories` | Complete list |
| Health check | `/api/search/health` | Service status |

This guide shows you exactly how to get specific data from your database using the optimized API endpoints!
