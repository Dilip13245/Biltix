<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if language is provided in header
        if ($request->hasHeader('language')) {
            $language = $request->header('language');
            
            // Set supported languages
            $supportedLanguages = ['en', 'ar'];
            
            if (in_array($language, $supportedLanguages)) {
                App::setLocale($language);
            }
        }

        return $next($request);
    }
}