<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocaleFromRequest
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $supportedLocales = ['en', 'ar'];

        $header = $request->header('Accept-Language');
        $query = $request->query('lang');

        $candidate = $query ?: ($header ? substr($header, 0, 2) : null);

        $locale = in_array($candidate, $supportedLocales, true) ? $candidate : config('app.locale', 'en');

        App::setLocale($locale);

        return $next($request);
    }
}