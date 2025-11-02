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
            $pushService = new PushNotificationService();
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
            $pushService = new PushNotificationService();
            
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
}

