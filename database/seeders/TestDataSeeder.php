<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserDevice;
use App\Models\SnagCategory;
use App\Models\FileCategory;
use App\Models\InspectionTemplate;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Create test users for each role
        $roles = ['contractor', 'site_engineer', 'consultant', 'project_manager', 'stakeholder'];
        
        foreach ($roles as $role) {
            $user = User::create([
                'name' => ucfirst(str_replace('_', ' ', $role)),
                'email' => $role . '@biltix.com',
                'password' => Hash::make('password123'),
                'phone' => '+123456789' . array_search($role, $roles),
                'role' => $role,
                'company_name' => 'Biltix ' . ucfirst($role),
                'designation' => ucfirst(str_replace('_', ' ', $role)),
                'is_active' => 1
            ]);

            // Create device token
            UserDevice::create([
                'user_id' => $user->id,
                'uuid' => 'test-device-' . $role,
                'device_type' => 'A',
                'token' => 'token-' . $role . '-' . time()
            ]);
        }

        // Create snag categories
        $categories = ['Structural', 'Electrical', 'Plumbing', 'Finishing', 'Safety'];
        foreach ($categories as $category) {
            SnagCategory::create([
                'name' => $category,
                'description' => $category . ' related issues',
                'is_active' => 1
            ]);
        }

        // Create file categories
        $fileCategories = ['Drawings', 'Reports', 'Photos', 'Documents'];
        foreach ($fileCategories as $category) {
            FileCategory::create([
                'name' => $category,
                'description' => $category . ' files',
                'is_active' => 1
            ]);
        }

        // Create inspection templates
        InspectionTemplate::create([
            'name' => 'Quality Inspection',
            'description' => 'Standard quality checklist',
            'category' => 'quality',
            'checklist_items' => json_encode([
                'Check material quality',
                'Verify dimensions',
                'Check finish'
            ]),
            'created_by' => 1,
            'is_active' => 1
        ]);
    }
}