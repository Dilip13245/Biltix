<?php

namespace App\Helpers;

use App\Models\User;
use App\Helpers\DynamicRoleHelper;

class RoleHelper
{
    /**
     * Check if user has permission for specific action
     */
    public static function hasPermission($user, string $module, string $action): bool
    {
        return DynamicRoleHelper::hasPermission($user, $module, $action);
    }

    /**
     * Get all permissions for a role and module
     */
    public static function getRolePermissions(string $role, string $module): array
    {
        return DynamicRoleHelper::getRolePermissions($role, $module);
    }

    /**
     * Check if role can register
     */
    public static function canRegister(string $role): bool
    {
        return DynamicRoleHelper::canRegister($role);
    }

    /**
     * Check if role is login-only
     */
    public static function isLoginOnly(string $role): bool
    {
        return DynamicRoleHelper::isLoginOnly($role);
    }

    /**
     * Get dashboard access level for role
     */
    public static function getDashboardAccess(string $role): string
    {
        return DynamicRoleHelper::getDashboardAccess($role);
    }

    /**
     * Get user's role hierarchy level
     */
    public static function getRoleLevel(string $role): int
    {
        $hierarchy = [
            'stakeholder' => 1,
            'site_engineer' => 2,
            'project_manager' => 3,
            'consultant' => 4,
            'contractor' => 5
        ];

        return $hierarchy[$role] ?? 0;
    }

    /**
     * Check if user can access another user's data
     */
    public static function canAccessUser($requester, $targetUserId): bool
    {
        if (is_int($requester)) {
            $requester = User::find($requester);
        }
        
        if (!$requester) {
            return false;
        }
        
        if ($requester->id === $targetUserId) {
            return true; // Can always access own data
        }

        $target = User::find($targetUserId);
        if (!$target) {
            return false;
        }

        // Contractors and Consultants can access all users in their projects
        if (in_array($requester->role, ['contractor', 'consultant'])) {
            return true;
        }

        // Project managers can access team members
        if ($requester->role === 'project_manager') {
            return self::getRoleLevel($requester->role) >= self::getRoleLevel($target->role);
        }

        return false;
    }

    /**
     * Get all available roles
     */
    public static function getAllRoles(): array
    {
        return DynamicRoleHelper::getAllRoles();
    }

    /**
     * Get role display name
     */
    public static function getRoleDisplayName(string $role): string
    {
        return DynamicRoleHelper::getRoleDisplayName($role);
    }
}