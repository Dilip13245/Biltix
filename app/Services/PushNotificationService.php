<?php

namespace App\Services;

use App\Models\UserDevice;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class PushNotificationService
{
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

        foreach ($userIds as $userId) {
            try {
                // Save notification to database
                $notification = Notification::create([
                    'user_id' => $userId,
                    'type' => $type,
                    'title' => $title,
                    'message' => $message,
                    'data' => $data,
                    'priority' => $priority,
                    'is_read' => false,
                    'is_active' => true,
                    'is_deleted' => false,
                ]);

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

                if ($devices->isEmpty()) {
                    Log::info("No active devices found for user {$userId}");
                    $results[$userId] = ['saved' => true, 'pushed' => false, 'reason' => 'no_devices'];
                    continue;
                }

                // Send push to each device
                $pushResults = [];
                foreach ($devices as $device) {
                    $pushResult = $this->sendToDevice($device, $title, $message, $data, $priority);
                    $pushResults[] = $pushResult;
                }

                $results[$userId] = [
                    'saved' => true,
                    'pushed' => true,
                    'devices_count' => $devices->count(),
                    'push_results' => $pushResults,
                ];

            } catch (\Exception $e) {
                Log::error("Failed to send notification to user {$userId}: " . $e->getMessage());
                $results[$userId] = [
                    'saved' => false,
                    'pushed' => false,
                    'error' => $e->getMessage(),
                ];
            }
        }

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
     * @return array
     */
    private function sendToDevice($device, $title, $message, $data = [], $priority = 'medium')
    {
        try {
            if ($device->device_type === 'A') {
                // Android - Send via FCM
                return $this->sendFCM($device->device_token, $title, $message, $data, $priority);
            } elseif ($device->device_type === 'I') {
                // iOS - Send via APNS
                return $this->sendAPNS($device->device_token, $title, $message, $data, $priority);
            } else {
                return ['success' => false, 'reason' => 'unsupported_device_type'];
            }
        } catch (\Exception $e) {
            Log::error("Failed to send push to device {$device->id}: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send push notification via FCM (Android)
     *
     * @param string $deviceToken
     * @param string $title
     * @param string $message
     * @param array $data
     * @param string $priority
     * @return array
     */
    private function sendFCM($deviceToken, $title, $message, $data = [], $priority = 'medium')
    {
        $fcmConfig = Config::get('push.fcm');
        $serverKey = $fcmConfig['server_key'];

        if (empty($serverKey)) {
            Log::warning('FCM Server Key is not configured');
            return ['success' => false, 'reason' => 'fcm_not_configured'];
        }

        $notificationPriority = $priority === 'high' ? 'high' : 'normal';
        
        // Prepare data payload
        $dataPayload = array_merge($data, [
            'notification_type' => $type,
            'priority' => $priority,
        ]);
        
        $payload = [
            'to' => $deviceToken,
            'notification' => [
                'title' => $title,
                'body' => $message,
                'sound' => 'default',
                'badge' => 1,
            ],
            'data' => $dataPayload,
            'priority' => $notificationPriority,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'key=' . $serverKey,
                'Content-Type' => 'application/json',
            ])->timeout($fcmConfig['timeout'])->post($fcmConfig['api_url'], $payload);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['success']) && $responseData['success'] == 1) {
                return ['success' => true, 'message_id' => $responseData['results'][0]['message_id'] ?? null];
            } else {
                $error = $responseData['results'][0]['error'] ?? 'Unknown FCM error';
                Log::warning("FCM push failed: {$error}");
                return ['success' => false, 'error' => $error];
            }
        } catch (\Exception $e) {
            Log::error("FCM push exception: " . $e->getMessage());
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

