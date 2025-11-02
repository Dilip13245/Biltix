<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Cloud Messaging (FCM) Configuration
    |--------------------------------------------------------------------------
    |
    | Configure FCM for Android push notifications
    | 
    | IMPORTANT: Firebase has deprecated the legacy server key method.
    | The new FCM HTTP v1 API uses OAuth2 authentication.
    |
    | Configuration options:
    | - credentials_path: Path to google-services.json (for project info)
    | - service_account_path: Path to service account JSON (for OAuth2)
    | - project_id: Firebase project ID (auto-loaded from credentials if not set)
    | - server_key: Legacy API key (fallback only, deprecated)
    |
    */
    'fcm' => [
        'credentials_path' => env('FIREBASE_CREDENTIALS_PATH', config_path('firebase-credentials.json')),
        'service_account_path' => env('FIREBASE_SERVICE_ACCOUNT_PATH', storage_path('app/firebase-service-account.json')),
        'project_id' => env('FIREBASE_PROJECT_ID', ''),
        'server_key' => env('FCM_SERVER_KEY', ''), // Deprecated: Legacy API fallback only
        'api_url_v1' => 'https://fcm.googleapis.com/v1/projects/{project_id}/messages:send',
        'api_url_legacy' => 'https://fcm.googleapis.com/fcm/send',
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

