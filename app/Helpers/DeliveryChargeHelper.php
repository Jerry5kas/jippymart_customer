<?php

namespace App\Helpers;

class DeliveryChargeHelper
{
    /**
     * Get delivery charge settings from configuration
     */
    public static function getDeliverySettings()
    {
        // This can be enhanced to fetch from Firebase/Firestore
        // For now, using default settings
        return [
            'base_delivery_charge' => 23,
            'item_total_threshold' => 299,
            'free_delivery_distance_km' => 7,
            'per_km_charge_above_free_distance' => 8,
            'vendor_can_modify' => false
        ];
    }

    /**
     * Check if new delivery system is enabled
     */
    public static function isNewDeliverySystemEnabled()
    {
        // You can add a configuration flag here
        return true; // Set to false to disable new system
    }

    /**
     * Get delivery charge display data
     */
    public static function getDeliveryChargeDisplay($itemTotal, $distance, $deliverySettings = null)
    {
        if (!$deliverySettings) {
            $deliverySettings = self::getDeliverySettings();
        }

        $baseDeliveryCharge = $deliverySettings['base_delivery_charge'];
        $itemTotalThreshold = $deliverySettings['item_total_threshold'];
        $freeDeliveryDistanceKm = $deliverySettings['free_delivery_distance_km'];
        $perKmChargeAboveFreeDistance = $deliverySettings['per_km_charge_above_free_distance'];

        // Calculate original fee
        if ($distance <= $freeDeliveryDistanceKm) {
            $originalFee = $baseDeliveryCharge;
        } else {
            $extraDistance = $distance - $freeDeliveryDistanceKm;
            $originalFee = $baseDeliveryCharge + ($extraDistance * $perKmChargeAboveFreeDistance);
        }

        // Calculate actual fee
        if ($itemTotal < $itemTotalThreshold) {
            $actualFee = $originalFee;
            $isFreeDelivery = false;
        } else {
            if ($distance <= $freeDeliveryDistanceKm) {
                $actualFee = 0;
                $isFreeDelivery = true;
            } else {
                $extraDistance = $distance - $freeDeliveryDistanceKm;
                $actualFee = $extraDistance * $perKmChargeAboveFreeDistance;
                $isFreeDelivery = false;
            }
        }

        return [
            'original_fee' => $originalFee,
            'actual_fee' => $actualFee,
            'is_free_delivery' => $isFreeDelivery,
            'savings' => $originalFee - $actualFee,
            'show_strikethrough' => $originalFee > $actualFee,
            'display_text' => $actualFee == 0 ? 'Free Delivery' : '₹' . number_format($actualFee, 2)
        ];
    }

    /**
     * Format delivery charge for display
     */
    public static function formatDeliveryCharge($displayData)
    {
        if ($displayData['is_free_delivery']) {
            return [
                'type' => 'free_delivery',
                'text' => 'Free Delivery',
                'original_fee' => $displayData['original_fee'],
                'actual_fee' => 0
            ];
        } elseif ($displayData['show_strikethrough']) {
            return [
                'type' => 'partial_free',
                'text' => 'Free Delivery',
                'original_fee' => $displayData['original_fee'],
                'actual_fee' => $displayData['actual_fee']
            ];
        } else {
            return [
                'type' => 'normal',
                'text' => '₹' . number_format($displayData['actual_fee'], 2),
                'actual_fee' => $displayData['actual_fee']
            ];
        }
    }
}
