<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'John Contractor',
                'email' => 'contractor@biltix.com',
                'phone' => '+1234567890',
                'password' => Hash::make('password123'),
                'role' => 'contractor',
                'company_name' => 'ABC Construction Ltd',
                'designation' => 'Senior Contractor',
                'employee_count' => 50,
                'member_number' => 'CON001',
                'member_name' => 'John Contractor',
                'language' => 'en',
                'is_active' => true,
                'is_deleted' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sarah Consultant',
                'email' => 'consultant@biltix.com',
                'phone' => '+1234567891',
                'password' => Hash::make('password123'),
                'role' => 'consultant',
                'company_name' => 'Expert Consulting Group',
                'designation' => 'Senior Consultant',
                'employee_count' => 25,
                'member_number' => 'CON002',
                'member_name' => 'Sarah Consultant',
                'language' => 'en',
                'is_active' => true,
                'is_deleted' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mike Engineer',
                'email' => 'engineer@biltix.com',
                'phone' => '+1234567892',
                'password' => Hash::make('password123'),
                'role' => 'site_engineer',
                'company_name' => 'Field Engineering Co',
                'designation' => 'Site Engineer',
                'employee_count' => 15,
                'member_number' => 'ENG001',
                'member_name' => 'Mike Engineer',
                'language' => 'en',
                'is_active' => true,
                'is_deleted' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lisa Manager',
                'email' => 'manager@biltix.com',
                'phone' => '+1234567893',
                'password' => Hash::make('password123'),
                'role' => 'project_manager',
                'company_name' => 'Project Management Pro',
                'designation' => 'Project Manager',
                'employee_count' => 30,
                'member_number' => 'PM001',
                'member_name' => 'Lisa Manager',
                'language' => 'en',
                'is_active' => true,
                'is_deleted' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Robert Stakeholder',
                'email' => 'stakeholder@biltix.com',
                'phone' => '+1234567894',
                'password' => Hash::make('password123'),
                'role' => 'stakeholder',
                'company_name' => 'Investment Partners LLC',
                'designation' => 'Senior Stakeholder',
                'employee_count' => 100,
                'member_number' => 'STK001',
                'member_name' => 'Robert Stakeholder',
                'language' => 'en',
                'is_active' => true,
                'is_deleted' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}