<?php

namespace App\Traits;

use App\Services\DeliveryChargeService;

trait DeliveryChargeTrait
{
    protected $deliveryChargeService;

    /**
     * Initialize delivery charge service
     */
    protected function initDeliveryChargeService()
    {
        if (!$this->deliveryChargeService) {
            $this->deliveryChargeService = new DeliveryChargeService();
        }
    }

    /**
     * Apply new delivery charge calculation to cart
     */
    protected function applyNewDeliveryChargeToCart($cart)
    {
        $this->initDeliveryChargeService();
        
        // Only apply if new system is enabled
        if (!$this->deliveryChargeService->shouldUseNewDeliverySystem()) {
            return $cart;
        }

        // Get delivery settings (you can fetch from Firebase here)
        $deliverySettings = $this->getDeliverySettings();
        
        // Update cart with new calculation
        return $this->deliveryChargeService->updateCartDeliveryCharge($cart, $deliverySettings);
    }

    /**
     * Get delivery settings from Firebase/Firestore
     */
    protected function getDeliverySettings()
    {
        // This is a placeholder - you would integrate with your Firebase here
        // For now, using default settings
        return [
            'base_delivery_charge' => 23,
            'item_total_threshold' => 299,
            'free_delivery_distance_km' => 7,
            'per_km_charge_above_free_distance' => 8
        ];
    }

    /**
     * Get delivery charge display for cart
     */
    protected function getCartDeliveryChargeDisplay($cart)
    {
        $this->initDeliveryChargeService();
        return $this->deliveryChargeService->getCartDeliveryChargeDisplay($cart, $this->getDeliverySettings());
    }

    /**
     * Calculate delivery charge for specific item total and distance
     */
    protected function calculateDeliveryCharge($itemTotal, $distance)
    {
        $this->initDeliveryChargeService();
        return $this->deliveryChargeService->calculateDeliveryCharge($itemTotal, $distance, $this->getDeliverySettings());
    }

    /**
     * Check if new delivery system should be used
     */
    protected function shouldUseNewDeliverySystem()
    {
        $this->initDeliveryChargeService();
        return $this->deliveryChargeService->shouldUseNewDeliverySystem();
    }
}
