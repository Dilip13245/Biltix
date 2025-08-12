<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get available locales from config
        $availableLocales = array_keys(Config::get('app.available_locales', ['en' => []]));
        
        // Priority order for locale detection:
        // 1. URL parameter (?lang=ar)
        // 2. Route parameter ({locale})
        // 3. Session
        // 4. User preference (if authenticated)
        // 5. Browser language
        // 6. Default locale
        
        $locale = $this->detectLocale($request, $availableLocales);
        
        // Set the application locale
        App::setLocale($locale);
        
        // Store in session for future requests
        Session::put('locale', $locale);
        
        // Share locale data with views
        view()->share('currentLocale', $locale);
        view()->share('availableLocales', Config::get('app.available_locales'));
        view()->share('isRtl', $this->isRtlLocale($locale));
        
        return $next($request);
    }
    
    /**
     * Detect the best locale for the request
     */
    private function detectLocale(Request $request, array $availableLocales): string
    {
        // 1. Check URL parameter
        if ($request->has('lang') && in_array($request->get('lang'), $availableLocales)) {
            return $request->get('lang');
        }
        
        // 2. Check route parameter
        $routeLocale = $request->route('locale');
        if ($routeLocale && in_array($routeLocale, $availableLocales)) {
            return $routeLocale;
        }
        
        // 3. Check session
        $sessionLocale = Session::get('locale');
        if ($sessionLocale && in_array($sessionLocale, $availableLocales)) {
            return $sessionLocale;
        }
        
        // 4. Check user preference (if authenticated)
        if (auth()->check() && auth()->user()->locale) {
            $userLocale = auth()->user()->locale;
            if (in_array($userLocale, $availableLocales)) {
                return $userLocale;
            }
        }
        
        // 5. Check browser language
        $browserLocale = $this->getBrowserLocale($request, $availableLocales);
        if ($browserLocale) {
            return $browserLocale;
        }
        
        // 6. Return default locale
        return Config::get('app.locale', 'en');
    }
    
    /**
     * Get browser preferred language
     */
    private function getBrowserLocale(Request $request, array $availableLocales): ?string
    {
        $acceptLanguage = $request->header('Accept-Language');
        
        if (!$acceptLanguage) {
            return null;
        }
        
        // Parse Accept-Language header
        $languages = [];
        preg_match_all('/([a-z]{1,8}(?:-[a-z]{1,8})?)\s*(?:;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $acceptLanguage, $matches);
        
        if (!empty($matches[1])) {
            foreach ($matches[1] as $i => $lang) {
                $quality = isset($matches[2][$i]) ? (float) $matches[2][$i] : 1.0;
                $languages[strtolower($lang)] = $quality;
            }
            
            // Sort by quality
            arsort($languages);
            
            // Check for exact matches first
            foreach (array_keys($languages) as $lang) {
                if (in_array($lang, $availableLocales)) {
                    return $lang;
                }
            }
            
            // Check for partial matches (e.g., 'ar-SA' matches 'ar')
            foreach (array_keys($languages) as $lang) {
                $shortLang = substr($lang, 0, 2);
                if (in_array($shortLang, $availableLocales)) {
                    return $shortLang;
                }
            }
        }
        
        return null;
    }
    
    /**
     * Check if locale is RTL
     */
    private function isRtlLocale(string $locale): bool
    {
        $rtlLocales = ['ar', 'fa', 'he', 'ur', 'yi'];
        return in_array($locale, $rtlLocales);
    }
}