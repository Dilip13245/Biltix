<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;

class LanguageHelper
{
    public static function isRtl(): bool
    {
        return App::getLocale() === 'ar';
    }
    
    public static function getDirection(): string
    {
        return self::isRtl() ? 'rtl' : 'ltr';
    }
    
    public static function getSupportedLocales(): array
    {
        return config('app.supported_locales', ['en']);
    }
    
    public static function getCurrentLocale(): string
    {
        return App::getLocale();
    }
}