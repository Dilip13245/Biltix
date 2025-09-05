<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for API calling setup
    |
    */

    'base_url' => env('API_BASE_URL', env('APP_URL') . '/api'),
    'timeout' => env('API_TIMEOUT', 30),
    'retry_attempts' => env('API_RETRY_ATTEMPTS', 3),
    'retry_delay' => env('API_RETRY_DELAY', 1000), // milliseconds

    /*
    |--------------------------------------------------------------------------
    | API Endpoints
    |--------------------------------------------------------------------------
    */
    'endpoints' => [
        'auth' => [
            'login' => 'v1/auth/login',
            'signup' => 'v1/auth/signup',
            'logout' => 'v1/auth/logout',
            'profile' => 'v1/auth/get_user_profile',
            'update_profile' => 'v1/auth/update_profile',
            'send_otp' => 'v1/auth/send_otp',
            'verify_otp' => 'v1/auth/verify_otp',
            'reset_password' => 'v1/auth/reset_password',
        ],
        'projects' => [
            'list' => 'v1/projects/list',
            'create' => 'v1/projects/create',
            'details' => 'v1/projects/details',
            'update' => 'v1/projects/update',
            'delete' => 'v1/projects/delete',
            'dashboard_stats' => 'v1/projects/dashboard_stats',
        ],
        'tasks' => [
            'list' => 'v1/tasks/list',
            'create' => 'v1/tasks/create',
            'details' => 'v1/tasks/details',
            'update' => 'v1/tasks/update',
            'delete' => 'v1/tasks/delete',
            'change_status' => 'v1/tasks/change_status',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Response Codes
    |--------------------------------------------------------------------------
    */
    'response_codes' => [
        'success' => 200,
        'error' => 400,
        'unauthorized' => 401,
        'forbidden' => 403,
        'not_found' => 404,
        'validation_error' => 422,
        'server_error' => 500,
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Headers
    |--------------------------------------------------------------------------
    */
    'default_headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'User-Agent' => 'Biltix-Website/1.0',
    ],
];