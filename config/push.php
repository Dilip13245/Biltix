<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Cloud Messaging (FCM) Configuration
    |--------------------------------------------------------------------------
    |
    | Configure FCM for Android push notifications
    |
    */
    'fcm' => [
        'server_key' => env('FCM_SERVER_KEY', ''),
        'api_url' => 'https://fcm.googleapis.com/fcm/send',
        'timeout' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Apple Push Notification Service (APNS) Configuration
    |--------------------------------------------------------------------------
    |
    | Configure APNS for iOS push notifications
    |
    */
    'apns' => [
        'certificate_path' => env('APNS_CERTIFICATE_PATH', ''),
        'passphrase' => env('APNS_PASSPHRASE', ''),
        'environment' => env('APNS_ENVIRONMENT', 'sandbox'), // sandbox or production
        'apns_url' => env('APNS_ENVIRONMENT', 'sandbox') === 'production' 
            ? 'ssl://gateway.push.apple.com:2195'
            : 'ssl://gateway.sandbox.push.apple.com:2195',
    ],

    /*
    |--------------------------------------------------------------------------
    | Push Notification Settings
    |--------------------------------------------------------------------------
    |
    | General settings for push notifications
    |
    */
    'settings' => [
        'enabled' => env('PUSH_NOTIFICATIONS_ENABLED', true),
        'batch_size' => 100, // Number of notifications to send in one batch
        'retry_attempts' => 3, // Number of retry attempts for failed notifications
        'priority_delay' => [
            'high' => 0, // Immediate
            'medium' => 5, // 5 seconds delay
            'low' => 60, // 1 minute delay
        ],
    ],
];

