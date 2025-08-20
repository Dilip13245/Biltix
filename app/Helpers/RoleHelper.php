<?php

namespace App\Helpers;

use App\Models\User;

class RoleHelper
{
    /**
     * Check if user has permission for specific action
     */
    public static function hasPermission(int $userId, string $module, string $action): bool
    {
        $user = User::find($userId);
        
        if (!$user || !$user->is_active) {
            return false;
        }

        $permissions = self::getRolePermissions($user->role, $module);
        
        return in_array($action, $permissions);
    }

    /**
     * Get all permissions for a role and module
     */
    public static function getRolePermissions(string $role, string $module): array
    {
        $rolePermissions = [
            'contractor' => [
                'projects' => ['create', 'edit', 'delete', 'view'],
                'tasks' => ['create', 'update', 'complete', 'comment', 'assign', 'delete', 'view'],
                'inspections' => ['create', 'conduct', 'complete', 'approve', 'view'],
                'snags' => ['create', 'update', 'resolve', 'assign', 'review', 'approve', 'view'],
                'plans' => ['upload', 'markup', 'approve', 'view'],
                'team' => ['add', 'remove', 'coordinate', 'view'],
                'daily_logs' => ['create', 'edit', 'view'],
                'files' => ['upload', 'delete', 'view'],
                'photos' => ['upload', 'delete', 'view'],
                'reports' => ['generate', 'view']
            ],
            'site_engineer' => [
                'projects' => ['view'],
                'tasks' => ['update', 'complete', 'view'],
                'inspections' => ['conduct', 'complete', 'view'],
                'snags' => ['create', 'update', 'view'],
                'plans' => ['view', 'markup'],
                'team' => ['view'],
                'daily_logs' => ['create', 'edit', 'view'],
                'files' => ['upload', 'view'],
                'photos' => ['upload', 'view'],
                'reports' => ['view']
            ],
            'consultant' => [
                'projects' => ['view', 'comment'],
                'tasks' => ['view', 'comment'],
                'inspections' => ['create', 'approve', 'view'],
                'snags' => ['review', 'approve', 'view'],
                'plans' => ['markup', 'approve', 'view'],
                'team' => ['view'],
                'daily_logs' => ['view'],
                'files' => ['view'],
                'photos' => ['view'],
                'reports' => ['view']
            ],
            'project_manager' => [
                'projects' => ['view', 'edit'],
                'tasks' => ['assign', 'track', 'view'],
                'inspections' => ['schedule', 'review', 'view'],
                'snags' => ['assign', 'track', 'view'],
                'plans' => ['view', 'approve'],
                'team' => ['view', 'coordinate'],
                'daily_logs' => ['view'],
                'files' => ['view'],
                'photos' => ['view'],
                'reports' => ['generate', 'view']
            ],
            'stakeholder' => [
                'projects' => ['view'],
                'tasks' => ['view'],
                'inspections' => ['view'],
                'snags' => ['view'],
                'plans' => ['view'],
                'team' => ['view'],
                'daily_logs' => ['view'],
                'files' => ['view'],
                'photos' => ['view'],
                'reports' => ['view']
            ]
        ];
        
        return $rolePermissions[$role][$module] ?? [];
    }

    /**
     * Get user's role hierarchy level
     */
    public static function getRoleLevel(string $role): int
    {
        $hierarchy = [
            'stakeholder' => 1,
            'consultant' => 2,
            'site_engineer' => 3,
            'project_manager' => 4,
            'contractor' => 5
        ];

        return $hierarchy[$role] ?? 0;
    }

    /**
     * Check if user can access another user's data
     */
    public static function canAccessUser(int $requesterId, int $targetUserId): bool
    {
        if ($requesterId === $targetUserId) {
            return true; // Can always access own data
        }

        $requester = User::find($requesterId);
        $target = User::find($targetUserId);

        if (!$requester || !$target) {
            return false;
        }

        // Contractors can access all users in their projects
        if ($requester->role === 'contractor') {
            return true;
        }

        // Project managers can access team members
        if ($requester->role === 'project_manager') {
            return self::getRoleLevel($requester->role) >= self::getRoleLevel($target->role);
        }

        return false;
    }
}