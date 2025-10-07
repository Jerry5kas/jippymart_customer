<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Shared Hosting Optimization Settings
    |--------------------------------------------------------------------------
    |
    | These settings are specifically designed to prevent 503/508 errors
    | on shared hosting by implementing strict resource limits and
    | disabling resource-intensive features.
    |
    */

    'enabled' => env('SHARED_HOSTING_MODE', true),
    
    'memory_limit' => env('MEMORY_LIMIT', '64M'),
    'max_execution_time' => env('MAX_EXECUTION_TIME', 15),
    
    'firebase' => [
        'query_limit' => env('FIREBASE_QUERY_LIMIT', 20),
        'timeout' => env('FIREBASE_TIMEOUT', 10),
        'memory_limit' => env('FIREBASE_MEMORY_LIMIT', 50),
    ],
    
    'queue' => [
        'connection' => 'sync', // Force sync to prevent background jobs
        'timeout' => 0, // Disable queue workers
    ],
    
    'cache' => [
        'driver' => 'file', // Use file cache instead of Redis
        'lifetime' => 3600, // 1 hour cache
    ],
    
    'disabled_features' => [
        'background_jobs' => true,
        'async_processing' => true,
        'scheduled_tasks' => true,
        'heavy_logging' => true,
        'debug_mode' => true,
    ],
    
    'optimizations' => [
        'force_garbage_collection' => true,
        'limit_database_connections' => true,
        'compress_responses' => true,
        'minimize_memory_usage' => true,
    ],
];
