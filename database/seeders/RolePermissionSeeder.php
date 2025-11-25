<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            // Projects
            ['name' => 'projects.create', 'display_name' => 'Create Projects', 'module' => 'projects', 'action' => 'create'],
            ['name' => 'projects.view', 'display_name' => 'View Projects', 'module' => 'projects', 'action' => 'view'],
            ['name' => 'projects.edit', 'display_name' => 'Edit Projects', 'module' => 'projects', 'action' => 'edit'],
            ['name' => 'projects.delete', 'display_name' => 'Delete Projects', 'module' => 'projects', 'action' => 'delete'],
            
            // Tasks
            ['name' => 'tasks.create', 'display_name' => 'Create Tasks', 'module' => 'tasks', 'action' => 'create'],
            ['name' => 'tasks.view', 'display_name' => 'View Tasks', 'module' => 'tasks', 'action' => 'view'],
            ['name' => 'tasks.update', 'display_name' => 'Update Tasks', 'module' => 'tasks', 'action' => 'update'],
            ['name' => 'tasks.complete', 'display_name' => 'Complete Tasks', 'module' => 'tasks', 'action' => 'complete'],
            ['name' => 'tasks.assign', 'display_name' => 'Assign Tasks', 'module' => 'tasks', 'action' => 'assign'],
            ['name' => 'tasks.delete', 'display_name' => 'Delete Tasks', 'module' => 'tasks', 'action' => 'delete'],
            ['name' => 'tasks.comment', 'display_name' => 'Comment on Tasks', 'module' => 'tasks', 'action' => 'comment'],
            
            // Inspections
            ['name' => 'inspections.create', 'display_name' => 'Create Inspections', 'module' => 'inspections', 'action' => 'create'],
            ['name' => 'inspections.view', 'display_name' => 'View Inspections', 'module' => 'inspections', 'action' => 'view'],
            ['name' => 'inspections.conduct', 'display_name' => 'Conduct Inspections', 'module' => 'inspections', 'action' => 'conduct'],
            ['name' => 'inspections.complete', 'display_name' => 'Complete Inspections', 'module' => 'inspections', 'action' => 'complete'],
            ['name' => 'inspections.approve', 'display_name' => 'Approve Inspections', 'module' => 'inspections', 'action' => 'approve'],
            
            // Snags
            ['name' => 'snags.create', 'display_name' => 'Create Snags', 'module' => 'snags', 'action' => 'create'],
            ['name' => 'snags.view', 'display_name' => 'View Snags', 'module' => 'snags', 'action' => 'view'],
            ['name' => 'snags.update', 'display_name' => 'Update Snags', 'module' => 'snags', 'action' => 'update'],
            ['name' => 'snags.resolve', 'display_name' => 'Resolve Snags', 'module' => 'snags', 'action' => 'resolve'],
            ['name' => 'snags.assign', 'display_name' => 'Assign Snags', 'module' => 'snags', 'action' => 'assign'],
            ['name' => 'snags.review', 'display_name' => 'Review Snags', 'module' => 'snags', 'action' => 'review'],
            
            // Plans
            ['name' => 'plans.upload', 'display_name' => 'Upload Plans', 'module' => 'plans', 'action' => 'upload'],
            ['name' => 'plans.view', 'display_name' => 'View Plans', 'module' => 'plans', 'action' => 'view'],
            ['name' => 'plans.markup', 'display_name' => 'Markup Plans', 'module' => 'plans', 'action' => 'markup'],
            ['name' => 'plans.approve', 'display_name' => 'Approve Plans', 'module' => 'plans', 'action' => 'approve'],
            ['name' => 'plans.delete', 'display_name' => 'Delete Plans', 'module' => 'plans', 'action' => 'delete'],
            
            // Files
            ['name' => 'files.upload', 'display_name' => 'Upload Files', 'module' => 'files', 'action' => 'upload'],
            ['name' => 'files.view', 'display_name' => 'View Files', 'module' => 'files', 'action' => 'view'],
            ['name' => 'files.download', 'display_name' => 'Download Files', 'module' => 'files', 'action' => 'download'],
            ['name' => 'files.delete', 'display_name' => 'Delete Files', 'module' => 'files', 'action' => 'delete'],
            
            // Photos
            ['name' => 'photos.upload', 'display_name' => 'Upload Photos', 'module' => 'photos', 'action' => 'upload'],
            ['name' => 'photos.view', 'display_name' => 'View Photos', 'module' => 'photos', 'action' => 'view'],
            ['name' => 'photos.delete', 'display_name' => 'Delete Photos', 'module' => 'photos', 'action' => 'delete'],
            
            // Daily Logs
            ['name' => 'daily_logs.create', 'display_name' => 'Create Daily Logs', 'module' => 'daily_logs', 'action' => 'create'],
            ['name' => 'daily_logs.view', 'display_name' => 'View Daily Logs', 'module' => 'daily_logs', 'action' => 'view'],
            ['name' => 'daily_logs.edit', 'display_name' => 'Edit Daily Logs', 'module' => 'daily_logs', 'action' => 'edit'],
            
            // Team Management
            ['name' => 'team.add', 'display_name' => 'Add Team Members', 'module' => 'team', 'action' => 'add'],
            ['name' => 'team.view', 'display_name' => 'View Team Members', 'module' => 'team', 'action' => 'view'],
            ['name' => 'team.remove', 'display_name' => 'Remove Team Members', 'module' => 'team', 'action' => 'remove'],
            ['name' => 'team.coordinate', 'display_name' => 'Coordinate Team', 'module' => 'team', 'action' => 'coordinate'],
            
            // Team Management (Company Level)
            ['name' => 'team_management.create', 'display_name' => 'Manage Team - Add Members', 'module' => 'team_management', 'action' => 'create'],
            ['name' => 'team_management.view', 'display_name' => 'Manage Team - View Members', 'module' => 'team_management', 'action' => 'view'],
            ['name' => 'team_management.edit', 'display_name' => 'Manage Team - Edit Members', 'module' => 'team_management', 'action' => 'edit'],
            ['name' => 'team_management.delete', 'display_name' => 'Manage Team - Remove Members', 'module' => 'team_management', 'action' => 'delete'],
            
            // Notifications
            ['name' => 'notifications.view', 'display_name' => 'View Notifications', 'module' => 'notifications', 'action' => 'view'],
            ['name' => 'notifications.update', 'display_name' => 'Update Notifications', 'module' => 'notifications', 'action' => 'update'],
            ['name' => 'notifications.delete', 'display_name' => 'Delete Notifications', 'module' => 'notifications', 'action' => 'delete'],
            
            // Reports
            ['name' => 'reports.view', 'display_name' => 'View Reports', 'module' => 'reports', 'action' => 'view'],
            ['name' => 'reports.create', 'display_name' => 'Create Reports', 'module' => 'reports', 'action' => 'create'],
            
            // Timeline & Progress
            ['name' => 'timeline.view', 'display_name' => 'View Timeline', 'module' => 'timeline', 'action' => 'view'],
            ['name' => 'timeline.edit', 'display_name' => 'Edit Timeline', 'module' => 'timeline', 'action' => 'edit'],
            ['name' => 'progress.view', 'display_name' => 'View Progress', 'module' => 'progress', 'action' => 'view'],
            ['name' => 'progress.update', 'display_name' => 'Update Progress', 'module' => 'progress', 'action' => 'update'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        // Create Roles
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrator',
                'description' => 'Full system access with all permissions'
            ],
            [
                'name' => 'contractor',
                'display_name' => 'Contractor',
                'description' => 'Full project control and management'
            ],
            [
                'name' => 'consultant',
                'display_name' => 'Consultant',
                'description' => 'Review and approval authority'
            ],
            [
                'name' => 'project_manager',
                'display_name' => 'Project Manager',
                'description' => 'Project oversight and coordination'
            ],
            [
                'name' => 'site_engineer',
                'display_name' => 'Site Engineer',
                'description' => 'Field operations and data collection'
            ],
            [
                'name' => 'stakeholder',
                'display_name' => 'Stakeholder',
                'description' => 'Read-only access to project information'
            ]
        ];

        foreach ($roles as $roleData) {
            $role = Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );

            // Assign permissions based on role
            $this->assignPermissionsToRole($role);
        }
    }

    private function assignPermissionsToRole(Role $role)
    {
        $allPermissions = Permission::all();
        
        switch ($role->name) {
            case 'super_admin':
                // Super admin gets all permissions
                $role->permissions()->sync($allPermissions->pluck('id'));
                break;
                
            case 'contractor':
                $contractorPermissions = $allPermissions->whereIn('name', [
                    'projects.create', 'projects.view', 'projects.edit', 'projects.delete',
                    'tasks.create', 'tasks.view', 'tasks.update', 'tasks.assign', 'tasks.delete', 'tasks.comment',
                    'inspections.create', 'inspections.view', 'inspections.conduct', 'inspections.approve',
                    'snags.create', 'snags.view', 'snags.update', 'snags.resolve', 'snags.assign',
                    'plans.upload', 'plans.view', 'plans.markup', 'plans.approve', 'plans.delete',
                    'files.upload', 'files.view', 'files.download', 'files.delete',
                    'photos.upload', 'photos.view', 'photos.delete',
                    'daily_logs.create', 'daily_logs.view', 'daily_logs.edit',
                    'team.add', 'team.view', 'team.remove', 'team.coordinate',
                    'team_management.create', 'team_management.view', 'team_management.edit', 'team_management.delete',
                    'notifications.view', 'notifications.update', 'notifications.delete',
                    'reports.view', 'reports.create',
                    'timeline.view', 'timeline.edit', 'progress.view', 'progress.update'
                ]);
                $role->permissions()->sync($contractorPermissions->pluck('id'));
                break;
                
            case 'consultant':
                $consultantPermissions = $allPermissions->whereIn('name', [
                    'projects.view', 'tasks.view', 'tasks.comment',
                    'inspections.create', 'inspections.view', 'inspections.approve',
                    'snags.view', 'snags.review', 'plans.view', 'plans.markup', 'plans.approve',
                    'files.view', 'files.download', 'photos.view', 'daily_logs.view',
                    'team.view', 'notifications.view', 'notifications.update', 'notifications.delete',
                    'reports.view', 'timeline.view', 'progress.view'
                ]);
                $role->permissions()->sync($consultantPermissions->pluck('id'));
                break;
                
            case 'project_manager':
                $pmPermissions = $allPermissions->whereIn('name', [
                    'projects.create', 'projects.view', 'projects.edit', 'projects.delete',
                    'tasks.create', 'tasks.view', 'tasks.assign', 'tasks.complete', 'tasks.update',
                    'inspections.create', 'inspections.view', 'inspections.conduct', 'inspections.approve',
                    'snags.create', 'snags.view', 'snags.assign', 'snags.resolve',
                    'plans.upload', 'plans.view', 'plans.approve', 'plans.delete',
                    'files.upload', 'files.view', 'files.download', 'files.delete',
                    'photos.upload', 'photos.view', 'daily_logs.create', 'daily_logs.view', 'daily_logs.edit',
                    'team.add', 'team.view', 'team.remove', 'team.coordinate',
                    'team_management.create', 'team_management.view', 'team_management.edit', 'team_management.delete',
                    'notifications.view', 'notifications.update', 'notifications.delete',
                    'reports.view', 'reports.create', 'timeline.view', 'timeline.edit',
                    'progress.view', 'progress.update'
                ]);
                $role->permissions()->sync($pmPermissions->pluck('id'));
                break;
                
            case 'site_engineer':
                $sePermissions = $allPermissions->whereIn('name', [
                    'projects.view', 'tasks.view', 'inspections.create', 'inspections.view',
                    'snags.create', 'snags.view', 'plans.view', 'plans.markup',
                    'files.upload', 'files.view', 'files.download',
                    'photos.upload', 'photos.view',
                    'daily_logs.create', 'daily_logs.view', 'daily_logs.edit',
                    'team.view', 'notifications.view', 'notifications.update', 'notifications.delete',
                    'reports.view', 'timeline.view', 'progress.view'
                ]);
                $role->permissions()->sync($sePermissions->pluck('id'));
                break;
                
            case 'stakeholder':
                $stakeholderPermissions = $allPermissions->whereIn('name', [
                    'projects.view', 'tasks.view', 'inspections.view', 'snags.view',
                    'plans.view', 'files.view', 'files.download', 'photos.view',
                    'daily_logs.view', 'team.view',
                    'notifications.view', 'notifications.update', 'notifications.delete',
                    'reports.view', 'timeline.view', 'progress.view'
                ]);
                $role->permissions()->sync($stakeholderPermissions->pluck('id'));
                break;
        }
    }
}