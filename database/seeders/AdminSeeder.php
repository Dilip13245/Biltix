<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@biltix.com',
            'password' => Hash::make('admin123'),
            'phone' => '+1 234 567 8900',
        ]);
    }
}