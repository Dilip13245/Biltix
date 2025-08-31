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
            $encryptedApiKey = $request->header('api-key');
            $decryptedApiKey = EncryptDecrypt::requestDecrypt($encryptedApiKey, 'api-key');
            $expectedApiKey = config('constant.API_KEY');
            
            // Return debug info in response
            if ($decryptedApiKey != $expectedApiKey) {
                return response()->json([
                    'code' => 401,
                    'message' => 'API Key Debug Info',
                    'debug' => [
                        'encrypted_received' => $encryptedApiKey,
                        'decrypted_result' => $decryptedApiKey,
                        'expected_key' => $expectedApiKey,
                        'match' => ($decryptedApiKey == $expectedApiKey),
                        'decrypted_length' => strlen($decryptedApiKey),
                        'expected_length' => strlen($expectedApiKey)
                    ],
                    'data' => new stdClass(),
                ], 400);
            }

            if ($decryptedApiKey == $expectedApiKey) {
                return $next($request);
            }
        }
        
        return response()->json([
            'code' => 401,
            'message' => 'API key header not found',
            'data' => new stdClass(),
        ], 400);
    }
}