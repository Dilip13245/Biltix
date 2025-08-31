<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;

class EncryptDecrypt
{
    /**
     *This method is used encrypt string.
     *
     * @param  string  $string
     * @return string encrypt string
     */
    public static function bodyEncrypt($string)
    {
        $encryptionMethod = 'AES-256-CBC';
        $secret = hash('sha256', config('constant.SECRET')); //must be 32 char length
        $iv = config('constant.IV');

        $encryptValue = openssl_encrypt($string, $encryptionMethod, $secret, 0, $iv);

        return $encryptValue;
    }

    /**
     *This method is used decrypt string.
     *
     * @param  string  $string
     * @return string decrypt string
     */
    public static function bodyDecrypt($string)
    {
        $encryptionMethod = 'AES-256-CBC';
        $secret = hash('sha256', config('constant.SECRET')); //must be 32 char length
        $iv = config('constant.IV');

        $decryptValue = openssl_decrypt($string, $encryptionMethod, $secret, 0, $iv);

        return $decryptValue;
    }

    /**
     * Decrypt functionality
     * This function should now return the raw decrypted JSON string.
     * The actual JSON decoding into an associative array will happen in the middleware.
     */
    public static function requestDecrypt($encryptedContent, $type = '') // Renamed $request to $encryptedContent for clarity
    {
        if (! empty($type) && ($type == 'api-key' || $type == 'token')) {
            // For API keys or tokens, it's just a string, no JSON expected
            return self::bodyDecrypt($encryptedContent);
        }

        // For the main request body, decrypt it and return the raw JSON string
        // The middleware will then call json_decode($jsonString, true)
        $decryptedJsonString = self::bodyDecrypt($encryptedContent);

        return $decryptedJsonString;
    }
}