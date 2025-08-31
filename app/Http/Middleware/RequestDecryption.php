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

        // Only decrypt if content is not valid JSON (i.e., encrypted string)
        if (! empty($rawContent) && ! $this->isJson($rawContent)) {
            $decryptedJson = EncryptDecrypt::requestDecrypt($rawContent);
            $decodedArray = json_decode($decryptedJson, true);

            if (is_array($decodedArray)) {
                Log::info('🧩 Decoded Request Data:', $decodedArray);
                $request->replace($decodedArray);
            } else {
                Log::error('❌ Failed to decode decrypted request. Raw:', ['data' => $decryptedJson]);
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