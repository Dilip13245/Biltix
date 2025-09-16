<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdmin extends Command
{
    protected $signature = 'admin:create-super {--email=} {--password=} {--name=}';
    protected $description = 'Create a super admin user';

    public function handle()
    {
        $name = $this->option('name') ?: $this->ask('Enter admin name', 'Super Admin');
        $email = $this->option('email') ?: $this->ask('Enter admin email', 'admin@biltix.com');
        $password = $this->option('password') ?: $this->secret('Enter admin password');

        if (Admin::where('email', $email)->exists()) {
            $this->error('Admin with this email already exists!');
            return 1;
        }

        $admin = Admin::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'is_active' => true,
        ]);

        $superAdminRole = Role::where('name', 'super_admin')->first();
        if ($superAdminRole) {
            $admin->roles()->attach($superAdminRole->id);
        }

        $this->info('Super admin created successfully!');
        $this->table(['Name', 'Email'], [[$admin->name, $admin->email]]);

        return 0;
    }
}