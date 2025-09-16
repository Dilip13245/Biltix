<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class RoleController extends Controller
{
    public function getRolePermissions(Request $request)
    {
        try {
            $roles = Role::where('is_active', true)
                ->with(['permissions' => function($query) {
                    $query->where('is_active', true);
                }])
                ->get();

            $formattedRoles = [];

            foreach ($roles as $role) {
                $permissions = [];
                
                // Group permissions by module
                $modulePermissions = $role->permissions->groupBy('module');
                
                foreach ($modulePermissions as $module => $modulePerms) {
                    $actions = $modulePerms->pluck('action')->toArray();
                    
                    // Format based on actions available
                    if (empty($actions)) {
                        $permissions[$module] = false;
                    } else {
                        // Check for full permissions
                        $hasAll = in_array('create', $actions) && in_array('edit', $actions) && 
                                 in_array('delete', $actions) && in_array('view', $actions);
                        
                        if ($hasAll) {
                            $permissions[$module] = 'full';
                        } elseif (in_array('view', $actions) && count($actions) === 1) {
                            $permissions[$module] = 'view-only';
                        } elseif (count($actions) === 1) {
                            // Single specific action
                            $permissions[$module] = $actions[0];
                        } else {
                            // Multiple specific actions
                            $permissions[$module] = $actions;
                        }
                    }
                }

                $formattedRoles[$role->display_name] = [
                    'permissions' => $permissions
                ];
            }

            return $this->toJsonEnc($formattedRoles, 'Role permissions retrieved successfully', Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function getUserPermissions(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            $role_name = $request->input('role');

            if (!$role_name) {
                return $this->toJsonEnc([], 'Role is required', Config::get('constant.ERROR'));
            }

            $role = Role::where('name', $role_name)
                ->where('is_active', true)
                ->with(['permissions' => function($query) {
                    $query->where('is_active', true);
                }])
                ->first();

            if (!$role) {
                return $this->toJsonEnc([], 'Role not found', Config::get('constant.NOT_FOUND'));
            }

            $permissions = [];
            $modulePermissions = $role->permissions->groupBy('module');
            
            foreach ($modulePermissions as $module => $modulePerms) {
                $actions = $modulePerms->pluck('action')->toArray();
                
                if (empty($actions)) {
                    $permissions[$module] = false;
                } elseif (count($actions) === 1) {
                    $permissions[$module] = $actions[0];
                } else {
                    // $hasAll = in_array('create', $actions) && in_array('edit', $actions) && 
                    //          in_array('delete', $actions) && in_array('view', $actions);
                    
                    // if ($hasAll) {
                    //     $permissions[$module] = 'full';
                    // } elseif (in_array('view', $actions) && count($actions) === 1) {
                    //     $permissions[$module] = 'view-only';
                    // } else {
                        $permissions[$module] = $actions;
                    // }
                }
            }

            $userPermissions = [
                'role_id' => $role->id,
                'role_name' => $role->name,
                'role_display_name' => $role->display_name,
                'can_register' => $role->can_register,
                'dashboard_access' => $role->dashboard_access,
                'hierarchy_level' => $role->hierarchy_level,
                'permissions' => $permissions
            ];

            return $this->toJsonEnc($userPermissions, 'User permissions retrieved successfully', Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }
}