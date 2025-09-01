<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\EncryptDecrypt;
use Illuminate\Support\Facades\Log;

class RequestDecryption
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $rawContent = $request->getContent();
        
        if (config('constant.ENCRYPTION_ENABLED') == 1) {
            // Encryption enabled - only accept raw encrypted string (not JSON)
            if (empty($rawContent)) {
                return response()->json(EncryptDecrypt::bodyEncrypt(json_encode([
                    'code' => 400,
                    'message' => 'Request body is required',
                    'data' => new \stdClass(),
                ])), 400);
            }
            
            if ($this->isJson($rawContent)) {
                return response()->json(EncryptDecrypt::bodyEncrypt(json_encode([
                    'code' => 400,
                    'message' => 'Request must be encrypted plain text, not JSON',
                    'data' => new \stdClass(),
                ])), 400);
            }
            
            $decryptedJson = EncryptDecrypt::requestDecrypt($rawContent);
            $decodedArray = json_decode($decryptedJson, true);

            if (is_array($decodedArray)) {
                Log::info('🧩 Decoded Request Data:', $decodedArray);
                $request->replace($decodedArray);
            } else {
                Log::error('❌ Failed to decode decrypted request. Raw:', ['data' => $decryptedJson]);
                return response()->json(EncryptDecrypt::bodyEncrypt(json_encode([
                    'code' => 400,
                    'message' => 'Invalid encrypted data',
                    'data' => new \stdClass(),
                ])), 400);
            }
        } else {
            // Encryption disabled - only accept JSON data
            if (!empty($rawContent) && !$this->isJson($rawContent)) {
                return response()->json([
                    'code' => 400,
                    'message' => 'Request must be valid JSON',
                    'data' => new \stdClass(),
                ], 400);
            }
        }

        return $next($request);
    }

    protected function isJson($string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}