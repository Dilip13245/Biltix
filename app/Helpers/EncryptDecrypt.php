<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;

class EncryptDecrypt
{
    public static function bodyEncrypt($data)
    {
        if (Config::get('constant.ENCRYPTION_ENABLED') == 1) {
            $secret = Config::get('constant.SECRET');
            $iv = Config::get('constant.IV');
            
            return openssl_encrypt($data, 'AES-256-CBC', $secret, 0, $iv);
        }
        
        return $data;
    }

    public static function bodyDecrypt($data)
    {
        if (Config::get('constant.ENCRYPTION_ENABLED') == 1) {
            $secret = Config::get('constant.SECRET');
            $iv = Config::get('constant.IV');
            
            return openssl_decrypt($data, 'AES-256-CBC', $secret, 0, $iv);
        }
        
        return $data;
    }

    public static function requestDecrypt($data, $type = '')
    {
        if (Config::get('constant.ENCRYPTION_ENABLED') == 1) {
            return self::bodyDecrypt($data);
        }
        
        return $data;
    }
}