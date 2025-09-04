<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\EncryptDecrypt;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class ApiResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $requestId = Str::uuid()->toString();
        
        // Add request ID to request for logging
        $request->attributes->set('request_id', $requestId);
        
        $response = $next($request);
        
        // Only process JSON responses
        if ($response instanceof JsonResponse) {
            $response = $this->formatApiResponse($response, $request, $startTime, $requestId);
        }
        
        return $response;
    }
    
    /**
     * Format the API response with consistent structure
     */
    private function formatApiResponse(JsonResponse $response, Request $request, float $startTime, string $requestId): JsonResponse
    {
        $data = $response->getData(true);
        $statusCode = $response->getStatusCode();
        
        // Calculate response time
        $responseTime = round((microtime(true) - $startTime) * 1000, 2);
        
        // Determine if response is successful
        $isSuccess = $statusCode >= 200 && $statusCode < 300;
        
        // Build meta information
        $meta = [
            'timestamp' => now()->toISOString(),
            'version' => config('api.version', 'v1'),
            'request_id' => $requestId,
            'response_time_ms' => $responseTime,
        ];
        
        // If response already has the expected structure, preserve it
        if (isset($data['code']) && isset($data['message']) && isset($data['data'])) {
            $formattedData = $data;
        } else {
            // Format response to standard structure
            $formattedData = [
                'code' => $statusCode,
                'message' => $isSuccess ? 'Success' : ($data['message'] ?? 'An error occurred'),
                'data' => $isSuccess ? $data : new \stdClass(),
                'meta' => $meta,
            ];
            
            // Add error details for non-success responses
            if (!$isSuccess && isset($data['errors'])) {
                $formattedData['errors'] = $data['errors'];
            }
        }
        
        // Add meta to existing response if not already present
        if (!isset($formattedData['meta'])) {
            $formattedData['meta'] = $meta;
        }
        
        // Encrypt response if encryption is enabled
        if (Config::get('constant.ENCRYPTION_ENABLED') == 1) {
            $encryptedResponse = EncryptDecrypt::bodyEncrypt(json_encode($formattedData));
            return response($encryptedResponse, $statusCode)
                ->header('Content-Type', 'text/plain')
                ->header('X-Request-ID', $requestId)
                ->header('X-Response-Time', $responseTime . 'ms');
        }
        
        return response()->json($formattedData, $statusCode)
            ->header('X-Request-ID', $requestId)
            ->header('X-Response-Time', $responseTime . 'ms');
    }
}
