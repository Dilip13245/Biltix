<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

class LocalizationController extends Controller
{
    /**
     * Switch language via AJAX or redirect
     */
    public function switchLanguage(Request $request, $locale)
    {
        // Validate locale
        $availableLocales = array_keys(Config::get('app.available_locales', []));
        
        if (!in_array($locale, $availableLocales)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Invalid language selected'),
                    'available_locales' => $availableLocales
                ], 400);
            }
            
            return redirect()->back()->with('error', __('Invalid language selected'));
        }
        
        // Set locale
        App::setLocale($locale);
        Session::put('locale', $locale);
        
        // Update user preference if authenticated
        if (auth()->check()) {
            auth()->user()->update(['locale' => $locale]);
        }
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'locale' => $locale,
                'message' => __('Language changed successfully'),
                'translations' => $this->getCommonTranslations($locale)
            ]);
        }
        
        $redirectUrl = $request->get('redirect', $request->headers->get('referer', url('/')));
        return redirect($redirectUrl)->with('success', __('Language changed successfully'));
    }
    
    /**
     * Get available locales (API)
     */
    public function getLocales(): JsonResponse
    {
        $locales = Config::get('app.available_locales', []);
        $currentLocale = App::getLocale();
        
        return response()->json([
            'current_locale' => $currentLocale,
            'available_locales' => $locales,
            'is_rtl' => $this->isRtlLocale($currentLocale)
        ]);
    }
    
    /**
     * Get translations for a specific namespace (API)
     */
    public function getTranslations(Request $request, $namespace = 'common'): JsonResponse
    {
        $locale = $request->get('locale', App::getLocale());
        
        // Validate locale
        $availableLocales = array_keys(Config::get('app.available_locales', []));
        if (!in_array($locale, $availableLocales)) {
            return response()->json([
                'error' => 'Invalid locale',
                'available_locales' => $availableLocales
            ], 400);
        }
        
        return response()->json([
            'locale' => $locale,
            'namespace' => $namespace,
            'translations' => $this->getTranslationsByNamespace($namespace, $locale),
            'is_rtl' => $this->isRtlLocale($locale)
        ]);
    }
    
    /**
     * Get all translations for mobile app
     */
    public function getAllTranslations(Request $request): JsonResponse
    {
        $locale = $request->get('locale', App::getLocale());
        
        // Validate locale
        $availableLocales = array_keys(Config::get('app.available_locales', []));
        if (!in_array($locale, $availableLocales)) {
            return response()->json([
                'error' => 'Invalid locale',
                'available_locales' => $availableLocales
            ], 400);
        }
        
        $translations = [
            'common' => $this->getTranslationsByNamespace('common', $locale),
            'admin' => $this->getTranslationsByNamespace('admin', $locale),
        ];
        
        // Add validation messages
        $translations['validation'] = trans('validation', [], $locale);
        
        return response()->json([
            'locale' => $locale,
            'translations' => $translations,
            'is_rtl' => $this->isRtlLocale($locale),
            'locale_info' => Config::get("app.available_locales.{$locale}", [])
        ]);
    }
    
    /**
     * Detect browser language and redirect
     */
    public function detectLanguage(Request $request)
    {
        $acceptLanguage = $request->header('Accept-Language');
        $availableLocales = array_keys(Config::get('app.available_locales', []));
        
        $detectedLocale = $this->getBrowserLocale($acceptLanguage, $availableLocales);
        
        if ($detectedLocale && $detectedLocale !== App::getLocale()) {
            Session::put('locale', $detectedLocale);
            App::setLocale($detectedLocale);
        }
        
        if ($request->expectsJson()) {
            return response()->json([
                'detected_locale' => $detectedLocale,
                'current_locale' => App::getLocale(),
                'available_locales' => Config::get('app.available_locales', [])
            ]);
        }
        
        return redirect('/');
    }
    
    /**
     * Get localized route information
     */
    public function getLocalizedRoutes(Request $request): JsonResponse
    {
        $locale = $request->get('locale', App::getLocale());
        $routes = [];
        
        // Common routes that should be localized
        $commonRoutes = [
            'home' => '/',
            'about' => '/about',
            'contact' => '/contact',
            'login' => '/login',
            'register' => '/register',
        ];
        
        foreach ($commonRoutes as $name => $path) {
            $routes[$name] = [
                'name' => $name,
                'path' => $path,
                'localized_url' => url($path . '?lang=' . $locale),
                'title' => trans("common.{$name}", [], $locale)
            ];
        }
        
        return response()->json([
            'locale' => $locale,
            'routes' => $routes
        ]);
    }
    
    /**
     * Helper: Get translations by namespace
     */
    private function getTranslationsByNamespace(string $namespace, string $locale): array
    {
        try {
            return trans($namespace, [], $locale);
        } catch (\Exception $e) {
            return [];
        }
    }
    
    /**
     * Helper: Get common translations for frontend
     */
    private function getCommonTranslations(string $locale): array
    {
        return [
            'common' => $this->getTranslationsByNamespace('common', $locale),
            'messages' => [
                'loading' => trans('common.loading', [], $locale),
                'success' => trans('common.success', [], $locale),
                'error' => trans('common.error', [], $locale),
                'confirm' => trans('common.confirm', [], $locale),
                'cancel' => trans('common.cancel', [], $locale),
            ]
        ];
    }
    
    /**
     * Helper: Detect browser locale
     */
    private function getBrowserLocale(?string $acceptLanguage, array $availableLocales): ?string
    {
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
     * Helper: Check if locale is RTL
     */
    private function isRtlLocale(string $locale): bool
    {
        $rtlLocales = ['ar', 'fa', 'he', 'ur', 'yi'];
        return in_array($locale, $rtlLocales);
    }
}