<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use App\Helpers\DynamicRoleHelper;
use Illuminate\Support\Facades\Cache;

class SyncPermissions extends Command
{
    protected $signature = 'permissions:sync {--clear-cache}';
    protected $description = 'Sync user roles with database permissions and clear cache';

    public function handle()
    {
        $this->info('Starting permission synchronization...');

        // Get all users with their roles
        $users = User::whereNotNull('role')->get();
        $syncedCount = 0;

        foreach ($users as $user) {
            $role = Role::where('name', $user->role)->first();
            if ($role) {
                $this->line("✓ User {$user->email} has valid role: {$role->display_name}");
                $syncedCount++;
            } else {
                $this->warn("⚠ User {$user->email} has invalid role: {$user->role}");
            }
        }

        $this->info("Synced {$syncedCount} users with valid roles.");

        // Clear permission cache if requested
        if ($this->option('clear-cache')) {
            $this->info('Clearing permission cache...');
            
            foreach ($users as $user) {
                DynamicRoleHelper::clearUserPermissionCache($user->id);
            }
            
            Cache::flush();
            $this->info('✓ Permission cache cleared.');
        }

        // Display role statistics
        $this->table(
            ['Role', 'Display Name', 'Users Count', 'Permissions Count'],
            Role::withCount(['permissions'])
                ->get()
                ->map(function ($role) use ($users) {
                    $userCount = $users->where('role', $role->name)->count();
                    return [
                        $role->name,
                        $role->display_name,
                        $userCount,
                        $role->permissions_count
                    ];
                })
        );

        $this->info('Permission synchronization completed!');
        return 0;
    }
}