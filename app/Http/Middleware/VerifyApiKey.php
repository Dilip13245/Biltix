<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\EncryptDecrypt;
use stdClass;
use Illuminate\Support\Facades\Config;

class VerifyApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasHeader('api-key')) {
            $apiKey = $request->header('api-key');
            
            // Check if encryption is enabled
            if (Config::get('constant.ENCRYPTION_ENABLED') == 1) {
                $apiKey = EncryptDecrypt::requestDecrypt($apiKey, 'api-key');
            }
            
            $expectedApiKey = config('constant.API_KEY');
            
            // Debug logging
            \Log::info('API Key Verification', [
                'received' => $apiKey,
                'expected' => $expectedApiKey,
                'match' => $apiKey === $expectedApiKey
            ]);
            
                if ($apiKey == $expectedApiKey) {
                return $next($request);
            }
            if (Config::get('constant.ENCRYPTION_ENABLED') == 1) {
                return response()->json(EncryptDecrypt::bodyEncrypt(json_encode([
                    'code' => 401,
                    'message' => trans('auth.invalid_api_key'),
                    'data' => new stdClass(),
                ])), 400);
            } else {
                return response()->json([
                    'code' => 401,
                    'message' => trans('auth.invalid_api_key'),
                    'data' => new stdClass(),
                ], 400);
            }
        }
        if (Config::get('constant.ENCRYPTION_ENABLED') == 1) {
            return response()->json(EncryptDecrypt::bodyEncrypt(json_encode([
                'code' => 401,
                'message' => trans('auth.api_key_not_found'),
                'data' => new stdClass(),
            ])), 400);
        } else {
            return response()->json([
                'code' => 401,
                'message' => trans('auth.api_key_not_found'),
                'data' => new stdClass(),
            ], 400);
        }
    }
}