<?php

namespace App\Services;

class DeliveryChargeService
{
    /**
     * Calculate delivery charge based on new business rules
     */
    public function calculateDeliveryCharge($itemTotal, $distance, $deliverySettings = null)
    {
        // Default settings from Firestore
        $defaultSettings = [
            'base_delivery_charge' => 23,
            'item_total_threshold' => 299,
            'free_delivery_distance_km' => 7,
            'per_km_charge_above_free_distance' => 8
        ];

        // Merge with provided settings
        $settings = array_merge($defaultSettings, $deliverySettings ?? []);

        // Extract settings
        $baseDeliveryCharge = $settings['base_delivery_charge'];
        $itemTotalThreshold = $settings['item_total_threshold'];
        $freeDeliveryDistanceKm = $settings['free_delivery_distance_km'];
        $perKmChargeAboveFreeDistance = $settings['per_km_charge_above_free_distance'];

        // Calculate original fee (what would be charged without free delivery)
        $originalFee = $this->calculateOriginalFee($distance, $baseDeliveryCharge, $freeDeliveryDistanceKm, $perKmChargeAboveFreeDistance);

        // Calculate actual fee based on business rules
        $actualFee = $this->calculateActualFee($itemTotal, $distance, $settings);

        $calculation = [
            'original_fee' => $originalFee,
            'actual_fee' => $actualFee,
            'is_free_delivery' => $this->isFreeDelivery($itemTotal, $distance, $settings),
            'savings' => $originalFee - $actualFee,
            'settings' => $settings,
            'distance' => $distance,
            'item_total' => $itemTotal
        ];

        return array_merge($calculation, [
            'ui_components' => $this->getUIComponents($calculation)
        ]);
    }

    /**
     * Calculate original fee (what would be charged without free delivery)
     */
    private function calculateOriginalFee($distance, $baseDeliveryCharge, $freeDeliveryDistanceKm, $perKmChargeAboveFreeDistance)
    {
        if ($distance <= $freeDeliveryDistanceKm) {
            return $baseDeliveryCharge;
        } else {
            $extraDistance = $distance - $freeDeliveryDistanceKm;
            return $baseDeliveryCharge + ($extraDistance * $perKmChargeAboveFreeDistance);
        }
    }

    /**
     * Calculate actual fee based on business rules
     */
    private function calculateActualFee($itemTotal, $distance, $settings)
    {
        $baseDeliveryCharge = $settings['base_delivery_charge'];
        $itemTotalThreshold = $settings['item_total_threshold'];
        $freeDeliveryDistanceKm = $settings['free_delivery_distance_km'];
        $perKmChargeAboveFreeDistance = $settings['per_km_charge_above_free_distance'];

        // If item total is below threshold
        if ($itemTotal < $itemTotalThreshold) {
            return $this->calculateOriginalFee($distance, $baseDeliveryCharge, $freeDeliveryDistanceKm, $perKmChargeAboveFreeDistance);
        }

        // If item total is above threshold
        if ($distance <= $freeDeliveryDistanceKm) {
            return 0; // Free delivery within free distance
        } else {
            // Only charge for extra distance above free delivery distance
            $extraDistance = $distance - $freeDeliveryDistanceKm;
            return $extraDistance * $perKmChargeAboveFreeDistance;
        }
    }

    /**
     * Check if delivery is free
     */
    private function isFreeDelivery($itemTotal, $distance, $settings)
    {
        $itemTotalThreshold = $settings['item_total_threshold'];
        $freeDeliveryDistanceKm = $settings['free_delivery_distance_km'];

        return $itemTotal >= $itemTotalThreshold && $distance <= $freeDeliveryDistanceKm;
    }

    /**
     * Get delivery charge display information for UI
     */
    public function getDeliveryChargeDisplay($itemTotal, $distance, $deliverySettings = null)
    {
        $calculation = $this->calculateDeliveryCharge($itemTotal, $distance, $deliverySettings);
        
        $display = [
            'original_fee' => $calculation['original_fee'],
            'actual_fee' => $calculation['actual_fee'],
            'is_free_delivery' => $calculation['is_free_delivery'],
            'savings' => $calculation['savings'],
            'show_strikethrough' => $calculation['savings'] > 0,
            'display_text' => $this->getDisplayText($calculation),
            'ui_components' => $this->getUIComponents($calculation)
        ];

        return $display;
    }

    /**
     * Get display text for UI
     */
    private function getDisplayText($calculation)
    {
        if ($calculation['actual_fee'] == 0) {
            return 'Free Delivery';
        } else {
            return '₹' . number_format($calculation['actual_fee'], 2);
        }
    }

    /**
     * Get UI components for different display scenarios
     */
    private function getUIComponents($calculation)
    {
        $components = [];

        // Scenario 1: Normal fee (no savings)
        if ($calculation['savings'] == 0) {
            $components['type'] = 'normal';
            $components['main_text'] = '₹' . number_format($calculation['actual_fee'], 2);
            $components['sub_text'] = '';
            $components['strikethrough'] = false;
        }
        // Scenario 2: Free delivery (≤ 7km, ≥ ₹299)
        elseif ($calculation['is_free_delivery']) {
            $components['type'] = 'free_delivery';
            $components['main_text'] = 'Free Delivery';
            $components['sub_text'] = '₹' . number_format($calculation['original_fee'], 2);
            $components['strikethrough'] = true;
            $components['charged_amount'] = '₹0.00';
        }
        // Scenario 3: Extra distance (≥ ₹299, > 7km)
        else {
            $components['type'] = 'extra_distance';
            $components['main_text'] = 'Free Delivery';
            $components['sub_text'] = '₹' . number_format($calculation['original_fee'], 2);
            $components['strikethrough'] = true;
            $components['charged_amount'] = '₹' . number_format($calculation['actual_fee'], 2);
        }

        return $components;
    }

    /**
     * Get settings from Firestore (placeholder for Firebase integration)
     */
    public function getDeliverySettings()
    {
        // Get default settings from config
        $defaultSettings = config('delivery_charge.default_settings', [
            'base_delivery_charge' => 23,
            'item_total_threshold' => 299,
            'free_delivery_distance_km' => 7,
            'per_km_charge_above_free_distance' => 8
        ]);

        // TODO: Integrate with Firebase/Firestore here
        // If Firebase integration is enabled, fetch settings from there
        if (config('delivery_charge.firebase.enabled', false)) {
            // $firebaseSettings = $this->getFirebaseSettings();
            // return array_merge($defaultSettings, $firebaseSettings);
        }

        return $defaultSettings;
    }

    /**
     * Update cart with new delivery charge calculation
     * This method maintains backward compatibility
     */
    public function updateCartDeliveryCharge($cart, $deliverySettings = null)
    {
        // Calculate item total
        $itemTotal = $this->calculateCartItemTotal($cart);
        
        // Get distance from cart
        $distance = $cart['deliverykm'] ?? 0;
        
        // Calculate new delivery charge
        $calculation = $this->calculateDeliveryCharge($itemTotal, $distance, $deliverySettings);
        
        // Update cart with new values (maintaining backward compatibility)
        $cart['deliverycharge'] = $calculation['actual_fee'];
        $cart['deliverychargemain'] = $calculation['original_fee'];
        $cart['delivery_charge_calculation'] = $calculation;
        
        return $cart;
    }

    /**
     * Calculate total item value from cart
     */
    private function calculateCartItemTotal($cart)
    {
        $total = 0;
        
        if (isset($cart['item']) && is_array($cart['item'])) {
            foreach ($cart['item'] as $restaurantItems) {
                if (is_array($restaurantItems)) {
                    foreach ($restaurantItems as $item) {
                        $basePrice = floatval($item['item_price'] ?? 0);
                        $extraPrice = floatval($item['extra_price'] ?? 0);
                        $quantity = floatval($item['quantity'] ?? 1);
                        
                        $total += ($basePrice + $extraPrice) * $quantity;
                    }
                }
            }
        }
        
        return $total;
    }

    /**
     * Check if new delivery system should be used
     */
    public function shouldUseNewDeliverySystem()
    {
        return config('delivery_charge.enable_new_system', true);
    }

    /**
     * Get delivery charge for display in cart
     */
    public function getCartDeliveryChargeDisplay($cart, $deliverySettings = null)
    {
        if (!$this->shouldUseNewDeliverySystem()) {
            return null; // Use old system
        }

        $itemTotal = $this->calculateCartItemTotal($cart);
        $distance = $cart['deliverykm'] ?? 0;
        
        return $this->getDeliveryChargeDisplay($itemTotal, $distance, $deliverySettings);
    }

    /**
     * Validate and ensure coordinates are available for delivery charge calculation
     */
    public function validateCoordinates($userLat, $userLng, $restaurantLat, $restaurantLng)
    {
        $validation = [
            'user_coordinates_valid' => false,
            'restaurant_coordinates_valid' => false,
            'distance_can_be_calculated' => false,
            'issues' => []
        ];

        // Validate user coordinates
        if ($userLat && $userLng && is_numeric($userLat) && is_numeric($userLng)) {
            $validation['user_coordinates_valid'] = true;
        } else {
            $validation['issues'][] = 'User coordinates are missing or invalid';
        }

        // Validate restaurant coordinates
        if ($restaurantLat && $restaurantLng && is_numeric($restaurantLat) && is_numeric($restaurantLng)) {
            $validation['restaurant_coordinates_valid'] = true;
        } else {
            $validation['issues'][] = 'Restaurant coordinates are missing or invalid';
        }

        // Check if distance can be calculated
        if ($validation['user_coordinates_valid'] && $validation['restaurant_coordinates_valid']) {
            $validation['distance_can_be_calculated'] = true;
        }

        return $validation;
    }

    /**
     * Get restaurant coordinates from Firebase or fallback
     */
    public function getRestaurantCoordinates($vendorId, $fallbackLat = null, $fallbackLng = null)
    {
        // This would typically fetch from Firebase
        // For now, return fallback coordinates
        return [
            'latitude' => $fallbackLat ?? '15.4865041',
            'longitude' => $fallbackLng ?? '80.0499408',
            'source' => 'fallback'
        ];
    }
}
