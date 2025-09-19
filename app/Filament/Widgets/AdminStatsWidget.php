<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Admin;
use App\Models\Project;
use App\Models\User;

class AdminStatsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected function getStats(): array
    {
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $totalAdmins = Admin::count();
        $activeAdmins = Admin::where('is_active', true)->count();
        $totalRoles = Role::count();
        $activeRoles = Role::where('is_active', true)->count();
        $totalPermissions = Permission::count();
        $activePermissions = Permission::where('is_active', true)->count();
        
        return [
            Stat::make(__('filament.dashboard.system_users'), $totalUsers)
                ->description("{$activeUsers} " . __('filament.dashboard.active_users'))
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
->chart([12, 8, 15, 10, 18, 14, 20])
                ->extraAttributes(['class' => 'text-center',]),

            Stat::make('Total Projects', Project::count())
                ->description(Project::where('status', 'active')->count() . ' currently active')
                ->descriptionIcon('heroicon-m-home-modern')
                ->color('success')
                ->chart([12, 8, 15, 10, 18, 14, 20])
                ->extraAttributes(['class' => 'text-center']),
                
            Stat::make(__('filament.dashboard.roles_permissions'), $totalRoles)
                ->description("{$totalPermissions} " . __('filament.dashboard.permissions_available'))
                ->descriptionIcon('heroicon-m-key')
                ->color('warning')
->chart([8, 6, 10, 7, 12, 9, 14])
                ->extraAttributes(['class' => 'text-center']),

            Stat::make(__('filament.dashboard.admin_users'), $totalAdmins)
                ->description("{$activeAdmins} " . __('filament.dashboard.active_admins'))
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('info')
->chart([5, 3, 7, 4, 8, 6, 9])
                ->extraAttributes(['class' => 'text-center']),

            Stat::make('Completed Projects', Project::where('status', 'completed')->count())
                ->description('Successfully completed')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('warning')
                ->chart([3, 7, 4, 9, 5, 12, 8])
                ->extraAttributes(['class' => 'text-center']),
                
            Stat::make(__('filament.dashboard.system_modules'), Permission::distinct('module')->count('module'))
                ->description(__('filament.dashboard.available_modules'))
                ->descriptionIcon('heroicon-m-squares-2x2')
                ->color('primary')
->chart([4, 3, 5, 4, 6, 5, 7])
                ->extraAttributes(['class' => 'text-center']),
        ];
    }
}