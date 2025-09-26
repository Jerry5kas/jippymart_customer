<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin Panel Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration specific to the admin panel to prevent resource conflicts
    | with the customer panel on shared hosting.
    |
    */

    'firebase' => [
        'timeout' => 10, // Reduced timeout for admin panel
        'max_execution_time' => 10,
        'cache_ttl' => 600, // 10 minutes cache
    ],

    'performance' => [
        'enable_circuit_breaker' => true,
        'fallback_mode' => true,
        'max_memory_usage' => '128M',
    ],

    'rate_limiting' => [
        'enabled' => true,
        'max_requests_per_minute' => 30,
    ],
];
