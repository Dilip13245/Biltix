<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'John Smith',
                'email' => 'contractor@biltix.com',
                'phone' => '+1234567890',
                'password' => Hash::make('password123'),
                'role' => 'contractor',
                'company_name' => 'BuildCorp Construction',
                'designation' => 'Senior Contractor',
                'employee_count' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'engineer@biltix.com',
                'phone' => '+1234567891',
                'password' => Hash::make('password123'),
                'role' => 'site_engineer',
                'company_name' => 'TechBuild Solutions',
                'designation' => 'Senior Site Engineer',
                'employee_count' => 25,
                'is_active' => true,
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'consultant@biltix.com',
                'phone' => '+1234567892',
                'password' => Hash::make('password123'),
                'role' => 'consultant',
                'company_name' => 'Expert Consulting Group',
                'designation' => 'Senior Construction Consultant',
                'employee_count' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'manager@biltix.com',
                'phone' => '+1234567893',
                'password' => Hash::make('password123'),
                'role' => 'project_manager',
                'company_name' => 'ProjectPro Management',
                'designation' => 'Senior Project Manager',
                'employee_count' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Robert Wilson',
                'email' => 'stakeholder@biltix.com',
                'phone' => '+1234567894',
                'password' => Hash::make('password123'),
                'role' => 'stakeholder',
                'company_name' => 'Investment Partners LLC',
                'designation' => 'Senior Stakeholder',
                'employee_count' => 100,
                'is_active' => true,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}