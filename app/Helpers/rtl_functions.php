<?php

if (!function_exists('is_rtl')) {
    function is_rtl(): bool
    {
        return app()->getLocale() === 'ar';
    }
}

if (!function_exists('rtl_helper')) {
    function rtl_helper(): \App\Helpers\RtlHelper
    {
        return new \App\Helpers\RtlHelper();
    }
}

if (!function_exists('dir_class')) {
    function dir_class(): string
    {
        return is_rtl() ? 'rtl' : 'ltr';
    }
}

if (!function_exists('text_align')) {
    function text_align(): string
    {
        return is_rtl() ? 'text-end' : 'text-start';
    }
}

if (!function_exists('float_start')) {
    function float_start(): string
    {
        return is_rtl() ? 'float-end' : 'float-start';
    }
}

if (!function_exists('float_end')) {
    function float_end(): string
    {
        return is_rtl() ? 'float-start' : 'float-end';
    }
}

if (!function_exists('margin_start')) {
    function margin_start(int $size = 3): string
    {
        return is_rtl() ? "me-{$size}" : "ms-{$size}";
    }
}

if (!function_exists('margin_end')) {
    function margin_end(int $size = 3): string
    {
        return is_rtl() ? "ms-{$size}" : "me-{$size}";
    }
}

if (!function_exists('padding_start')) {
    function padding_start(int $size = 3): string
    {
        return is_rtl() ? "pe-{$size}" : "ps-{$size}";
    }
}

if (!function_exists('padding_end')) {
    function padding_end(int $size = 3): string
    {
        return is_rtl() ? "ps-{$size}" : "pe-{$size}";
    }
}

if (!function_exists('bootstrap_css')) {
    function bootstrap_css(): string
    {
        return is_rtl() 
            ? asset('website/bootstrap-5.3.1-dist/css/bootstrap.rtl.min.css')
            : asset('website/bootstrap-5.3.1-dist/css/bootstrap.min.css');
    }
}

if (!function_exists('bootstrap_css_assets')) {
    function bootstrap_css_assets(): string
    {
        return is_rtl() 
            ? asset('assets/bootstrap-5.3.1-dist/css/bootstrap.rtl.min.css')
            : asset('assets/bootstrap-5.3.1-dist/css/bootstrap.min.css');
    }
}