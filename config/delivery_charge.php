<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Delivery Charge Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the new delivery charge system.
    | These settings can be overridden by Firebase/Firestore settings.
    |
    */

    // Enable/disable new delivery charge system
    'enable_new_system' => env('ENABLE_NEW_DELIVERY_CHARGE_SYSTEM', true),

    // Default delivery charge settings
    'default_settings' => [
        'base_delivery_charge' => 23,
        'item_total_threshold' => 299,
        'free_delivery_distance_km' => 7,
        'per_km_charge_above_free_distance' => 8,
    ],

    // Firebase/Firestore configuration
    'firebase' => [
        'collection' => 'settings',
        'document' => 'DeliveryCharge',
        'enabled' => env('FIREBASE_DELIVERY_CHARGE_ENABLED', true),
    ],

    // UI display settings
    'ui' => [
        'show_savings' => true,
        'show_original_price' => true,
        'free_delivery_text' => 'Free Delivery',
        'currency_symbol' => '₹',
    ],

    // Business rules
    'rules' => [
        // Item total < threshold
        'below_threshold' => [
            'description' => 'If item total < threshold: Apply full delivery charge',
            'logic' => 'full_charge',
        ],
        
        // Item total >= threshold, distance <= free distance
        'above_threshold_within_free_distance' => [
            'description' => 'If item total >= threshold AND distance <= free distance: Free delivery',
            'logic' => 'free_delivery',
        ],
        
        // Item total >= threshold, distance > free distance
        'above_threshold_beyond_free_distance' => [
            'description' => 'If item total >= threshold AND distance > free distance: Only charge extra km',
            'logic' => 'extra_km_only',
        ],
    ],

    // Self-delivery settings
    'self_delivery' => [
        'enabled' => true,
        'free_delivery' => true,
        'override_other_rules' => true,
    ],

    // Takeaway settings
    'takeaway' => [
        'enabled' => true,
        'free_delivery' => true,
        'override_other_rules' => true,
    ],
];
