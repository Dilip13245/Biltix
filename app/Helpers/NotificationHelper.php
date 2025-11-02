<?php

namespace App\Helpers;

use App\Services\PushNotificationService;
use App\Models\Notification;
use App\Models\Project;
use App\Models\TeamMember;
use Illuminate\Support\Facades\Log;

class NotificationHelper
{
    /**
     * Send notification and push to user(s)
     *
     * @param array|int $userIds
     * @param string $type
     * @param string $title
     * @param string $message
     * @param array $data
     * @param string $priority
     * @return array
     */
    public static function send($userIds, $type, $title, $message, $data = [], $priority = 'medium')
    {
        try {
            // Use Laravel's service container to resolve dependencies automatically
            $pushService = app(PushNotificationService::class);
            return $pushService->send($userIds, $type, $title, $message, $data, $priority);
        } catch (\Exception $e) {
            Log::error("NotificationHelper::send failed: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Send notification to project team members
     *
     * @param int $projectId
     * @param string $type
     * @param string $title
     * @param string $message
     * @param array $data
     * @param string $priority
     * @param array $excludeUserIds Users to exclude from notification
     * @return array
     */
    public static function sendToProjectTeam($projectId, $type, $title, $message, $data = [], $priority = 'medium', $excludeUserIds = [])
    {
        try {
            // Use Laravel's service container to resolve dependencies automatically
            $pushService = app(PushNotificationService::class);
            
            // Get all team members
            $teamMembers = TeamMember::where('project_id', $projectId)
                ->where('is_active', true)
                ->where('is_deleted', false)
                ->pluck('user_id')
                ->toArray();

            // Add project manager and technical engineer
            $project = Project::where('id', $projectId)->first();
            if ($project) {
                if ($project->project_manager_id && !in_array($project->project_manager_id, $teamMembers)) {
                    $teamMembers[] = $project->project_manager_id;
                }
                if ($project->technical_engineer_id && !in_array($project->technical_engineer_id, $teamMembers)) {
                    $teamMembers[] = $project->technical_engineer_id;
                }
            }

            // Exclude specified users
            if (!empty($excludeUserIds)) {
                $excludeUserIds = is_array($excludeUserIds) ? $excludeUserIds : [$excludeUserIds];
                $teamMembers = array_diff($teamMembers, $excludeUserIds);
            }

            if (empty($teamMembers)) {
                return [];
            }

            return $pushService->send($teamMembers, $type, $title, $message, $data, $priority);
        } catch (\Exception $e) {
            Log::error("NotificationHelper::sendToProjectTeam failed: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Send notification to project manager and technical engineer
     *
     * @param int $projectId
     * @param string $type
     * @param string $title
     * @param string $message
     * @param array $data
     * @param string $priority
     * @param array $excludeUserIds Users to exclude from notification
     * @return array
     */
    public static function sendToProjectManagers($projectId, $type, $title, $message, $data = [], $priority = 'medium', $excludeUserIds = [])
    {
        try {
            $project = Project::where('id', $projectId)->first();
            if (!$project) {
                return [];
            }

            $userIds = [];
            if ($project->project_manager_id) {
                $userIds[] = $project->project_manager_id;
            }
            if ($project->technical_engineer_id) {
                $userIds[] = $project->technical_engineer_id;
            }

            // Exclude specified users
            if (!empty($excludeUserIds)) {
                $excludeUserIds = is_array($excludeUserIds) ? $excludeUserIds : [$excludeUserIds];
                $userIds = array_diff($userIds, $excludeUserIds);
            }

            if (empty($userIds)) {
                return [];
            }

            return self::send($userIds, $type, $title, $message, $data, $priority);
        } catch (\Exception $e) {
            Log::error("NotificationHelper::sendToProjectManagers failed: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Extract mentioned users from text (@username)
     *
     * @param string $text
     * @return array User IDs
     */
    public static function extractMentions($text)
    {
        preg_match_all('/@(\w+)/', $text, $matches);
        $usernames = $matches[1] ?? [];
        
        if (empty($usernames)) {
            return [];
        }

        // Get user IDs by usernames/names (assuming name field can be used)
        $users = \App\Models\User::whereIn('name', $usernames)
            ->orWhereIn('email', $usernames)
            ->where('is_active', true)
            ->where('is_deleted', false)
            ->pluck('id')
            ->toArray();

        return $users;
    }

    /**
     * Format notification message with placeholders
     *
     * @param string $template
     * @param array $variables
     * @return string
     */
    public static function formatMessage($template, $variables = [])
    {
        $message = $template;
        foreach ($variables as $key => $value) {
            $message = str_replace('{' . $key . '}', $value, $message);
        }
        return $message;
    }

    /**
     * Generate deep link URL for Android app
     * Based on notification type and action_url
     *
     * @param string $type Notification type
     * @param array $data Notification data (should contain action_url or specific IDs)
     * @return string Deep link URL
     */
    public static function generateDeepLink($type, $data = [])
    {
        $packageName = 'com.biltix';
        $baseUrl = "biltix://open";
        
        // Extract relevant IDs from data
        $projectId = $data['project_id'] ?? null;
        $taskId = $data['task_id'] ?? null;
        $snagId = $data['snag_id'] ?? null;
        $inspectionId = $data['inspection_id'] ?? null;
        $fileId = $data['file_id'] ?? null;
        $planId = $data['plan_id'] ?? null;
        
        // Generate deep link based on notification type
        switch ($type) {
            case 'task_assigned':
            case 'task_status_changed':
            case 'task_comment':
            case 'task_due_soon':
            case 'task_overdue':
                if ($taskId) {
                    return "{$baseUrl}?screen=task&task_id={$taskId}" . ($projectId ? "&project_id={$projectId}" : '');
                }
                break;
                
            case 'snag_reported':
            case 'snag_assigned':
            case 'snag_resolved':
            case 'snag_comment':
                if ($snagId) {
                    return "{$baseUrl}?screen=snag&snag_id={$snagId}" . ($projectId ? "&project_id={$projectId}" : '');
                }
                break;
                
            case 'inspection_created':
            case 'inspection_due':
            case 'inspection_completed':
            case 'inspection_approved':
                if ($inspectionId) {
                    return "{$baseUrl}?screen=inspection&inspection_id={$inspectionId}" . ($projectId ? "&project_id={$projectId}" : '');
                }
                break;
                
            case 'project_created':
            case 'project_status_changed':
            case 'project_updated':
                if ($projectId) {
                    return "{$baseUrl}?screen=project&project_id={$projectId}";
                }
                break;
                
            case 'team_member_added':
            case 'team_member_removed':
            case 'team_role_updated':
                if ($projectId) {
                    return "{$baseUrl}?screen=team&project_id={$projectId}";
                }
                break;
                
            case 'plan_uploaded':
            case 'plan_approved':
            case 'plan_markup_added':
                if ($planId) {
                    return "{$baseUrl}?screen=plan&plan_id={$planId}" . ($projectId ? "&project_id={$projectId}" : '');
                }
                break;
                
            case 'file_uploaded':
            case 'file_shared':
                if ($fileId) {
                    return "{$baseUrl}?screen=file&file_id={$fileId}" . ($projectId ? "&project_id={$projectId}" : '');
                }
                break;
                
            case 'daily_log_created':
                if ($projectId) {
                    return "{$baseUrl}?screen=daily_log&project_id={$projectId}";
                }
                break;
        }
        
        // Fallback: use action_url if provided, or default to dashboard
        if (isset($data['action_url'])) {
            // Convert web URL to deep link if possible
            $actionUrl = $data['action_url'];
            if (preg_match('/\/projects\/(\d+)/', $actionUrl, $matches)) {
                return "{$baseUrl}?screen=project&project_id={$matches[1]}";
            }
            if (preg_match('/\/tasks\/(\d+)/', $actionUrl, $matches)) {
                return "{$baseUrl}?screen=task&task_id={$matches[1]}";
            }
            if (preg_match('/\/snags\/(\d+)/', $actionUrl, $matches)) {
                return "{$baseUrl}?screen=snag&snag_id={$matches[1]}";
            }
        }
        
        // Default: open dashboard
        return "{$baseUrl}?screen=dashboard";
    }

    /**
     * Prepare notification data payload with all required fields for app
     *
     * @param string $type Notification type
     * @param array $data Original data
     * @param int|null $notificationId Database notification ID
     * @return array Complete data payload
     */
    public static function prepareNotificationPayload($type, $data = [], $notificationId = null)
    {
        // Generate deep link
        $deepLink = self::generateDeepLink($type, $data);
        
        // Build complete payload
        $payload = array_merge([
            'notification_type' => $type,
            'deep_link' => $deepLink,
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK', // For Flutter apps
            'android_channel_id' => 'default',
        ], $data);
        
        // Add notification ID if available (for marking as read)
        if ($notificationId) {
            $payload['notification_id'] = (string) $notificationId;
        }
        
        // Ensure all values are strings (FCM requirement)
        foreach ($payload as $key => $value) {
            if (!is_string($value)) {
                $payload[$key] = is_null($value) ? '' : (string) $value;
            }
        }
        
        return $payload;
    }
}

