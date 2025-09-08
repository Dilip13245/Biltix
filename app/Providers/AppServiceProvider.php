<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Blade permission directives
        \Illuminate\Support\Facades\Blade::if('can', function ($module, $action) {
            $user = request()->attributes->get('user') ?? session('user');
            if (!$user) return false;
            
            if (is_array($user)) {
                $user = \App\Models\User::find($user['id'] ?? null);
            }
            
            return $user ? $user->hasPermission($module, $action) : false;
        });
        
        \Illuminate\Support\Facades\Blade::if('cannot', function ($module, $action) {
            $user = request()->attributes->get('user') ?? session('user');
            if (!$user) return true;
            
            if (is_array($user)) {
                $user = \App\Models\User::find($user['id'] ?? null);
            }
            
            return $user ? $user->lacksPermission($module, $action) : true;
        });
        
        \Illuminate\Support\Facades\Blade::if('hasRole', function ($role) {
            $user = request()->attributes->get('user') ?? session('user');
            if (!$user) return false;
            
            if (is_array($user)) {
                $user = \App\Models\User::find($user['id'] ?? null);
            }
            
            return $user ? $user->hasRole($role) : false;
        });
    }
}
