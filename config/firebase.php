<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Firebase settings. You can get your
    | credentials from the Firebase Console.
    |
    */

    'project_id' => env('FIREBASE_PROJECT_ID'),
    'credentials' => env('FIREBASE_CREDENTIALS'),
    'database_url' => env('FIREBASE_DATABASE_URL'),
    
    /*
    |--------------------------------------------------------------------------
    | Firestore Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for Firestore database operations.
    |
    */
    
    'firestore' => [
        'collections' => [
            'catering_requests' => 'catering_requests',
            'email_logs' => 'email_logs',
            'audit_logs' => 'audit_logs',
            'security_logs' => 'security_logs',
            'performance_logs' => 'performance_logs',
        ],
        
        'indexes' => [
            'catering_requests' => [
                'status' => 'status',
                'date' => 'date',
                'created_at' => 'created_at',
                'ip_address' => 'ip_address',
            ],
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Email Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for email notifications.
    |
    */
    
    'email' => [
        'admin_email' => env('CATERING_ADMIN_EMAIL', 'jerry@jippymart.in'),
        'from_email' => env('CATERING_FROM_EMAIL', 'noreply@jippymart.in'),
        'from_name' => env('CATERING_FROM_NAME', 'JippyMart Catering'),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configuration for API rate limiting.
    |
    */
    
    'rate_limiting' => [
        'public_requests' => env('CATERING_RATE_LIMIT_PUBLIC', 5), // requests per minute
        'admin_requests' => env('CATERING_RATE_LIMIT_ADMIN', 60), // requests per minute
        'spam_threshold' => env('CATERING_SPAM_THRESHOLD', 3), // requests per 5 minutes
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    |
    | Configuration for request validation.
    |
    */
    
    'validation' => [
        'max_guests' => env('CATERING_MAX_GUESTS', 10000),
        'max_advance_days' => env('CATERING_MAX_ADVANCE_DAYS', 365),
        'allowed_function_types' => [
            'Wedding', 'Corporate', 'Birthday', 'Anniversary', 'Other'
        ],
        'allowed_meal_preferences' => [
            'veg', 'non_veg', 'both'
        ],
    ],
];