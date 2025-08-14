# New Delivery Charge System

## Overview

This document describes the implementation of the new delivery charge system that implements the business rules for Jippy Customer app.

## Business Rules

### 1. Item Total < ₹299
- **Distance ≤ 7km**: Delivery Fee: ₹23.00
- **Distance > 7km**: Delivery Fee: ₹23.00 (base) + ₹8.00 per km for each km above 7km
  - Example: 10km → ₹23.00 + (3 × ₹8.00) = ₹47.00

### 2. Item Total ≥ ₹299
- **Distance ≤ 7km**:
  - Delivery Fee: Free
  - UI: Show 'Free Delivery' (green), original fee (₹23.00) with strikethrough (red), charged amount: ₹0.00
- **Distance > 7km**:
  - Delivery Fee: Only the extra km fee is charged: ₹8.00 per km for each km above 7km
  - UI: Show 'Free Delivery' (green), original fee (base + extra, e.g., ₹47.00) with strikethrough (red), charged amount: only the extra km fee (e.g., ₹24.00 for 3km above 7km)

## Implementation

### Files Created/Modified

1. **`app/Services/DeliveryChargeService.php`** - Core service for delivery charge calculations
2. **`app/Traits/DeliveryChargeTrait.php`** - Trait for easy integration in controllers
3. **`app/Http/Controllers/ProductController.php`** - Updated to use new system
4. **`app/Http/Controllers/CheckoutController.php`** - Updated to use new system
5. **`resources/views/restaurant/cart_item.blade.php`** - Updated UI to show new delivery charge display
6. **`config/delivery_charge.php`** - Configuration file
7. **`tests/Unit/DeliveryChargeServiceTest.php`** - Unit tests

### Key Features

#### 1. Backward Compatibility
- The new system maintains backward compatibility with existing cart structure
- Old delivery charge fields (`deliverycharge`, `deliverychargemain`) are still populated
- New calculation data is stored in `delivery_charge_calculation`

#### 2. Configuration Management
- Settings can be configured via `config/delivery_charge.php`
- Firebase/Firestore integration ready (placeholder implemented)
- Environment variables for enabling/disabling the system

#### 3. UI Components
- Dynamic UI display based on calculation results
- Three display scenarios: normal, free delivery, extra distance
- Strikethrough pricing for savings visualization

#### 4. Service Methods

```php
// Calculate delivery charge
$calculation = $deliveryChargeService->calculateDeliveryCharge($itemTotal, $distance);

// Get UI display components
$display = $deliveryChargeService->getDeliveryChargeDisplay($itemTotal, $distance);

// Update cart with new calculation
$updatedCart = $deliveryChargeService->updateCartDeliveryCharge($cart);
```

## Configuration

### Environment Variables
```env
ENABLE_NEW_DELIVERY_CHARGE_SYSTEM=true
FIREBASE_DELIVERY_CHARGE_ENABLED=false
```

### Config File Settings
```php
// config/delivery_charge.php
'default_settings' => [
    'base_delivery_charge' => 23,
    'item_total_threshold' => 299,
    'free_delivery_distance_km' => 7,
    'per_km_charge_above_free_distance' => 8,
],
```

## Usage Examples

### Basic Calculation
```php
$deliveryChargeService = new DeliveryChargeService();

// Item total: ₹250, Distance: 5km
$result = $deliveryChargeService->calculateDeliveryCharge(250, 5);
// Result: actual_fee = 23, original_fee = 23, savings = 0

// Item total: ₹350, Distance: 5km  
$result = $deliveryChargeService->calculateDeliveryCharge(350, 5);
// Result: actual_fee = 0, original_fee = 23, savings = 23

// Item total: ₹350, Distance: 10km
$result = $deliveryChargeService->calculateDeliveryCharge(350, 10);
// Result: actual_fee = 24, original_fee = 47, savings = 23
```

### Cart Integration
```php
// In controller
$cart = Session::get('cart', []);
$updatedCart = $this->deliveryChargeService->updateCartDeliveryCharge($cart);
Session::put('cart', $updatedCart);
```

## UI Display Scenarios

### 1. Normal Fee (No Savings)
```
₹23.00
```

### 2. Free Delivery (≤ 7km, ≥ ₹299)
```
Free Delivery
₹23.00 (strikethrough)
₹0.00
```

### 3. Extra Distance (≥ ₹299, > 7km)
```
Free Delivery  
₹47.00 (strikethrough)
₹24.00
```

## Testing

Run the unit tests to verify the implementation:
```bash
php artisan test tests/Unit/DeliveryChargeServiceTest.php
```

## Integration Points

### 1. ProductController
- Updated `addToCart()` method to use new delivery charge calculation
- Maintains backward compatibility with existing cart structure

### 2. CheckoutController  
- Updated `checkout()` method to recalculate delivery charges
- Integrates with existing distance calculation logic

### 3. Cart Item View
- Updated to display new delivery charge UI components
- Fallback to old system display if new calculation not available

## Migration Strategy

1. **Phase 1**: Deploy with new system disabled (use config)
2. **Phase 2**: Enable new system for testing
3. **Phase 3**: Monitor and validate calculations
4. **Phase 4**: Full rollout

## Firebase Integration (Future)

The system is designed to integrate with Firebase/Firestore for dynamic settings:

```php
// In DeliveryChargeService::getDeliverySettings()
if (config('delivery_charge.firebase.enabled', false)) {
    $firebaseSettings = $this->getFirebaseSettings();
    return array_merge($defaultSettings, $firebaseSettings);
}
```

## Benefits

1. **Flexible Business Rules**: Easy to modify thresholds and rates
2. **Clear UI**: Users see exactly what they're saving
3. **Backward Compatible**: No breaking changes to existing functionality
4. **Testable**: Comprehensive unit tests
5. **Configurable**: Settings can be changed without code deployment
6. **Scalable**: Ready for Firebase integration

## Example Table

| Item Total | Distance | UI Shows (Delivery Fee) | Fee Added to Total |
|------------|----------|--------------------------|--------------------|
| ₹250       | 5km      | ₹23.00                   | ₹23.00             |
| ₹250       | 10km     | ₹47.00                   | ₹47.00             |
| ₹350       | 5km      | Free Delivery, ₹23.00 (strikethrough), ₹0.00 | ₹0.00 |
| ₹350       | 10km     | Free Delivery, ₹47.00 (strikethrough), ₹24.00 | ₹24.00 |
