<?php

namespace App\Helpers;

class RtlHelper
{
    public static function isRtl(): bool
    {
        return app()->getLocale() === 'ar';
    }

    public static function direction(): string
    {
        return self::isRtl() ? 'rtl' : 'ltr';
    }

    public static function textAlign(): string
    {
        return self::isRtl() ? 'text-end' : 'text-start';
    }

    public static function floatStart(): string
    {
        return self::isRtl() ? 'float-end' : 'float-start';
    }

    public static function floatEnd(): string
    {
        return self::isRtl() ? 'float-start' : 'float-end';
    }

    public static function marginStart(int $size = 3): string
    {
        return self::isRtl() ? "me-{$size}" : "ms-{$size}";
    }

    public static function marginEnd(int $size = 3): string
    {
        return self::isRtl() ? "ms-{$size}" : "me-{$size}";
    }

    public static function paddingStart(int $size = 3): string
    {
        return self::isRtl() ? "pe-{$size}" : "ps-{$size}";
    }

    public static function paddingEnd(int $size = 3): string
    {
        return self::isRtl() ? "ps-{$size}" : "pe-{$size}";
    }

    public static function bootstrapCss(): string
    {
        return self::isRtl() 
            ? asset('website/bootstrap-5.3.1-dist/css/bootstrap.rtl.min.css')
            : asset('website/bootstrap-5.3.1-dist/css/bootstrap.min.css');
    }

    public static function customRtlCss(): string
    {
        return self::isRtl() ? asset('website/css/rtl.css') : '';
    }
}