<?php

if (!function_exists('__t')) {
    /**
     * Enhanced translation function with fallback
     */
    function __t($key, $replace = [], $locale = null, $fallback = null)
    {
        $translation = __($key, $replace, $locale);
        
        // If translation key is returned (not found), try fallback or return key
        if ($translation === $key) {
            if ($fallback) {
                return $fallback;
            }
            // Try English as fallback if current locale is not English
            if (app()->getLocale() !== 'en') {
                $englishTranslation = __($key, $replace, 'en');
                if ($englishTranslation !== $key) {
                    return $englishTranslation;
                }
            }
        }
        
        return $translation;
    }
}

if (!function_exists('is_rtl')) {
    /**
     * Check if current locale is RTL
     */
    function is_rtl($locale = null): bool
    {
        $locale = $locale ?: app()->getLocale();
        $rtlLocales = ['ar', 'fa', 'he', 'ur', 'yi'];
        return in_array($locale, $rtlLocales);
    }
}

if (!function_exists('rtl_class')) {
    /**
     * Get RTL CSS class
     */
    function rtl_class($rtlClass = 'rtl', $ltrClass = 'ltr'): string
    {
        return is_rtl() ? $rtlClass : $ltrClass;
    }
}

if (!function_exists('dir_attr')) {
    /**
     * Get direction attribute for HTML
     */
    function dir_attr(): string
    {
        return is_rtl() ? 'rtl' : 'ltr';
    }
}

if (!function_exists('current_locale')) {
    /**
     * Get current locale
     */
    function current_locale(): string
    {
        return app()->getLocale();
    }
}

if (!function_exists('available_locales')) {
    /**
     * Get available locales
     */
    function available_locales(): array
    {
        return config('app.available_locales', []);
    }
}

if (!function_exists('locale_url')) {
    /**
     * Generate URL with locale parameter
     */
    function locale_url($locale, $url = null): string
    {
        $url = $url ?: request()->url();
        
        // Remove existing locale parameter
        $parsed = parse_url($url);
        $query = [];
        
        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $query);
        }
        
        // Add or update locale parameter
        $query['lang'] = $locale;
        
        // Rebuild URL
        $newUrl = $parsed['scheme'] . '://' . $parsed['host'];
        
        if (isset($parsed['port'])) {
            $newUrl .= ':' . $parsed['port'];
        }
        
        if (isset($parsed['path'])) {
            $newUrl .= $parsed['path'];
        }
        
        if (!empty($query)) {
            $newUrl .= '?' . http_build_query($query);
        }
        
        return $newUrl;
    }
}

if (!function_exists('localized_route')) {
    /**
     * Generate localized route URL
     */
    function localized_route($name, $parameters = [], $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        
        // If route has locale parameter, add it
        if (strpos($name, '{locale}') !== false) {
            $parameters = array_merge(['locale' => $locale], $parameters);
        }
        
        $url = route($name, $parameters);
        
        // If no locale in route, add as parameter
        if (strpos($name, '{locale}') === false) {
            $url = locale_url($locale, $url);
        }
        
        return $url;
    }
}

if (!function_exists('format_date_localized')) {
    /**
     * Format date according to locale
     */
    function format_date_localized($date, $format = null): string
    {
        if (!$date) {
            return '';
        }
        
        $locale = app()->getLocale();
        $carbonDate = \Carbon\Carbon::parse($date);
        
        // Set locale for Carbon
        $carbonDate->locale($locale);
        
        if ($format) {
            return $carbonDate->format($format);
        }
        
        // Default formats per locale
        $defaultFormats = [
            'ar' => 'd/m/Y',
            'en' => 'M d, Y',
        ];
        
        $format = $defaultFormats[$locale] ?? 'Y-m-d';
        
        return $carbonDate->format($format);
    }
}

if (!function_exists('format_number_localized')) {
    /**
     * Format number according to locale
     */
    function format_number_localized($number, $decimals = 0): string
    {
        if (!is_numeric($number)) {
            return $number;
        }
        
        $locale = app()->getLocale();
        
        // Number formatting per locale
        $formatters = [
            'ar' => [
                'decimal_point' => '.',
                'thousands_separator' => ','
            ],
            'en' => [
                'decimal_point' => '.',
                'thousands_separator' => ','
            ]
        ];
        
        $formatter = $formatters[$locale] ?? $formatters['en'];
        
        return number_format(
            $number,
            $decimals,
            $formatter['decimal_point'],
            $formatter['thousands_separator']
        );
    }
}

if (!function_exists('get_locale_direction')) {
    /**
     * Get text direction for locale
     */
    function get_locale_direction($locale = null): string
    {
        return is_rtl($locale) ? 'rtl' : 'ltr';
    }
}

if (!function_exists('translate_model_attribute')) {
    /**
     * Get translated model attribute
     */
    function translate_model_attribute($model, $attribute, $locale = null): ?string
    {
        $locale = $locale ?: app()->getLocale();
        
        // Try localized attribute first (e.g., title_ar, title_en)
        $localizedAttribute = $attribute . '_' . $locale;
        
        if (isset($model->$localizedAttribute) && !empty($model->$localizedAttribute)) {
            return $model->$localizedAttribute;
        }
        
        // Fallback to default attribute
        if (isset($model->$attribute)) {
            return $model->$attribute;
        }
        
        // Try English fallback
        if ($locale !== 'en') {
            $englishAttribute = $attribute . '_en';
            if (isset($model->$englishAttribute) && !empty($model->$englishAttribute)) {
                return $model->$englishAttribute;
            }
        }
        
        return null;
    }
}