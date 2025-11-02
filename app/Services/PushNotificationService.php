<?php

namespace App\Services;

use App\Models\UserDevice;
use App\Models\Notification;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class PushNotificationService
{
    private $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Send push notification to user(s)
     *
     * @param array|int $userIds User ID(s) to send notification to
     * @param string $type Notification type
     * @param string $title Notification title
     * @param string $message Notification message
     * @param array $data Additional data payload
     * @param string $priority Priority level (low, medium, high)
     * @return array
     */
    public function send($userIds, $type, $title, $message, $data = [], $priority = 'medium')
    {
        $userIds = is_array($userIds) ? $userIds : [$userIds];
        $results = [];

        Log::info("[FCM] Starting notification send", [
            'user_ids' => $userIds,
            'type' => $type,
            'title' => $title,
            'priority' => $priority
        ]);

        foreach ($userIds as $userId) {
            try {
                Log::info("[FCM] Processing notification for user {$userId}");
                
                // Prepare complete notification payload with deep linking
                $completeData = \App\Helpers\NotificationHelper::prepareNotificationPayload($type, $data);
                
                // Save notification to database
                $notification = Notification::create([
                    'user_id' => $userId,
                    'type' => $type,
                    'title' => $title,
                    'message' => $message,
                    'data' => $completeData, // Store complete payload with deep link
                    'priority' => $priority,
                    'is_read' => false,
                    'is_active' => true,
                    'is_deleted' => false,
                ]);

                Log::info("[FCM] Notification saved to database", [
                    'notification_id' => $notification->id,
                    'deep_link' => $completeData['deep_link'] ?? 'N/A'
                ]);
                
                // Add notification_id to payload after saving
                $completeData['notification_id'] = (string) $notification->id;

                // Get user devices with push tokens
                $devicesQuery = UserDevice::where('user_id', $userId)
                    ->where('is_active', true)
                    ->where('is_deleted', false)
                    ->whereNotNull('device_token');
                
                // Check if push_notification_enabled column exists
                if (Schema::hasColumn('user_devices', 'push_notification_enabled')) {
                    $devicesQuery->where('push_notification_enabled', true);
                }
                
                $devices = $devicesQuery->get();

                Log::info("[FCM] Found {$devices->count()} devices for user {$userId}", [
                    'devices' => $devices->map(function($device) {
                        return [
                            'id' => $device->id,
                            'device_type' => $device->device_type,
                            'has_token' => !empty($device->device_token),
                            'token_preview' => $device->device_token ? substr($device->device_token, 0, 20) . '...' : null
                        ];
                    })
                ]);

                if ($devices->isEmpty()) {
                    Log::warning("[FCM] No active devices found for user {$userId}");
                    $results[$userId] = ['saved' => true, 'pushed' => false, 'reason' => 'no_devices'];
                    continue;
                }

                // Send push to each device
                $pushResults = [];
                foreach ($devices as $device) {
                    Log::info("[FCM] Sending to device {$device->id} (type: {$device->device_type})");
                    // Use complete data payload with deep link and notification_id
                    $pushResult = $this->sendToDevice($device, $title, $message, $completeData, $priority, $type);
                    $pushResults[] = $pushResult;
                    
                    Log::info("[FCM] Device {$device->id} result", $pushResult);
                }

                $results[$userId] = [
                    'saved' => true,
                    'pushed' => true,
                    'devices_count' => $devices->count(),
                    'push_results' => $pushResults,
                ];

                Log::info("[FCM] Completed notification for user {$userId}", $results[$userId]);

            } catch (\Exception $e) {
                Log::error("[FCM] Failed to send notification to user {$userId}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                $results[$userId] = [
                    'saved' => false,
                    'pushed' => false,
                    'error' => $e->getMessage(),
                ];
            }
        }

        Log::info("[FCM] Notification send completed", ['results' => $results]);
        return $results;
    }

    /**
     * Send notification to a specific device
     *
     * @param UserDevice $device
     * @param string $title
     * @param string $message
     * @param array $data
     * @param string $priority
     * @param string $type
     * @return array
     */
    private function sendToDevice($device, $title, $message, $data = [], $priority = 'medium', $type = '')
    {
        try {
            Log::info("[FCM] Sending to device", [
                'device_id' => $device->id,
                'device_type' => $device->device_type,
                'user_id' => $device->user_id,
                'notification_type' => $type
            ]);
            
            if ($device->device_type === 'A') {
                // Android - Send via FCM
                return $this->sendFCM($device->device_token, $title, $message, $data, $priority, $type, 'A');
            } elseif ($device->device_type === 'I') {
                // iOS - Send via APNS
                return $this->sendAPNS($device->device_token, $title, $message, $data, $priority);
            } elseif ($device->device_type === 'W') {
                // Web - Send via FCM Web Push
                $token = $device->fcm_token ?: $device->device_token;
                $webData = $data;
                if (isset($webData['action_url'])) {
                    $webData['click_action'] = $webData['action_url'];
                } elseif (isset($webData['deep_link'])) {
                    $webData['click_action'] = self::convertDeepLinkToWebUrl($webData['deep_link'], $webData);
                }
                return $this->sendFCM($token, $title, $message, $webData, $priority, $type, 'W');
            } else {
                Log::warning("[FCM] Unsupported device type: {$device->device_type}");
                return ['success' => false, 'reason' => 'unsupported_device_type'];
            }
        } catch (\Exception $e) {
            Log::error("[FCM] Failed to send push to device {$device->id}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send push notification via FCM (Android)
     * Uses FCM HTTP v1 API with OAuth2 (preferred) or falls back to legacy API
     *
     * @param string $deviceToken
     * @param string $title
     * @param string $message
     * @param array $data
     * @param string $priority
     * @param string $type
     * @return array
     */
    private function sendFCM($deviceToken, $title, $message, $data = [], $priority = 'medium', $type = '', $deviceType = 'A')
    {
        Log::info("[FCM] Preparing FCM request", [
            'token_preview' => substr($deviceToken, 0, 20) . '...',
            'title' => $title,
            'priority' => $priority,
            'type' => $type,
            'device_type' => $deviceType,
            'has_service_account' => $this->firebaseService->hasServiceAccount()
        ]);

        // Try to use FCM HTTP v1 API first (preferred method)
        if ($this->firebaseService->hasServiceAccount()) {
            return $this->sendFCMv1($deviceToken, $title, $message, $data, $priority, $type, $deviceType);
        }

        // Fallback to legacy API if service account is not available
        return $this->sendFCMLegacy($deviceToken, $title, $message, $data, $priority, $type, $deviceType);
    }

    /**
     * Convert deep link to web URL for web push notifications
     *
     * @param string $deepLink
     * @param array $data
     * @return string
     */
    private static function convertDeepLinkToWebUrl($deepLink, $data = [])
    {
        // If action_url is available, use it
        if (isset($data['action_url'])) {
            return $data['action_url'];
        }
        
        // Parse deep link and convert to web URL
        // Format: biltix://open?screen={screen}&{id}={value}
        if (preg_match('/screen=([^&]+)/', $deepLink, $screenMatch)) {
            $screen = $screenMatch[1];
            
            switch ($screen) {
                case 'task':
                    $taskId = $data['task_id'] ?? null;
                    return $taskId ? "/tasks/{$taskId}" : '/tasks';
                    
                case 'snag':
                    $snagId = $data['snag_id'] ?? null;
                    return $snagId ? "/snags/{$snagId}" : '/snags';
                    
                case 'project':
                    $projectId = $data['project_id'] ?? null;
                    return $projectId ? "/projects/{$projectId}" : '/projects';
                    
                case 'team':
                    $projectId = $data['project_id'] ?? null;
                    return $projectId ? "/projects/{$projectId}/team" : '/projects';
                    
                case 'inspection':
                    $inspectionId = $data['inspection_id'] ?? null;
                    return $inspectionId ? "/inspections/{$inspectionId}" : '/inspections';
                    
                case 'plan':
                    $planId = $data['plan_id'] ?? null;
                    return $planId ? "/plans/{$planId}" : '/plans';
                    
                case 'file':
                    $fileId = $data['file_id'] ?? null;
                    return $fileId ? "/files/{$fileId}" : '/files';
                    
                case 'daily_log':
                    $projectId = $data['project_id'] ?? null;
                    return $projectId ? "/projects/{$projectId}/daily-logs" : '/projects';
                    
                case 'dashboard':
                default:
                    return '/dashboard';
            }
        }
        
        // Fallback to dashboard
        return '/dashboard';
    }

    /**
     * Send push notification using FCM HTTP v1 API (OAuth2)
     *
     * @param string $deviceToken
     * @param string $title
     * @param string $message
     * @param array $data
     * @param string $priority
     * @param string $type
     * @param string $deviceType Device type: A (Android), W (Web)
     * @return array
     */
    private function sendFCMv1($deviceToken, $title, $message, $data = [], $priority = 'medium', $type = '', $deviceType = 'A')
    {
        $accessToken = $this->firebaseService->getAccessToken();
        
        if (empty($accessToken)) {
            Log::error('[FCM] Failed to get OAuth2 access token');
            return ['success' => false, 'reason' => 'oauth_token_failed'];
        }

        $projectId = $this->firebaseService->getProjectId();
        $notificationPriority = $priority === 'high' ? 'high' : 'normal';

        // Prepare data payload (data is already prepared by NotificationHelper with deep link)
        $dataPayload = [];
        foreach ($data as $key => $value) {
            // Ensure all values are strings (FCM requirement)
            $dataPayload[$key] = is_null($value) ? '' : (string) $value;
        }

        // Extract click action from data if provided
        $clickAction = $dataPayload['click_action'] ?? 'FLUTTER_NOTIFICATION_CLICK';
        $channelId = $dataPayload['android_channel_id'] ?? 'default';

        // FCM HTTP v1 API payload structure with deep linking support
        // Note: Deep link is already in data payload (dataPayload['deep_link'])
        // Android app should read from message.data['deep_link'] when handling notification clicks
        $payload = [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $message,
                ],
                'data' => $dataPayload, // Contains deep_link, notification_id, and all other data
            ],
        ];
        
        // Add platform-specific configuration
        if ($deviceType === 'W') {
            // Web push configuration
            $webClickAction = $dataPayload['click_action'] ?? $dataPayload['action_url'] ?? '/dashboard';
            $payload['message']['webpush'] = [
                'notification' => [
                    'title' => $title,
                    'body' => $message,
                    'icon' => '/website/images/icons/logo.svg',
                    'badge' => '/website/images/icons/logo.svg',
                    'tag' => $dataPayload['notification_id'] ?? 'biltix-notification',
                    'requireInteraction' => false,
                    'silent' => false,
                    'vibrate' => [200, 100, 200],
                ],
                'fcm_options' => [
                    'link' => $webClickAction, // Web URL to open when notification is clicked
                ],
            ];
            
            Log::info('[FCM] Added webpush section for web device', [
                'click_action' => $webClickAction
            ]);
        } else {
            // Android configuration
            $payload['message']['android'] = [
                'priority' => $notificationPriority,
                'notification' => [
                    'sound' => 'default',
                    'channel_id' => $channelId,
                    'click_action' => $clickAction, // For handling notification clicks
                ],
            ];
        }

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        Log::info('[FCM] Sending FCM v1 API request', [
            'url' => $url,
            'payload_size' => strlen(json_encode($payload))
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($url, $payload);

            $responseData = $response->json();
            $statusCode = $response->status();

            Log::info('[FCM] FCM v1 Response received', [
                'status_code' => $statusCode,
                'response_data' => $responseData
            ]);

            if ($response->successful() && isset($responseData['name'])) {
                Log::info('[FCM] FCM v1 notification sent successfully', ['message_name' => $responseData['name']]);
                return ['success' => true, 'message_name' => $responseData['name']];
            } else {
                $error = $responseData['error']['message'] ?? $responseData['error'] ?? 'Unknown FCM error';
                Log::error('[FCM] FCM v1 push failed', [
                    'error' => $error,
                    'response_data' => $responseData,
                    'status_code' => $statusCode
                ]);
                return ['success' => false, 'error' => $error, 'response_data' => $responseData];
            }
        } catch (\Exception $e) {
            Log::error('[FCM] FCM v1 push exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send push notification using FCM Legacy API (fallback)
     * 
     * @param string $deviceToken
     * @param string $title
     * @param string $message
     * @param array $data
     * @param string $priority
     * @param string $type
     * @param string $deviceType Device type: A (Android), W (Web)
     * @return array
     */
    private function sendFCMLegacy($deviceToken, $title, $message, $data = [], $priority = 'medium', $type = '', $deviceType = 'A')
    {
        // NOTE: The API key in google-services.json is a CLIENT API KEY, not a SERVER KEY
        // Client API keys cannot be used with FCM Legacy API. You need either:
        // 1. A Service Account JSON (for FCM HTTP v1 API) - RECOMMENDED
        // 2. A Server Key from Firebase Console (for Legacy API) - DEPRECATED
        
        // Try to get Server Key from environment variable first (this is the correct one for Legacy API)
        $apiKey = env('FCM_SERVER_KEY');
        
        // If not in env, try to load from JSON (but warn it might not work)
        if (empty($apiKey)) {
            $credentialsPath = config_path('firebase-credentials.json');
            if (file_exists($credentialsPath)) {
                $credentials = json_decode(file_get_contents($credentialsPath), true);
                if (isset($credentials['client'][0]['api_key'][0]['current_key'])) {
                    $apiKey = $credentials['client'][0]['api_key'][0]['current_key'];
                    Log::warning('[FCM] Using client API key from JSON - this may not work with Legacy API. Please use FCM_SERVER_KEY in .env or get a Service Account JSON for HTTP v1 API.');
                }
            }
        }

        if (empty($apiKey)) {
            Log::error('[FCM] No server key configured. Please set FCM_SERVER_KEY in .env or add a Service Account JSON file.');
            return ['success' => false, 'reason' => 'fcm_not_configured', 'message' => 'No server key found. Use FCM_SERVER_KEY in .env or Service Account JSON for HTTP v1 API.'];
        }
        
        Log::info('[FCM] Using Legacy API with server key', [
            'key_preview' => substr($apiKey, 0, 10) . '...' . substr($apiKey, -10)
        ]);

        $notificationPriority = $priority === 'high' ? 'high' : 'normal';
        
        // Prepare data payload (data is already prepared by NotificationHelper with deep link)
        $dataPayload = [];
        foreach ($data as $key => $value) {
            // Ensure all values are strings (FCM requirement)
            $dataPayload[$key] = is_null($value) ? '' : (string) $value;
        }
        
        // Extract click action and deep link
        $clickAction = $dataPayload['click_action'] ?? 'FLUTTER_NOTIFICATION_CLICK';
        $deepLink = $dataPayload['deep_link'] ?? '';
        
        $payload = [
            'to' => $deviceToken,
            'notification' => [
                'title' => $title,
                'body' => $message,
                'sound' => 'default',
                'click_action' => $clickAction, // For handling notification clicks
            ],
            'data' => $dataPayload,
            'priority' => $notificationPriority,
        ];
        
        // Add deep link if available (for Android intent)
        if (!empty($deepLink)) {
            $payload['data']['deep_link'] = $deepLink;
        }

        $url = 'https://fcm.googleapis.com/fcm/send';
        
        Log::info('[FCM] Sending FCM Legacy API request', [
            'url' => $url,
            'payload_size' => strlen(json_encode($payload))
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'key=' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($url, $payload);

            $responseData = $response->json();
            $statusCode = $response->status();

            Log::info('[FCM] FCM Legacy Response received', [
                'status_code' => $statusCode,
                'response_data' => $responseData,
                'raw_response' => $response->body() // Log raw response for debugging
            ]);

            // Handle different HTTP status codes
            if ($statusCode === 404) {
                $errorMsg = 'FCM endpoint not found. The API key in google-services.json is a CLIENT API KEY and cannot be used with Legacy API. You need to either: 1) Set FCM_SERVER_KEY in .env, or 2) Add a Service Account JSON file for HTTP v1 API.';
                Log::error('[FCM] FCM Legacy push failed - 404', [
                    'error' => $errorMsg,
                    'status_code' => $statusCode,
                    'response_data' => $responseData
                ]);
                return ['success' => false, 'error' => $errorMsg, 'response_data' => $responseData, 'status_code' => $statusCode];
            }

            if ($statusCode === 401) {
                $errorMsg = 'FCM authentication failed. Invalid server key. Please check your FCM_SERVER_KEY in .env.';
                Log::error('[FCM] FCM Legacy push failed - 401 Unauthorized', [
                    'error' => $errorMsg,
                    'status_code' => $statusCode,
                    'response_data' => $responseData
                ]);
                return ['success' => false, 'error' => $errorMsg, 'response_data' => $responseData, 'status_code' => $statusCode];
            }

            if ($response->successful() && isset($responseData['success']) && $responseData['success'] == 1) {
                $messageId = $responseData['results'][0]['message_id'] ?? null;
                Log::info('[FCM] FCM Legacy notification sent successfully', ['message_id' => $messageId]);
                return ['success' => true, 'message_id' => $messageId];
            } else {
                $error = $responseData['results'][0]['error'] ?? $responseData['error'] ?? ($responseData['error']['message'] ?? 'Unknown FCM error');
                Log::error('[FCM] FCM Legacy push failed', [
                    'error' => $error,
                    'response_data' => $responseData,
                    'status_code' => $statusCode
                ]);
                return ['success' => false, 'error' => $error, 'response_data' => $responseData, 'status_code' => $statusCode];
            }
        } catch (\Exception $e) {
            Log::error('[FCM] FCM Legacy push exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send push notification via APNS (iOS)
     *
     * @param string $deviceToken
     * @param string $title
     * @param string $message
     * @param array $data
     * @param string $priority
     * @return array
     */
    private function sendAPNS($deviceToken, $title, $message, $data = [], $priority = 'medium')
    {
        $apnsConfig = Config::get('push.apns');
        $certificatePath = $apnsConfig['certificate_path'];

        if (empty($certificatePath) || !file_exists($certificatePath)) {
            Log::warning('APNS Certificate path is not configured or file does not exist');
            return ['success' => false, 'reason' => 'apns_not_configured'];
        }

        try {
            // Create APNS payload
            $aps = [
                'alert' => [
                    'title' => $title,
                    'body' => $message,
                ],
                'sound' => 'default',
                'badge' => 1,
            ];

            $payload = json_encode([
                'aps' => $aps,
                'data' => $data,
            ]);

            // Open connection to APNS
            $ctx = stream_context_create();
            stream_context_set_option($ctx, 'ssl', 'local_cert', $certificatePath);
            
            if (!empty($apnsConfig['passphrase'])) {
                stream_context_set_option($ctx, 'ssl', 'passphrase', $apnsConfig['passphrase']);
            }

            $fp = stream_socket_client(
                $apnsConfig['apns_url'],
                $err,
                $errstr,
                60,
                STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT,
                $ctx
            );

            if (!$fp) {
                return ['success' => false, 'error' => "Failed to connect: {$err} {$errstr}"];
            }

            // Build binary notification
            $deviceTokenBinary = hex2bin(str_replace(' ', '', $deviceToken));
            $payloadLength = strlen($payload);

            $message = chr(0) . // Command (0 = simple notification)
                       pack('n', 32) . // Device token length
                       $deviceTokenBinary . // Device token
                       pack('n', $payloadLength) . // Payload length
                       $payload; // Payload

            // Send notification
            $result = fwrite($fp, $message, strlen($message));

            if ($result) {
                fclose($fp);
                return ['success' => true];
            } else {
                fclose($fp);
                return ['success' => false, 'error' => 'Failed to send notification'];
            }
        } catch (\Exception $e) {
            Log::error("APNS push exception: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send notification to all project team members
     *
     * @param int $projectId
     * @param string $type
     * @param string $title
     * @param string $message
     * @param array $data
     * @param string $priority
     * @return array
     */
    public function sendToProjectTeam($projectId, $type, $title, $message, $data = [], $priority = 'medium')
    {
        $teamMembers = \App\Models\TeamMember::where('project_id', $projectId)
            ->where('is_active', true)
            ->where('is_deleted', false)
            ->pluck('user_id')
            ->toArray();

        // Also add project manager and technical engineer
        $project = \App\Models\Project::where('id', $projectId)->first();
        if ($project) {
            if ($project->project_manager_id && !in_array($project->project_manager_id, $teamMembers)) {
                $teamMembers[] = $project->project_manager_id;
            }
            if ($project->technical_engineer_id && !in_array($project->technical_engineer_id, $teamMembers)) {
                $teamMembers[] = $project->technical_engineer_id;
            }
        }

        if (empty($teamMembers)) {
            return [];
        }

        return $this->send($teamMembers, $type, $title, $message, $data, $priority);
    }

    /**
     * Send notification excluding specific user(s)
     *
     * @param array $userIds
     * @param array $excludeUserIds
     * @param string $type
     * @param string $title
     * @param string $message
     * @param array $data
     * @param string $priority
     * @return array
     */
    public function sendExcluding($userIds, $excludeUserIds, $type, $title, $message, $data = [], $priority = 'medium')
    {
        $excludeUserIds = is_array($excludeUserIds) ? $excludeUserIds : [$excludeUserIds];
        $filteredUserIds = array_diff($userIds, $excludeUserIds);
        
        if (empty($filteredUserIds)) {
            return [];
        }

        return $this->send($filteredUserIds, $type, $title, $message, $data, $priority);
    }


}

