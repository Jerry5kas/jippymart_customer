# 🗺️ JippyMart Location Logic Map

## 📍 **Location Logic Distribution**

### **1. Core Location Files**

#### **A. Header Location Input**
- **File**: `resources/views/layouts/header.blade.php`
- **Lines**: 143-157
- **Purpose**: Location input field and "Use Current Location" button
- **Key Functions**:
  ```javascript
  // Location input field
  <input id="user_locationnew" type="text" placeholder="Enter your location">
  
  // Current location button
  <a onclick="getCurrentLocation('reload'); return false;">
      Use My Current Location
  </a>
  ```

#### **B. Main Location Logic**
- **File**: `resources/views/layouts/footer.blade.php`
- **Lines**: 434-600+
- **Purpose**: Core location detection and management
- **Key Functions**:
  ```javascript
  async function getCurrentLocation(type = '') {
      // Google Maps geocoding
      // Cookie management
      // Location storage
  }
  ```

#### **C. Home Page Location**
- **File**: `resources/views/home.blade.php`
- **Lines**: 1718-2166
- **Purpose**: Location initialization and zone detection
- **Key Functions**:
  ```javascript
  async function initializeLocation()
  async function getUserZoneId()
  async function getAddressFromCoordinates(lat, lng)
  ```

#### **D. Mart Page Location**
- **File**: `resources/views/mart/index.blade.php`
- **Lines**: 234-350+
- **Purpose**: Mart-specific location handling
- **Key Functions**:
  ```javascript
  function initializeLocationFromCookies()
  async function detectZoneFromLocation()
  ```

### **2. Location Controller**

#### **A. HomeController**
- **File**: `app/Http/Controllers/HomeController.php`
- **Lines**: 23-47
- **Purpose**: Location validation and set-location page
- **Key Logic**:
  ```php
  // Redirect to set-location if no address cookie
  if(!isset($_COOKIE['address_name']) && $route != "set-location"){
      \Redirect::to('set-location')->send();
  }
  
  public function setLocation() {
      return view('layer');
  }
  ```

#### **B. Set Location Page**
- **File**: `resources/views/layer.blade.php`
- **Purpose**: Location selection interface
- **Key Logic**:
  ```javascript
  // Location validation before continuing
  if($('#user_locationnew').val() == ''){
      Swal.fire({text: "Select your address", icon: "error"});
      return false;
  }
  ```

### **3. Location Variables & Storage**

#### **A. Global Location Variables**
```javascript
var address_lat;        // User latitude
var address_lng;        // User longitude  
var address_name;       // Full address string
var user_zone_id;       // Delivery zone ID
var user_address;      // User-friendly address
```

#### **B. Cookie Management**
```javascript
// Location cookies stored for 365 days
setCookie('address_lat', address_lat, 365);
setCookie('address_lng', address_lng, 365);
setCookie('address_name', address_name, 365);
setCookie('user_zone_id', user_zone_id, 365);
setCookie('user_address', user_address, 365);
```

### **4. Location Detection APIs**

#### **A. Browser Geolocation**
```javascript
navigator.geolocation.getCurrentPosition(
    function(position) {
        address_lat = position.coords.latitude;
        address_lng = position.coords.longitude;
    }
);
```

#### **B. Google Maps APIs**
```javascript
// Google Maps JavaScript API
script.src = "https://maps.googleapis.com/maps/api/js?key=" + googleMapKey + "&libraries=places";

// Google Places Autocomplete
autocomplete = new google.maps.places.Autocomplete(input);

// Google Geocoding
geocoder.geocode({'latLng': location}, function(results, status) {
    // Process geocoding results
});
```

#### **C. BigDataCloud API**
```javascript
// Reverse geocoding service
const response = await fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lng}&localityLanguage=en`);
```

### **5. Zone Detection Logic**

#### **A. Zone Detection Function**
```javascript
async function getUserZoneId() {
    // Fetch zones from Firebase
    var snapshots = await database.collection('zone').where("publish", "==", true).get();
    
    // Check if user location is within zone boundaries
    for (i = 0; i < zone_list.length; i++) {
        var zone = zone_list[i];
        // Polygon point-in-polygon test
        var isInZone = is_in_polygon(points_polygon, vertices_x, vertices_y, address_lng, address_lat);
        if (isInZone) {
            user_zone_id = zone.id;
            break;
        }
    }
}
```

#### **B. Polygon Point-in-Polygon Test**
```javascript
function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y) {
    // Ray casting algorithm to check if point is inside polygon
}
```

### **6. Location Flow Diagram**

```
1. User visits site
   ↓
2. Check for address_name cookie
   ↓
3. If no cookie → Redirect to /set-location
   ↓
4. User selects location on layer.blade.php
   ↓
5. getCurrentLocation() function called
   ↓
6. Browser geolocation API or manual input
   ↓
7. Google Maps geocoding to get coordinates
   ↓
8. Store location in cookies
   ↓
9. Detect delivery zone (getUserZoneId)
   ↓
10. Load restaurants/products for that zone
```

### **7. Files with Location Logic**

#### **Primary Location Files:**
1. `resources/views/layouts/header.blade.php` - Location input UI
2. `resources/views/layouts/footer.blade.php` - Core location functions
3. `resources/views/home.blade.php` - Home page location logic
4. `resources/views/mart/index.blade.php` - Mart page location logic
5. `resources/views/layer.blade.php` - Set location page
6. `app/Http/Controllers/HomeController.php` - Location controller

#### **Secondary Location Files:**
- `resources/views/search/search.blade.php`
- `resources/views/allrestaurants/index.blade.php`
- `resources/views/products/list.blade.php`
- `resources/views/products/detail.blade.php`
- `resources/views/offers/offers.blade.php`
- `resources/views/favourites/favouritesVendor.blade.php`
- `resources/views/favourites/favouritesProduct.blade.php`
- `resources/views/dinein/index.blade.php`
- `resources/views/delivery_address/index.blade.php`
- `resources/views/checkout/checkout.blade.php`
- `resources/views/allrestaurants/bycategory.blade.php`

### **8. Location Error Handling**

#### **A. Geolocation Errors**
```javascript
navigator.geolocation.getCurrentPosition(
    successCallback,
    errorCallback,
    {
        enableHighAccuracy: true,
        timeout: 15000,
        maximumAge: 300000 // 5 minutes
    }
);
```

#### **B. Fallback Mechanisms**
```javascript
// Fallback to manual location input
// Fallback to default location
// Fallback to coordinate-based address
const fallbackAddress = `Location: ${lat.toFixed(4)}, ${lng.toFixed(4)}`;
```

### **9. Location Validation**

#### **A. Cookie Validation**
```php
// In HomeController constructor
if(!isset($_COOKIE['address_name']) && $route != "set-location"){
    \Redirect::to('set-location')->send();
}
```

#### **B. JavaScript Validation**
```javascript
// Check if location variables are defined and valid
if (typeof address_lat === 'undefined' || typeof address_lng === 'undefined' || 
    address_lat == '' || address_lng == '' || address_lat == null || address_lng == null) {
    console.log("Location not detected yet, waiting...");
    return false;
}
```

### **10. Location Updates**

#### **A. Location Change Detection**
```javascript
// Update when user location changes
window.addEventListener('locationChanged', function() {
    const now = Date.now();
    if (now - lastUpdateTime > MIN_UPDATE_INTERVAL) {
        callStore();
        lastUpdateTime = now;
    }
});
```

#### **B. Retry Mechanism**
```javascript
// Retry mechanism for location detection
let locationRetryCount = 0;
const maxLocationRetries = 15;
const locationRetryInterval = setInterval(() => {
    if (typeof address_lat !== 'undefined' && typeof address_lng !== 'undefined') {
        clearInterval(locationRetryInterval);
        callStore();
    } else if (locationRetryCount >= maxLocationRetries) {
        clearInterval(locationRetryInterval);
        showLocationError();
    }
    locationRetryCount++;
}, 3000);
```

---

## 🎯 **Summary**

The location logic in your JippyMart application is distributed across multiple files with the main logic in:

1. **Header** (`header.blade.php`) - Location input UI
2. **Footer** (`footer.blade.php`) - Core location functions  
3. **Home** (`home.blade.php`) - Location initialization
4. **Mart** (`mart/index.blade.php`) - Mart-specific location
5. **Controller** (`HomeController.php`) - Location validation
6. **Layer** (`layer.blade.php`) - Location selection page

The system uses browser geolocation, Google Maps APIs, and BigDataCloud for location services, with comprehensive error handling and fallback mechanisms.
