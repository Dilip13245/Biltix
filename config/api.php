<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the API environment including
    | security settings, rate limiting, and other API-specific configurations.
    |
    */

    'version' => env('API_VERSION', 'v1'),
    'default_version' => env('API_DEFAULT_VERSION', 'v1'),

    /*
    |--------------------------------------------------------------------------
    | API Security Configuration
    |--------------------------------------------------------------------------
    */

    'security' => [
        'api_key' => env('API_KEY'),
        'secret' => env('APP_SECRET'),
        'iv' => env('APP_IV'),
        'encryption_enabled' => env('ENCRYPTION_ENABLED', 0),
        'guest_token' => env('GUESTTOKEN'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Configuration
    |--------------------------------------------------------------------------
    */

    'rate_limiting' => [
        'enabled' => true,
        'limit' => env('API_RATE_LIMIT', 60),
        'window' => env('API_RATE_LIMIT_WINDOW', 1), // in minutes
        'throttle_key' => 'api',
    ],

    /*
    |--------------------------------------------------------------------------
    | CORS Configuration
    |--------------------------------------------------------------------------
    */

    'cors' => [
        'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', '*')),
        'allowed_methods' => explode(',', env('CORS_ALLOWED_METHODS', 'GET,POST,PUT,DELETE,OPTIONS')),
        'allowed_headers' => explode(',', env('CORS_ALLOWED_HEADERS', '*')),
        'supports_credentials' => env('CORS_SUPPORTS_CREDENTIALS', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Configuration
    |--------------------------------------------------------------------------
    */

    'file_upload' => [
        'max_size' => env('MAX_FILE_SIZE', 10240), // in KB
        'allowed_types' => explode(',', env('ALLOWED_FILE_TYPES', 'jpg,jpeg,png,pdf,doc,docx,xls,xlsx')),
        'storage_disk' => env('FILESYSTEM_DISK', 'local'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Headers Configuration
    |--------------------------------------------------------------------------
    */

    'security_headers' => [
        'enabled' => env('SECURITY_HEADERS_ENABLED', true),
        'hsts_max_age' => env('HSTS_MAX_AGE', 31536000),
        'content_security_policy' => env('CONTENT_SECURITY_POLICY', "default-src 'self'"),
    ],

    /*
    |--------------------------------------------------------------------------
    | Monitoring & Logging Configuration
    |--------------------------------------------------------------------------
    */

    'monitoring' => [
        'enable_api_logging' => env('ENABLE_API_LOGGING', true),
        'enable_performance_monitoring' => env('ENABLE_PERFORMANCE_MONITORING', true),
        'slow_query_threshold' => env('SLOW_QUERY_THRESHOLD', 1000), // in milliseconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Response Configuration
    |--------------------------------------------------------------------------
    */

    'response' => [
        'default_format' => 'json',
        'include_meta' => true,
        'meta_fields' => [
            'timestamp',
            'version',
            'request_id',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination Configuration
    |--------------------------------------------------------------------------
    */

    'pagination' => [
        'default_per_page' => 15,
        'max_per_page' => 100,
        'per_page_param' => 'per_page',
        'page_param' => 'page',
    ],

    /*
    |--------------------------------------------------------------------------
    | API Status Codes
    |--------------------------------------------------------------------------
    */

    'status_codes' => [
        'success' => 200,
        'created' => 201,
        'accepted' => 202,
        'no_content' => 204,
        'bad_request' => 400,
        'unauthorized' => 401,
        'forbidden' => 403,
        'not_found' => 404,
        'method_not_allowed' => 405,
        'conflict' => 409,
        'unprocessable_entity' => 422,
        'too_many_requests' => 429,
        'internal_server_error' => 500,
        'service_unavailable' => 503,
    ],
];
