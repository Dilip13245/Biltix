<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Admin;
use App\Helpers\DynamicRoleHelper;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Define gates for Blade @can directives
        Gate::define('admin-permission', function (Admin $admin, string $module, string $action) {
            return DynamicRoleHelper::adminHasPermission($admin, $module, $action);
        });

        // Define gates for regular users
        Gate::define('user-permission', function ($user, string $module, string $action) {
            return DynamicRoleHelper::hasPermission($user, $module, $action);
        });

        // Support existing @can('module', 'action') syntax
        Gate::before(function ($user, $ability, $arguments = []) {
            // Handle @can('module', 'action') syntax
            if (count($arguments) === 1) {
                $module = $ability;
                $action = $arguments[0];
                
                if ($user instanceof Admin) {
                    return DynamicRoleHelper::adminHasPermission($user, $module, $action);
                } else {
                    return DynamicRoleHelper::hasPermission($user, $module, $action);
                }
            }
            
            // Handle @can('module.action') syntax
            if (str_contains($ability, '.')) {
                [$module, $action] = explode('.', $ability, 2);
                
                if ($user instanceof Admin) {
                    return DynamicRoleHelper::adminHasPermission($user, $module, $action);
                } else {
                    return DynamicRoleHelper::hasPermission($user, $module, $action);
                }
            }
        });
    }
}