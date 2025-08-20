<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\EncryptDecrypt;

class RequestDecryption
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (config('constant.ENCRYPTION_ENABLED') == 1) {
            // Decrypt request body if encrypted
            $input = $request->all();
            if (!empty($input)) {
                foreach ($input as $key => $value) {
                    if (is_string($value)) {
                        $decrypted = EncryptDecrypt::bodyDecrypt($value);
                        if ($decrypted !== false) {
                            $request->merge([$key => $decrypted]);
                        }
                    }
                }
            }
        }

        return $next($request);
    }
}