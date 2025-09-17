<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Cache;

class DynamicRoleHelper
{
    /**
     * Check if user has permission for specific action
     */
    public static function hasPermission($user, string $module, string $action): bool
    {
        if (is_int($user)) {
            $user = User::find($user);
        }
        
        if (!$user || !$user->is_active) {
            return false;
        }

        // For regular users, check their role permissions from database
        $cacheKey = "user_permissions_{$user->id}_{$module}_{$action}";
        
        return Cache::remember($cacheKey, 300, function () use ($user, $module, $action) {
            // Get user's role from the role field
            $roleName = $user->role;
            
            $role = Role::where('name', $roleName)->where('is_active', true)->first();
            if (!$role) {
                // Role not found in database
                return false;
            }
            
            return $role->permissions()
                ->where('module', $module)
                ->where('action', $action)
                ->where('is_active', true)
                ->exists();
        });
    }

    /**
     * Check if admin has permission
     */
    public static function adminHasPermission($admin, string $module, string $action): bool
    {
        if (is_int($admin)) {
            $admin = Admin::find($admin);
        }
        
        if (!$admin || !$admin->is_active) {
            return false;
        }

        $cacheKey = "admin_permissions_{$admin->id}_{$module}_{$action}";
        
        return Cache::remember($cacheKey, 300, function () use ($admin, $module, $action) {
            return $admin->roles()->whereHas('permissions', function ($query) use ($module, $action) {
                $query->where('module', $module)
                      ->where('action', $action)
                      ->where('is_active', true);
            })->exists();
        });
    }

    /**
     * Get all permissions for a role
     */
    public static function getRolePermissions(string $roleName, string $module = null): array
    {
        $cacheKey = "role_permissions_{$roleName}" . ($module ? "_{$module}" : '');
        
        return Cache::remember($cacheKey, 600, function () use ($roleName, $module) {
            $role = Role::where('name', $roleName)->where('is_active', true)->first();
            if (!$role) {
                return [];
            }
            
            $query = $role->permissions()->where('is_active', true);
            
            if ($module) {
                $query->where('module', $module);
            }
            
            return $query->pluck('action')->toArray();
        });
    }

    /**
     * Clear permission cache for user
     */
    public static function clearUserPermissionCache($userId): void
    {
        $modules = Permission::distinct()->pluck('module');
        $actions = Permission::distinct()->pluck('action');
        
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Cache::forget("user_permissions_{$userId}_{$module}_{$action}");
            }
        }
    }

    /**
     * Clear permission cache for admin
     */
    public static function clearAdminPermissionCache($adminId): void
    {
        $modules = Permission::distinct()->pluck('module');
        $actions = Permission::distinct()->pluck('action');
        
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Cache::forget("admin_permissions_{$adminId}_{$module}_{$action}");
            }
        }
    }

    /**
     * Get all available roles
     */
    public static function getAllRoles(): array
    {
        return Role::where('is_active', true)->pluck('display_name', 'name')->toArray();
    }
    
    /**
     * Get all available modules dynamically
     */
    public static function getAllModules(): array
    {
        $modules = Permission::distinct()->pluck('module')->toArray();
        $formattedModules = [];
        
        foreach ($modules as $module) {
            $formattedModules[$module] = ucfirst(str_replace('_', ' ', $module));
        }
        
        return $formattedModules;
    }
    
    /**
     * Get all available actions dynamically
     */
    public static function getAllActions(): array
    {
        $actions = Permission::distinct()->pluck('action')->toArray();
        $formattedActions = [];
        
        foreach ($actions as $action) {
            $formattedActions[$action] = ucfirst(str_replace('_', ' ', $action));
        }
        
        return $formattedActions;
    }
    
    /**
     * Auto-create permissions for new modules
     */
    public static function createModulePermissions(string $module, array $actions = ['view', 'create', 'edit', 'delete']): void
    {
        foreach ($actions as $action) {
            Permission::firstOrCreate(
                ['name' => $module . '.' . $action],
                [
                    'module' => $module,
                    'action' => $action,
                    'display_name' => ucfirst(str_replace('_', ' ', $module)) . ' - ' . ucfirst($action),
                    'description' => 'Can ' . $action . ' ' . str_replace('_', ' ', $module),
                    'is_active' => true
                ]
            );
        }
    }

    /**
     * Get role display name
     */
    public static function getRoleDisplayName(string $roleName): string
    {
        $role = Role::where('name', $roleName)->first();
        return $role ? $role->display_name : ucfirst(str_replace('_', ' ', $roleName));
    }

    /**
     * Check if role can register
     */
    public static function canRegister(string $role): bool
    {
        return in_array($role, ['consultant', 'contractor']);
    }

    /**
     * Check if role is login-only
     */
    public static function isLoginOnly(string $role): bool
    {
        return in_array($role, ['project_manager', 'site_engineer', 'stakeholder']);
    }

    /**
     * Get dashboard access level for role
     */
    public static function getDashboardAccess(string $role): string
    {
        $fullAccess = ['consultant', 'contractor', 'project_manager', 'site_engineer'];
        $viewOnly = ['stakeholder'];
        
        if (in_array($role, $fullAccess)) {
            return 'full';
        } elseif (in_array($role, $viewOnly)) {
            return 'view_only';
        }
        
        return 'none';
    }

    /**
     * Fallback to static permissions if role not in database
     */
    private static function hasStaticPermission(string $role, string $module, string $action): bool
    {
        $staticPermissions = [
            'contractor' => [
                'projects' => ['create', 'edit', 'delete', 'view'],
                'tasks' => ['update', 'view'],
                'inspections' => ['create', 'conduct', 'complete', 'approve', 'view'],
                'snags' => ['create', 'update', 'resolve', 'assign', 'review', 'approve', 'view'],
                'plans' => ['upload', 'markup', 'approve', 'view', 'delete'],
                'team' => ['add', 'remove', 'coordinate', 'view'],
                'daily_logs' => ['create', 'edit', 'view'],
                'files' => ['upload', 'delete', 'view'],
                'photos' => ['upload', 'delete', 'view'],
                'reports' => ['generate', 'view'],
            ],
            'consultant' => [
                'projects' => ['view'],
                'tasks' => ['view'],
                'inspections' => ['conduct', 'view'],
                'snags' => ['review', 'resolve', 'view'],
                'plans' => ['markup', 'view'],
                'team' => ['view'],
                'daily_logs' => ['comment', 'view'],
                'files' => ['view'],
                'photos' => ['view'],
                'reports' => ['view'],
            ],
        ];

        return in_array($action, $staticPermissions[$role][$module] ?? []);
    }
}