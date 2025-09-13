<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Optimization Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration settings for optimizing server performance
    | and reducing process usage. Adjust these values based on your server
    | capabilities and requirements.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Client-Side Polling Intervals
    |--------------------------------------------------------------------------
    |
    | These settings control how frequently client-side scripts poll the server
    | for updates. Higher values reduce server load but may make the app feel
    | less responsive.
    |
    */
    'client_polling' => [
        'store_updates' => env('OPT_STORE_UPDATES_INTERVAL', 300000), // 5 minutes
        'restaurant_status' => env('OPT_RESTAURANT_STATUS_INTERVAL', 900000), // 15 minutes
        'data_checks' => env('OPT_DATA_CHECKS_INTERVAL', 1000), // 1 second (for critical checks)
        'category_zone_checks' => env('OPT_CATEGORY_ZONE_CHECKS_INTERVAL', 2000), // 2 seconds
        'carousel_autoplay' => env('OPT_CAROUSEL_AUTOPLAY_INTERVAL', 10000), // 10 seconds
        'navbar_suggestions' => env('OPT_NAVBAR_SUGGESTIONS_INTERVAL', 5000), // 5 seconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Minimum Update Intervals
    |--------------------------------------------------------------------------
    |
    | These settings enforce minimum time between updates to prevent excessive
    | server calls even if the polling interval is set lower.
    |
    */
    'minimum_intervals' => [
        'store_data' => env('OPT_MIN_STORE_DATA_INTERVAL', 30000), // 30 seconds
        'restaurant_status' => env('OPT_MIN_RESTAURANT_STATUS_INTERVAL', 60000), // 1 minute
        'vendor_data' => env('OPT_MIN_VENDOR_DATA_INTERVAL', 60000), // 1 minute
    ],

    /*
    |--------------------------------------------------------------------------
    | Firebase Query Optimization
    |--------------------------------------------------------------------------
    |
    | These settings control Firebase query behavior and caching.
    |
    */
    'firebase' => [
        'cache_time' => env('OPT_FIREBASE_CACHE_TIME', 300000), // 5 minutes
        'batch_size' => env('OPT_FIREBASE_BATCH_SIZE', 100),
        'max_parallel_batches' => env('OPT_FIREBASE_MAX_PARALLEL_BATCHES', 5),
        'enable_intelligent_caching' => env('OPT_FIREBASE_INTELLIGENT_CACHING', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue and Background Processing
    |--------------------------------------------------------------------------
    |
    | These settings control how background tasks are processed.
    |
    */
    'queues' => [
        'default_connection' => env('QUEUE_CONNECTION', 'sync'),
        'max_attempts' => env('OPT_QUEUE_MAX_ATTEMPTS', 3),
        'retry_after' => env('OPT_QUEUE_RETRY_AFTER', 90),
        'timeout' => env('OPT_QUEUE_TIMEOUT', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Query Optimization
    |--------------------------------------------------------------------------
    |
    | These settings control database query behavior.
    |
    */
    'database' => [
        'enable_query_logging' => env('OPT_DB_QUERY_LOGGING', false),
        'max_query_time' => env('OPT_DB_MAX_QUERY_TIME', 30),
        'enable_query_caching' => env('OPT_DB_QUERY_CACHING', true),
        'cache_ttl' => env('OPT_DB_CACHE_TTL', 300), // 5 minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | These settings control various caching mechanisms.
    |
    */
    'cache' => [
        'enable_page_cache' => env('OPT_PAGE_CACHE', true),
        'page_cache_ttl' => env('OPT_PAGE_CACHE_TTL', 1800), // 30 minutes
        'enable_route_cache' => env('OPT_ROUTE_CACHE', true),
        'enable_config_cache' => env('OPT_CONFIG_CACHE', true),
        'enable_view_cache' => env('OPT_VIEW_CACHE', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Monitoring
    |--------------------------------------------------------------------------
    |
    | These settings control performance monitoring and logging.
    |
    */
    'monitoring' => [
        'enable_performance_logging' => env('OPT_PERFORMANCE_LOGGING', false),
        'log_slow_queries' => env('OPT_LOG_SLOW_QUERIES', true),
        'slow_query_threshold' => env('OPT_SLOW_QUERY_THRESHOLD', 1000), // 1 second
        'enable_resource_monitoring' => env('OPT_RESOURCE_MONITORING', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Development vs Production Settings
    |--------------------------------------------------------------------------
    |
    | These settings automatically adjust based on the environment.
    |
    */
    'environment' => [
        'is_production' => env('APP_ENV') === 'production',
        'enable_debug_mode' => env('APP_DEBUG', false),
        'enable_optimizations' => env('APP_ENV') === 'production',
    ],
];
