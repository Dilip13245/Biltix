<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class DefaultRolesSeeder extends Seeder
{
    public function run()
    {
        // Create consultant role
        $consultantRole = Role::firstOrCreate([
            'name' => 'consultant'
        ], [
            'display_name' => 'Consultant',
            'description' => 'Project consultant with view and review permissions',
            'is_active' => true
        ]);

        // Create contractor role
        $contractorRole = Role::firstOrCreate([
            'name' => 'contractor'
        ], [
            'display_name' => 'Contractor',
            'description' => 'Construction contractor with full project access',
            'is_active' => true
        ]);

        // Consultant permissions
        $consultantPermissions = [
            ['module' => 'projects', 'action' => 'view'],
            ['module' => 'tasks', 'action' => 'view'],
            ['module' => 'inspections', 'action' => 'view'],
            ['module' => 'inspections', 'action' => 'conduct'],
            ['module' => 'snags', 'action' => 'view'],
            ['module' => 'snags', 'action' => 'review'],
            ['module' => 'plans', 'action' => 'view'],
            ['module' => 'plans', 'action' => 'markup'],
            ['module' => 'daily_logs', 'action' => 'view'],
            ['module' => 'files', 'action' => 'view'],
            ['module' => 'reports', 'action' => 'view'],
        ];

        // Contractor permissions
        $contractorPermissions = [
            ['module' => 'projects', 'action' => 'view'],
            ['module' => 'projects', 'action' => 'create'],
            ['module' => 'projects', 'action' => 'edit'],
            ['module' => 'tasks', 'action' => 'view'],
            ['module' => 'tasks', 'action' => 'update'],
            ['module' => 'inspections', 'action' => 'view'],
            ['module' => 'inspections', 'action' => 'create'],
            ['module' => 'snags', 'action' => 'view'],
            ['module' => 'snags', 'action' => 'create'],
            ['module' => 'plans', 'action' => 'view'],
            ['module' => 'plans', 'action' => 'upload'],
        ];

        // Create consultant permissions
        foreach ($consultantPermissions as $perm) {
            $permission = Permission::firstOrCreate([
                'module' => $perm['module'],
                'action' => $perm['action']
            ], [
                'name' => $perm['module'] . '.' . $perm['action'],
                'display_name' => ucfirst($perm['module']) . ' - ' . ucfirst($perm['action']),
                'is_active' => true
            ]);
            $consultantRole->permissions()->syncWithoutDetaching([$permission->id]);
        }

        // Create contractor permissions
        foreach ($contractorPermissions as $perm) {
            $permission = Permission::firstOrCreate([
                'module' => $perm['module'],
                'action' => $perm['action']
            ], [
                'name' => $perm['module'] . '.' . $perm['action'],
                'display_name' => ucfirst($perm['module']) . ' - ' . ucfirst($perm['action']),
                'is_active' => true
            ]);
            $contractorRole->permissions()->syncWithoutDetaching([$permission->id]);
        }
    }
}