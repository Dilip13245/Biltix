<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Moyasar API Keys
    |--------------------------------------------------------------------------
    */
    'secret_key' => env('MOYASAR_SECRET_KEY', ''),
    'publishable_key' => env('MOYASAR_PUBLISHABLE_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Payment Settings
    |--------------------------------------------------------------------------
    */
    'currency' => env('MOYASAR_CURRENCY', 'SAR'),
    
    // Supported payment methods: creditcard, applepay, stcpay
    'methods' => ['creditcard'],
    
    // Supported card networks: visa, mastercard, mada, amex
    'supported_networks' => ['visa', 'mastercard', 'mada'],

    /*
    |--------------------------------------------------------------------------
    | API Endpoints
    |--------------------------------------------------------------------------
    */
    'api_base_url' => 'https://api.moyasar.com/v1',

    /*
    |--------------------------------------------------------------------------
    | Callback URLs
    |--------------------------------------------------------------------------
    */
    'callback_url' => env('APP_URL') . '/api/v1/payment/callback',
    'web_callback_url' => env('APP_URL') . '/payment/complete',
];
