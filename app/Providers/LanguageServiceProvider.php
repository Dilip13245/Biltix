<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Helpers\LanguageHelper;

class LanguageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Share language helper with all views
        View::share('languageHelper', new LanguageHelper());
        
        // Share common language variables
        View::composer('*', function ($view) {
            $view->with([
                'currentLocale' => app()->getLocale(),
                'isRtl' => app()->getLocale() === 'ar',
                'direction' => app()->getLocale() === 'ar' ? 'rtl' : 'ltr'
            ]);
        });
    }

    public function register()
    {
        //
    }
}