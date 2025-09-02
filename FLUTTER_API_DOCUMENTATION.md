# 🚀 **Flutter API Documentation - Jippy Mart**

## 📋 **Table of Contents**
1. [Overview](#overview)
2. [Base Configuration](#base-configuration)
3. [Authentication](#authentication)
4. [Mart Categories API](#mart-categories-api)
5. [Mart Subcategories API](#mart-subcategories-api)
6. [Mart Items API](#mart-items-api)
7. [Mart Vendors API](#mart-vendors-api)
8. [Global Search API](#global-search-api)
9. [Error Handling](#error-handling)
10. [Flutter Implementation Examples](#flutter-implementation-examples)
11. [Firebase Index Requirements](#firebase-index-requirements)

---

## 🌟 **Overview**

This API provides comprehensive access to Jippy Mart's data including categories, subcategories, items, vendors, and global search functionality. All endpoints are designed with fallback logic to ensure functionality even when Firebase indexes are missing.

**Key Features:**
- ✅ **Fallback Logic**: Works without Firebase indexes
- ✅ **Comprehensive Search**: Global search across all entities
- ✅ **Priority-based Results**: Items > Categories > Subcategories > Vendors
- ✅ **Rich Filtering**: Multiple filter options for each endpoint
- ✅ **Pagination Support**: Efficient data loading
- ✅ **Error Handling**: Graceful degradation and helpful error messages

---

## ⚙️ **Base Configuration**

### **Base URL**
```dart
const String baseUrl = 'http://127.0.0.1:8000/api';
// For production: 'https://customer.jippymart.in/api'
```

### **Headers**
```dart
Map<String, String> headers = {
  'Accept': 'application/json',
  'Content-Type': 'application/json',
};
```

---

## 🔐 **Authentication**

**All Mart API endpoints are PUBLIC** and do not require authentication.

```dart
// No authentication headers needed
Map<String, String> headers = {
  'Accept': 'application/json',
  'Content-Type': 'application/json',
};
```

---

## 📂 **Mart Categories API**

### **Endpoint Base**
```
GET /api/mart/categories
```

### **Available Endpoints**

#### 1. **Get All Categories**
```dart
// GET /api/mart/categories
Future<Map<String, dynamic>> getAllCategories({
  bool? publish,
  bool? showInHomepage,
  int? page,
  int? limit,
  String? sortBy,
  String? sortOrder,
}) async {
  final response = await http.get(
    Uri.parse('$baseUrl/mart/categories').replace(queryParameters: {
      if (publish != null) 'publish': publish.toString(),
      if (showInHomepage != null) 'show_in_homepage': showInHomepage.toString(),
      if (page != null) 'page': page.toString(),
      if (limit != null) 'limit': limit.toString(),
      if (sortBy != null) 'sort_by': sortBy,
      if (sortOrder != null) 'sort_order': sortOrder,
    }),
    headers: headers,
  );
  return jsonDecode(response.body);
}
```

#### 2. **Get Homepage Categories**
```dart
// GET /api/mart/categories/homepage
Future<Map<String, dynamic>> getHomepageCategories() async {
  final response = await http.get(
    Uri.parse('$baseUrl/mart/categories/homepage'),
    headers: headers,
  );
  return jsonDecode(response.body);
}
```

#### 3. **Search Categories**
```dart
// POST /api/mart/categories/search
Future<Map<String, dynamic>> searchCategories({
  required String query,
  bool? publish,
  bool? showInHomepage,
}) async {
  final response = await http.post(
    Uri.parse('$baseUrl/mart/categories/search'),
    headers: headers,
    body: jsonEncode({
      'query': query,
      if (publish != null) 'publish': publish,
      if (showInHomepage != null) 'show_in_homepage': showInHomepage,
    }),
  );
  return jsonDecode(response.body);
}
```

#### 4. **Get Category Details**
```dart
// GET /api/mart/categories/{category_id}
Future<Map<String, dynamic>> getCategoryDetails(String categoryId) async {
  final response = await http.get(
    Uri.parse('$baseUrl/mart/categories/$categoryId'),
    headers: headers,
  );
  return jsonDecode(response.body);
}
```

### **Category Data Structure**
```dart
class Category {
  final String id;
  final String title;
  final String description;
  final String photo;
  final bool publish;
  final bool showInHomepage;
  final int categoryOrder;
  final String section;
  final int sectionOrder;
  final List<dynamic> reviewAttributes;
  
  Category.fromJson(Map<String, dynamic> json)
      : id = json['id'] ?? '',
        title = json['title'] ?? '',
        description = json['description'] ?? '',
        photo = json['photo'] ?? '',
        publish = json['publish'] ?? false,
        showInHomepage = json['show_in_homepage'] ?? false,
        categoryOrder = json['category_order'] ?? 0,
        section = json['section'] ?? '',
        sectionOrder = json['section_order'] ?? 0,
        reviewAttributes = json['review_attributes'] ?? [];
}
```

---

## 📁 **Mart Subcategories API**

### **Endpoint Base**
```
GET /api/mart/subcategories
```

### **Available Endpoints**

#### 1. **Get All Subcategories**
```dart
// GET /api/mart/subcategories
Future<Map<String, dynamic>> getAllSubcategories({
  bool? publish,
  bool? showInHomepage,
  String? parentCategoryId,
  int? page,
  int? limit,
  String? sortBy,
  String? sortOrder,
}) async {
  final response = await http.get(
    Uri.parse('$baseUrl/mart/subcategories').replace(queryParameters: {
      if (publish != null) 'publish': publish.toString(),
      if (showInHomepage != null) 'show_in_homepage': showInHomepage.toString(),
      if (parentCategoryId != null) 'parent_category_id': parentCategoryId,
      if (page != null) 'page': page.toString(),
      if (limit != null) 'limit': limit.toString(),
      if (sortBy != null) 'sort_by': sortBy,
      if (sortOrder != null) 'sort_order': sortOrder,
    }),
    headers: headers,
  );
  return jsonDecode(response.body);
}
```

#### 2. **Get Homepage Subcategories**
```dart
// GET /api/mart/subcategories/homepage
Future<Map<String, dynamic>> getHomepageSubcategories() async {
  final response = await http.get(
    Uri.parse('$baseUrl/mart/subcategories/homepage'),
    headers: headers,
  );
  return jsonDecode(response.body);
}
```

#### 3. **Get Subcategories by Parent Category**
```dart
// GET /api/mart/subcategories/by-parent/{parent_category_id}
Future<Map<String, dynamic>> getSubcategoriesByParent(
  String parentCategoryId, {
  int? page,
  int? limit,
}) async {
  final response = await http.get(
    Uri.parse('$baseUrl/mart/subcategories/by-parent/$parentCategoryId')
        .replace(queryParameters: {
      if (page != null) 'page': page.toString(),
      if (limit != null) 'limit': limit.toString(),
    }),
    headers: headers,
  );
  return jsonDecode(response.body);
}
```

#### 4. **Search Subcategories**
```dart
// POST /api/mart/subcategories/search
Future<Map<String, dynamic>> searchSubcategories({
  required String query,
  bool? publish,
  bool? showInHomepage,
  String? parentCategoryId,
}) async {
  final response = await http.post(
    Uri.parse('$baseUrl/mart/subcategories/search'),
    headers: headers,
    body: jsonEncode({
      'query': query,
      if (publish != null) 'publish': publish,
      if (showInHomepage != null) 'show_in_homepage': showInHomepage,
      if (parentCategoryId != null) 'parent_category_id': parentCategoryId,
    }),
  );
  return jsonDecode(response.body);
}
```

### **Subcategory Data Structure**
```dart
class Subcategory {
  final String id;
  final String title;
  final String description;
  final String photo;
  final bool publish;
  final bool showInHomepage;
  final String parentCategoryId;
  final int subcategoryOrder;
  final int categoryOrder;
  final int sectionOrder;
  
  Subcategory.fromJson(Map<String, dynamic> json)
      : id = json['id'] ?? '',
        title = json['title'] ?? '',
        description = json['description'] ?? '',
        photo = json['photo'] ?? '',
        publish = json['publish'] ?? false,
        showInHomepage = json['show_in_homepage'] ?? false,
        parentCategoryId = json['parent_category_id'] ?? '',
        subcategoryOrder = json['subcategory_order'] ?? 0,
        categoryOrder = json['category_order'] ?? 0,
        sectionOrder = json['section_order'] ?? 0;
}
```

---

## 🛍️ **Mart Items API**

### **Endpoint Base**
```
GET /api/mart/items
```

### **Available Endpoints**

#### 1. **Get All Items**
```dart
// GET /api/mart/items
Future<Map<String, dynamic>> getAllItems({
  bool? publish,
  bool? isAvailable,
  String? categoryId,
  String? subcategoryId,
  String? vendorId,
  int? page,
  int? limit,
  String? sortBy,
  String? sortOrder,
}) async {
  final response = await http.get(
    Uri.parse('$baseUrl/mart/items').replace(queryParameters: {
      if (publish != null) 'publish': publish.toString(),
      if (isAvailable != null) 'is_available': isAvailable.toString(),
      if (categoryId != null) 'category_id': categoryId,
      if (subcategoryId != null) 'subcategory_id': subcategoryId,
      if (vendorId != null) 'vendor_id': vendorId,
      if (page != null) 'page': page.toString(),
      if (limit != null) 'limit': limit.toString(),
      if (sortBy != null) 'sort_by': sortBy,
      if (sortOrder != null) 'sort_order': sortOrder,
    }),
    headers: headers,
  );
  return jsonDecode(response.body);
}
```

#### 2. **Get Featured Items**
```dart
// GET /api/mart/items/featured
Future<Map<String, dynamic>> getFeaturedItems({
  int? page,
  int? limit,
}) async {
  final response = await http.get(
    Uri.parse('$baseUrl/mart/items/featured').replace(queryParameters: {
      if (page != null) 'page': page.toString(),
      if (limit != null) 'limit': limit.toString(),
    }),
    headers: headers,
  );
  return jsonDecode(response.body);
}
```

#### 3. **Get Best Sellers**
```dart
// GET /api/mart/items/best-sellers
Future<Map<String, dynamic>> getBestSellers({
  int? page,
  int? limit,
}) async {
  final response = await http.get(
    Uri.parse('$baseUrl/mart/items/best-sellers').replace(queryParameters: {
      if (page != null) 'page': page.toString(),
      if (limit != null) 'limit': limit.toString(),
    }),
    headers: headers,
  );
  return jsonDecode(response.body);
}
```

#### 4. **Get Items by Category**
```dart
// GET /api/mart/items/by-category/{category_id}
Future<Map<String, dynamic>> getItemsByCategory(
  String categoryId, {
  int? page,
  int? limit,
  String? sortBy,
  String? sortOrder,
}) async {
  final response = await http.get(
    Uri.parse('$baseUrl/mart/items/by-category/$categoryId')
        .replace(queryParameters: {
      if (page != null) 'page': page.toString(),
      if (limit != null) 'limit': limit.toString(),
      if (sortBy != null) 'sort_by': sortBy,
      if (sortOrder != null) 'sort_order': sortOrder,
    }),
    headers: headers,
  );
  return jsonDecode(response.body);
}
```

#### 5. **Get Items by Vendor**
```dart
// GET /api/mart/items/by-vendor/{vendor_id}
Future<Map<String, dynamic>> getItemsByVendor(
  String vendorId, {
  int? page,
  int? limit,
  String? sortBy,
  String? sortOrder,
}) async {
  final response = await http.get(
    Uri.parse('$baseUrl/mart/items/by-vendor/$vendorId')
        .replace(queryParameters: {
      if (page != null) 'page': page.toString(),
      if (limit != null) 'limit': limit.toString(),
      if (sortBy != null) 'sort_by': sortBy,
      if (sortOrder != null) 'sort_order': sortOrder,
    }),
    headers: headers,
  );
  return jsonDecode(response.body);
}
```

#### 6. **Search Items**
```dart
// POST /api/mart/items/search
Future<Map<String, dynamic>> searchItems({
  required String query,
  bool? publish,
  bool? isAvailable,
  String? categoryId,
  String? subcategoryId,
  String? vendorId,
  int? page,
  int? limit,
}) async {
  final response = await http.post(
    Uri.parse('$baseUrl/mart/items/search'),
    headers: headers,
    body: jsonEncode({
      'query': query,
      if (publish != null) 'publish': publish,
      if (isAvailable != null) 'is_available': isAvailable,
      if (categoryId != null) 'category_id': categoryId,
      if (subcategoryId != null) 'subcategory_id': subcategoryId,
      if (vendorId != null) 'vendor_id': vendorId,
      if (page != null) 'page': page,
      if (limit != null) 'limit': limit,
    }),
  );
  return jsonDecode(response.body);
}
```

### **Item Data Structure**
```dart
class Item {
  final String id;
  final String name;
  final String description;
  final String photo;
  final double price;
  final double disPrice;
  final bool publish;
  final bool isAvailable;
  final bool isBestSeller;
  final bool isFeature;
  final bool isNew;
  final bool isSeasonal;
  final bool isSpotlight;
  final bool isStealOfMoment;
  final bool isTrending;
  final String categoryId;
  final List<String> subcategoryIds;
  final String vendorId;
  final String reviewCount;
  final String reviewSum;
  final bool veg;
  final bool nonveg;
  final int calories;
  final int proteins;
  final int fats;
  final int grams;
  
  Item.fromJson(Map<String, dynamic> json)
      : id = json['id'] ?? '',
        name = json['name'] ?? '',
        description = json['description'] ?? '',
        photo = json['photo'] ?? '',
        price = (json['price'] ?? 0).toDouble(),
        disPrice = (json['disPrice'] ?? 0).toDouble(),
        publish = json['publish'] ?? false,
        isAvailable = json['isAvailable'] ?? false,
        isBestSeller = json['isBestSeller'] ?? false,
        isFeature = json['isFeature'] ?? false,
        isNew = json['isNew'] ?? false,
        isSeasonal = json['isSeasonal'] ?? false,
        isSpotlight = json['isSpotlight'] ?? false,
        isStealOfMoment = json['isStealOfMoment'] ?? false,
        isTrending = json['isTrending'] ?? false,
        categoryId = json['categoryID'] ?? '',
        subcategoryIds = List<String>.from(json['subcategoryID'] ?? []),
        vendorId = json['vendorID'] ?? '',
        reviewCount = json['reviewCount'] ?? '0',
        reviewSum = json['reviewSum'] ?? '0',
        veg = json['veg'] ?? false,
        nonveg = json['nonveg'] ?? false,
        calories = json['calories'] ?? 0,
        proteins = json['proteins'] ?? 0,
        fats = json['fats'] ?? 0,
        grams = json['grams'] ?? 0;
}
```

---

## 🏪 **Mart Vendors API**

### **Endpoint Base**
```
GET /api/mart/vendors
```

### **Available Endpoints**

#### 1. **Get All Vendors**
```dart
// GET /api/mart/vendors
Future<Map<String, dynamic>> getAllVendors({
  bool? publish,
  bool? isOpen,
  bool? enabledDelivery,
  String? categoryId,
  String? zoneId,
  String? search,
  int? page,
  int? limit,
  String? sortBy,
  String? sortOrder,
}) async {
  final response = await http.get(
    Uri.parse('$baseUrl/mart/vendors').replace(queryParameters: {
      if (publish != null) 'publish': publish.toString(),
      if (isOpen != null) 'is_open': isOpen.toString(),
      if (enabledDelivery != null) 'enabled_delivery': enabledDelivery.toString(),
      if (categoryId != null) 'category_id': categoryId,
      if (zoneId != null) 'zone_id': zoneId,
      if (search != null) 'search': search,
      if (page != null) 'page': page.toString(),
      if (limit != null) 'limit': limit.toString(),
      if (sortBy != null) 'sort_by': sortBy,
      if (sortOrder != null) 'sort_order': sortOrder,
    }),
    headers: headers,
  );
  return jsonDecode(response.body);
}
```

#### 2. **Get Vendor Details**
```dart
// GET /api/mart/vendors/{vendor_id}
Future<Map<String, dynamic>> getVendorDetails(String vendorId) async {
  final response = await http.get(
    Uri.parse('$baseUrl/mart/vendors/$vendorId'),
    headers: headers,
  );
  return jsonDecode(response.body);
}
```

#### 3. **Get Nearby Vendors**
```dart
// POST /api/mart/vendors/nearby
Future<Map<String, dynamic>> getNearbyVendors({
  required double latitude,
  required double longitude,
  double? radius,
  int? limit,
  String? categoryId,
  bool? enabledDelivery,
}) async {
  final response = await http.post(
    Uri.parse('$baseUrl/mart/vendors/nearby'),
    headers: headers,
    body: jsonEncode({
      'latitude': latitude,
      'longitude': longitude,
      if (radius != null) 'radius': radius,
      if (limit != null) 'limit': limit,
      if (categoryId != null) 'category_id': categoryId,
      if (enabledDelivery != null) 'enabled_delivery': enabledDelivery,
    }),
  );
  return jsonDecode(response.body);
}
```

#### 4. **Get Vendors by Category**
```dart
// POST /api/mart/vendors/by-category
Future<Map<String, dynamic>> getVendorsByCategory({
  required String categoryId,
  int? page,
  int? limit,
  String? sortBy,
  String? sortOrder,
}) async {
  final response = await http.post(
    Uri.parse('$baseUrl/mart/vendors/by-category'),
    headers: headers,
    body: jsonEncode({
      'category_id': categoryId,
      if (page != null) 'page': page,
      if (limit != null) 'limit': limit,
      if (sortBy != null) 'sort_by': sortBy,
      if (sortOrder != null) 'sort_order': sortOrder,
    }),
  );
  return jsonDecode(response.body);
}
```

#### 5. **Get Vendor Working Hours**
```dart
// POST /api/mart/vendors/working-hours
Future<Map<String, dynamic>> getVendorWorkingHours({
  required String vendorId,
}) async {
  final response = await http.post(
    Uri.parse('$baseUrl/mart/vendors/working-hours'),
    headers: headers,
    body: jsonEncode({
      'vendor_id': vendorId,
    }),
  );
  return jsonDecode(response.body);
}
```

#### 6. **Get Vendor Special Discounts**
```dart
// POST /api/mart/vendors/special-discounts
Future<Map<String, dynamic>> getVendorSpecialDiscounts({
  required String vendorId,
}) async {
  final response = await http.post(
    Uri.parse('$baseUrl/mart/vendors/special-discounts'),
    headers: headers,
    body: jsonEncode({
      'vendor_id': vendorId,
    }),
  );
  return jsonDecode(response.body);
}
```

#### 7. **Search Vendors**
```dart
// POST /api/mart/vendors/search
Future<Map<String, dynamic>> searchVendors({
  required String query,
  bool? publish,
  bool? isOpen,
  bool? enabledDelivery,
  String? categoryId,
  int? page,
  int? limit,
}) async {
  final response = await http.post(
    Uri.parse('$baseUrl/mart/vendors/search'),
    headers: headers,
    body: jsonEncode({
      'query': query,
      if (publish != null) 'publish': publish,
      if (isOpen != null) 'is_open': isOpen,
      if (enabledDelivery != null) 'enabled_delivery': enabledDelivery,
      if (categoryId != null) 'category_id': categoryId,
      if (page != null) 'page': page,
      if (limit != null) 'limit': limit,
    }),
  );
  return jsonDecode(response.body);
}
```

### **Vendor Data Structure**
```dart
class Vendor {
  final String id;
  final String title;
  final String description;
  final String vType;
  final bool publish;
  final bool isOpen;
  final bool enabledDelivery;
  final double latitude;
  final double longitude;
  final String location;
  final String phoneNumber;
  final String countryCode;
  final List<String> categoryIds;
  final List<String> categoryTitles;
  final String zoneId;
  final List<WorkingHours> workingHours;
  final List<SpecialDiscount> specialDiscounts;
  final AdminCommission adminCommission;
  
  Vendor.fromJson(Map<String, dynamic> json)
      : id = json['id'] ?? '',
        title = json['title'] ?? '',
        description = json['description'] ?? '',
        vType = json['vType'] ?? '',
        publish = json['publish'] ?? false,
        isOpen = json['isOpen'] ?? false,
        enabledDelivery = json['enabledDelivery'] ?? false,
        latitude = (json['latitude'] ?? 0).toDouble(),
        longitude = (json['longitude'] ?? 0).toDouble(),
        location = json['location'] ?? '',
        phoneNumber = json['phonenumber'] ?? '',
        countryCode = json['countryCode'] ?? '',
        categoryIds = List<String>.from(json['categoryID'] ?? []),
        categoryTitles = List<String>.from(json['categoryTitle'] ?? []),
        zoneId = json['zoneId'] ?? '',
        workingHours = (json['workingHours'] ?? [])
            .map((e) => WorkingHours.fromJson(e))
            .toList(),
        specialDiscounts = (json['specialDiscount'] ?? [])
            .map((e) => SpecialDiscount.fromJson(e))
            .toList(),
        adminCommission = AdminCommission.fromJson(json['adminCommission'] ?? {});
}

class WorkingHours {
  final String day;
  final List<TimeSlot> timeSlots;
  
  WorkingHours.fromJson(Map<String, dynamic> json)
      : day = json['day'] ?? '',
        timeSlots = (json['timeslot'] ?? [])
            .map((e) => TimeSlot.fromJson(e))
            .toList();
}

class TimeSlot {
  final String from;
  final String to;
  
  TimeSlot.fromJson(Map<String, dynamic> json)
      : from = json['from'] ?? '',
        to = json['to'] ?? '';
}

class SpecialDiscount {
  final String day;
  final List<dynamic> timeSlots;
  
  SpecialDiscount.fromJson(Map<String, dynamic> json)
      : day = json['day'] ?? '',
        timeSlots = json['timeslot'] ?? [];
}

class AdminCommission {
  final String commissionType;
  final double fixCommission;
  final bool isEnabled;
  
  AdminCommission.fromJson(Map<String, dynamic> json)
      : commissionType = json['commissionType'] ?? '',
        fixCommission = (json['fix_commission'] ?? 0).toDouble(),
        isEnabled = json['isEnabled'] ?? false;
}
```

---

## 🔍 **Global Search API**

### **Endpoint**
```
POST /api/mart/all-search
```

### **Description**
This is a **powerful unified search endpoint** that searches across all mart entities (categories, subcategories, items, and vendors) in a single request. It returns results with **items as the priority**.

### **Usage**
```dart
// Global search across all entities
Future<Map<String, dynamic>> globalSearch({
  required String query,
  String? searchType, // 'all', 'items', 'categories', 'subcategories', 'vendors'
  String? categoryId,
  String? subcategoryId,
  String? vendorId,
  bool? publish,
  bool? isAvailable,
  bool? isOpen,
  bool? enabledDelivery,
  int? page,
  int? limit,
}) async {
  final response = await http.post(
    Uri.parse('$baseUrl/mart/all-search'),
    headers: headers,
    body: jsonEncode({
      'query': query,
      if (searchType != null) 'search_type': searchType,
      if (categoryId != null) 'category_id': categoryId,
      if (subcategoryId != null) 'subcategory_id': subcategoryId,
      if (vendorId != null) 'vendor_id': vendorId,
      if (publish != null) 'publish': publish,
      if (isAvailable != null) 'is_available': isAvailable,
      if (isOpen != null) 'is_open': isOpen,
      if (enabledDelivery != null) 'enabled_delivery': enabledDelivery,
      if (page != null) 'page': page,
      if (limit != null) 'limit': limit,
    }),
  );
  return jsonDecode(response.body);
}
```

### **Search Examples**

#### **1. Search for "Groceries"**
```dart
final results = await globalSearch(query: 'Groceries');
// Returns: items, categories, subcategories, and vendors containing "Groceries"
```

#### **2. Search for Items Only**
```dart
final results = await globalSearch(
  query: 'Potato',
  searchType: 'items',
);
// Returns: only items containing "Potato"
```

#### **3. Search by Category**
```dart
final results = await globalSearch(
  query: 'Vegetables',
  categoryId: '68b16f87cac4e',
);
// Returns: all entities containing "Vegetables" in the specified category
```

#### **4. Search for Open Vendors**
```dart
final results = await globalSearch(
  query: 'Mart',
  searchType: 'vendors',
  isOpen: true,
);
// Returns: open vendors containing "Mart"
```

### **Response Structure**
```dart
{
  "success": true,
  "data": {
    "items": {
      "data": [...], // Array of items
      "total": 5,
      "has_more": false
    },
    "categories": {
      "data": [...], // Array of categories
      "total": 2,
      "has_more": false
    },
    "subcategories": {
      "data": [...], // Array of subcategories
      "total": 3,
      "has_more": false
    },
    "vendors": {
      "data": [...], // Array of vendors
      "total": 1,
      "has_more": false
    },
    "meta": {
      "query": "Groceries",
      "search_type": "all",
      "current_page": 1,
      "per_page": 20,
      "total_results": 11,
      "priority_order": "Items > Categories > Subcategories > Vendors",
      "search_insights": {
        "items_found": 5,
        "categories_found": 2,
        "subcategories_found": 3,
        "vendors_found": 1,
        "search_suggestions": [...]
      }
    }
  }
}
```

---

## ⚠️ **Error Handling**

### **Common HTTP Status Codes**
- **200**: Success
- **400**: Bad Request (validation errors)
- **404**: Not Found
- **422**: Validation Failed
- **500**: Internal Server Error

### **Error Response Structure**
```dart
{
  "success": false,
  "message": "Error description",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

### **Flutter Error Handling Example**
```dart
try {
  final response = await http.get(Uri.parse('$baseUrl/mart/categories'));
  
  if (response.statusCode == 200) {
    final data = jsonDecode(response.body);
    if (data['success'] == true) {
      return data['data'];
    } else {
      throw Exception(data['message'] ?? 'Unknown error');
    }
  } else {
    throw Exception('HTTP ${response.statusCode}: ${response.reasonPhrase}');
  }
} catch (e) {
  // Handle error gracefully
  print('Error: $e');
  // Show user-friendly error message
  return [];
}
```

---

## 📱 **Flutter Implementation Examples**

### **1. Categories Screen**
```dart
class CategoriesScreen extends StatefulWidget {
  @override
  _CategoriesScreenState createState() => _CategoriesScreenState();
}

class _CategoriesScreenState extends State<CategoriesScreen> {
  List<Category> categories = [];
  bool isLoading = true;
  String? error;

  @override
  void initState() {
    super.initState();
    _loadCategories();
  }

  Future<void> _loadCategories() async {
    try {
      setState(() {
        isLoading = true;
        error = null;
      });

      final data = await getAllCategories(publish: true);
      setState(() {
        categories = (data as List)
            .map((json) => Category.fromJson(json))
            .toList();
        isLoading = false;
      });
    } catch (e) {
      setState(() {
        error = e.toString();
        isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    if (isLoading) {
      return Center(child: CircularProgressIndicator());
    }

    if (error != null) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text('Error: $error'),
            ElevatedButton(
              onPressed: _loadCategories,
              child: Text('Retry'),
            ),
          ],
        ),
      );
    }

    return GridView.builder(
      padding: EdgeInsets.all(16),
      gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 2,
        childAspectRatio: 1.2,
        crossAxisSpacing: 16,
        mainAxisSpacing: 16,
      ),
      itemCount: categories.length,
      itemBuilder: (context, index) {
        final category = categories[index];
        return CategoryCard(category: category);
      },
    );
  }
}
```

### **2. Global Search Screen**
```dart
class GlobalSearchScreen extends StatefulWidget {
  @override
  _GlobalSearchScreenState createState() => _GlobalSearchScreenState();
}

class _GlobalSearchScreenState extends State<GlobalSearchScreen> {
  final TextEditingController _searchController = TextEditingController();
  Map<String, dynamic>? searchResults;
  bool isSearching = false;

  Future<void> _performSearch(String query) async {
    if (query.length < 2) return;

    setState(() {
      isSearching = true;
    });

    try {
      final results = await globalSearch(query: query);
      setState(() {
        searchResults = results;
        isSearching = false;
      });
    } catch (e) {
      setState(() {
        isSearching = false;
      });
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Search failed: $e')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: TextField(
          controller: _searchController,
          decoration: InputDecoration(
            hintText: 'Search categories, items, vendors...',
            border: InputBorder.none,
          ),
          onSubmitted: _performSearch,
        ),
      ),
      body: Column(
        children: [
          if (isSearching)
            LinearProgressIndicator()
          else if (searchResults != null)
            Expanded(
              child: _buildSearchResults(),
            ),
        ],
      ),
    );
  }

  Widget _buildSearchResults() {
    final data = searchResults!['data'];
    final meta = data['meta'];
    
    return ListView(
      padding: EdgeInsets.all(16),
      children: [
        // Items Section
        if (data['items']['total'] > 0) ...[
          _buildSectionHeader('Items (${data['items']['total']})'),
          ...data['items']['data'].map((item) => ItemTile(item: item)),
        ],
        
        // Categories Section
        if (data['categories']['total'] > 0) ...[
          _buildSectionHeader('Categories (${data['categories']['total']})'),
          ...data['categories']['data'].map((category) => CategoryTile(category: category)),
        ],
        
        // Subcategories Section
        if (data['subcategories']['total'] > 0) ...[
          _buildSectionHeader('Subcategories (${data['subcategories']['total']})'),
          ...data['subcategories']['data'].map((subcategory) => SubcategoryTile(subcategory: subcategory)),
        ],
        
        // Vendors Section
        if (data['vendors']['total'] > 0) ...[
          _buildSectionHeader('Vendors (${data['vendors']['total']})'),
          ...data['vendors']['data'].map((vendor) => VendorTile(vendor: vendor)),
        ],
      ],
    );
  }

  Widget _buildSectionHeader(String title) {
    return Padding(
      padding: EdgeInsets.symmetric(vertical: 16),
      child: Text(
        title,
        style: Theme.of(context).textTheme.headline6,
      ),
    );
  }
}
```

---

## 🔥 **Firebase Index Requirements**

### **Required Indexes for Optimal Performance**

#### **1. Categories Indexes**
- `publish` + `show_in_homepage` + `title`
- `has_subcategories` + `publish` + `title`

#### **2. Subcategories Indexes**
- `publish` + `show_in_homepage` + `parent_category_id`
- `parent_category_id` + `publish` + `show_in_homepage`

#### **3. Items Indexes**
- `categoryID` + `publish` + `isAvailable`
- `subcategoryID` + `publish` + `isAvailable`
- `vendorID` + `publish` + `isAvailable`
- `isSpotlight` + `publish` + `isAvailable`
- `isBestSeller` + `publish` + `isAvailable`
- `isFeature` + `publish` + `isAvailable`

#### **4. Vendors Indexes**
- `vType` + `publish` + `title`
- `vType` + `categoryID` + `publish`
- `vType` + `zoneId` + `publish`
- `vType` + `isOpen` + `enabledDelivery`

### **Index Creation Links**
```
https://console.firebase.google.com/project/jippymart-27c08/firestore/indexes
```

---

## 🎯 **Best Practices**

### **1. Error Handling**
- Always check `response.statusCode` and `data['success']`
- Implement retry logic for failed requests
- Show user-friendly error messages

### **2. Loading States**
- Show loading indicators during API calls
- Implement skeleton screens for better UX
- Cache results when possible

### **3. Pagination**
- Implement infinite scrolling for large lists
- Use `has_more` flag to determine when to load more
- Respect the `limit` parameter

### **4. Search Optimization**
- Debounce search input to avoid excessive API calls
- Cache search results
- Implement search suggestions

### **5. Data Models**
- Use proper data models with `fromJson` constructors
- Implement proper null safety
- Use enums for fixed values

---

## 📞 **Support**

If you encounter any issues or need help with the API:

1. **Check the logs** for detailed error messages
2. **Verify Firebase indexes** are created and enabled
3. **Test with Postman/cURL** to isolate issues
4. **Check the fallback logic** is working correctly

---

## 🚀 **Getting Started**

1. **Set up your Flutter project**
2. **Add HTTP package**: `flutter pub add http`
3. **Copy the API service classes**
4. **Implement the UI components**
5. **Test with the provided examples**
6. **Create Firebase indexes for optimal performance**

**Happy coding! 🎉**
