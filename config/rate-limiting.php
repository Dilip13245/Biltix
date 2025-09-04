<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for rate limiting in the API.
    | You can configure different rate limits for different types of requests.
    |
    */

    'api' => [
        'limit' => env('API_RATE_LIMIT', 60),
        'window' => env('API_RATE_LIMIT_WINDOW', 1), // in minutes
    ],

    'auth' => [
        'limit' => 5, // Login attempts per minute
        'window' => 1,
    ],

    'upload' => [
        'limit' => 10, // File uploads per minute
        'window' => 1,
    ],

    'general' => [
        'limit' => 100, // General API requests per minute
        'window' => 1,
    ],
];
