# New Delivery Charge System Implementation

## Overview

This document describes the implementation of the new delivery charge system that follows the business rules specified in the requirements. The system is designed to be backward compatible and can be enabled/disabled without affecting existing functionality.

## Business Rules

### Core Logic
1. **Item total < ₹299**
   - If distance ≤ 7km: Delivery Fee: ₹23.00
   - If distance > 7km: Delivery Fee: ₹23.00 (base) + ₹8.00 per km for each km above 7km

2. **Item total ≥ ₹299**
   - If distance ≤ 7km: Free Delivery (show original fee with strikethrough)
   - If distance > 7km: Only charge extra km fee (show original fee with strikethrough)

### Configuration Parameters
- `base_delivery_charge`: 23 (₹)
- `item_total_threshold`: 299 (₹)
- `free_delivery_distance_km`: 7 (km)
- `per_km_charge_above_free_distance`: 8 (₹/km)

## Implementation Components

### 1. Backend Services

#### `DeliveryChargeService` (`app/Services/DeliveryChargeService.php`)
- Core calculation logic
- Business rule implementation
- Display formatting

#### `DeliveryChargeHelper` (`app/Helpers/DeliveryChargeHelper.php`)
- Configuration management
- Utility functions
- Display formatting helpers

### 2. API Endpoints

#### Calculate Delivery Charge
```
POST /api/calculate-delivery-charge
{
    "item_total": 350,
    "distance": 10,
    "delivery_settings": {} // optional
}
```

#### Get Settings
```
GET /api/delivery-settings
```

#### Update Settings (Admin)
```
POST /api/update-delivery-settings
{
    "base_delivery_charge": 23,
    "item_total_threshold": 299,
    "free_delivery_distance_km": 7,
    "per_km_charge_above_free_distance": 8,
    "vendor_can_modify": false
}
```

### 3. Frontend Integration

#### JavaScript Helper (`public/js/delivery-charge-helper.js`)
- Client-side calculations
- UI updates
- Settings management

#### Cart Display Updates (`resources/views/restaurant/cart_item.blade.php`)
- Enhanced delivery fee display
- Strikethrough formatting
- Free delivery indicators

## Usage Examples

### Backend Usage

```php
use App\Services\DeliveryChargeService;

$service = new DeliveryChargeService();
$calculation = $service->calculateDeliveryCharge(350, 10);

// Result:
// [
//     'original_fee' => 47.0,
//     'actual_fee' => 24.0,
//     'is_free_delivery' => false,
//     'savings' => 23.0,
//     'settings' => [...]
// ]
```

### Frontend Usage

```javascript
// Initialize
await window.deliveryChargeHelper.init();

// Calculate
const calculation = window.deliveryChargeHelper.calculateDeliveryCharge(350, 10);

// Update UI
window.deliveryChargeHelper.updateCartDeliveryCharge(350, 10);
```

## Configuration

### Enable/Disable System
```php
// In DeliveryChargeHelper.php
public static function isNewDeliverySystemEnabled()
{
    return true; // Set to false to disable
}
```

### Update Settings
```javascript
// Frontend
window.deliveryChargeHelper.updateSettings({
    base_delivery_charge: 25,
    item_total_threshold: 350
});

// Backend
$settings = [
    'base_delivery_charge' => 25,
    'item_total_threshold' => 350
];
```

## Testing Scenarios

### Test Cases

| Item Total | Distance | Expected Original Fee | Expected Actual Fee | Display Type |
|------------|----------|---------------------|-------------------|--------------|
| ₹250 | 5km | ₹23.00 | ₹23.00 | Normal |
| ₹250 | 10km | ₹47.00 | ₹47.00 | Normal |
| ₹350 | 5km | ₹23.00 | ₹0.00 | Free Delivery |
| ₹350 | 10km | ₹47.00 | ₹24.00 | Partial Free |

### API Testing

```bash
# Test calculation
curl -X POST http://your-domain/api/calculate-delivery-charge \
  -H "Content-Type: application/json" \
  -d '{"item_total": 350, "distance": 10}'

# Test settings
curl -X GET http://your-domain/api/delivery-settings
```

## Migration Strategy

### Phase 1: Implementation (Current)
- ✅ New service classes created
- ✅ API endpoints added
- ✅ Frontend helper created
- ✅ Cart display updated

### Phase 2: Testing
- [ ] Unit tests for calculation logic
- [ ] Integration tests for API endpoints
- [ ] Frontend testing with various scenarios
- [ ] Performance testing

### Phase 3: Deployment
- [ ] Enable new system in staging
- [ ] Monitor for issues
- [ ] Gradual rollout to production
- [ ] Monitor metrics and user feedback

### Phase 4: Optimization
- [ ] Firebase integration for settings
- [ ] Real-time updates
- [ ] Advanced features (time-based, zone-based)

## Backward Compatibility

The implementation maintains full backward compatibility:

1. **Fallback System**: If new system is disabled, old logic is used
2. **Gradual Rollout**: Can be enabled per environment
3. **No Breaking Changes**: Existing cart functionality remains intact
4. **Configuration Driven**: All settings are configurable

## Monitoring and Analytics

### Key Metrics to Track
- Delivery charge calculation accuracy
- User satisfaction with new pricing
- Order completion rates
- Revenue impact

### Logging
```php
// Add to DeliveryChargeService
Log::info('Delivery charge calculated', [
    'item_total' => $itemTotal,
    'distance' => $distance,
    'original_fee' => $calculation['original_fee'],
    'actual_fee' => $calculation['actual_fee'],
    'savings' => $calculation['savings']
]);
```

## Troubleshooting

### Common Issues

1. **Settings not loading**
   - Check API endpoint availability
   - Verify Firebase connection
   - Check browser console for errors

2. **Calculations incorrect**
   - Verify settings in helper
   - Check distance calculation
   - Validate business logic

3. **UI not updating**
   - Check JavaScript console
   - Verify DOM element selectors
   - Test helper initialization

### Debug Mode
```javascript
// Enable debug logging
window.deliveryChargeHelper.debug = true;
```

## Future Enhancements

1. **Dynamic Pricing**: Time-based, demand-based pricing
2. **Zone-based**: Different rates for different areas
3. **Vendor-specific**: Custom rates per restaurant
4. **Real-time Updates**: Live calculation updates
5. **Analytics Dashboard**: Detailed reporting

## Support

For issues or questions:
1. Check this documentation
2. Review test cases
3. Check logs for errors
4. Contact development team
