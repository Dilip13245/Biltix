<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Handle admin panel locale
        if ($request->is('admin*')) {
            $locale = $request->get('lang') ?? Session::get('locale', config('app.locale'));
            if (in_array($locale, ['en', 'ar'])) {
                App::setLocale($locale);
                Session::put('locale', $locale);
            }
            return $next($request);
        }
        
        // Skip livewire updates
        if ($request->is('livewire/update')) {
            return $next($request);
        }
        
        $locale = $request->get('lang') ?? Session::get('locale', config('app.locale'));
        
        if (in_array($locale, ['en', 'ar'])) {
            App::setLocale($locale);
            Session::put('locale', $locale);
        }
        
        return $next($request);
    }
}