<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $supportedLocales = ['en', 'ar'];
        $sessionLocale = session('locale');

        $locale = in_array($sessionLocale, $supportedLocales, true)
            ? $sessionLocale
            : config('app.locale', 'en');

        App::setLocale($locale);

        // Ensure route() helpers automatically include locale when defined as a parameter
        URL::defaults(['locale' => $locale]);

        return $next($request);
    }
}