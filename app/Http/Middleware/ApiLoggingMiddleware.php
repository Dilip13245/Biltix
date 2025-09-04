<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiLoggingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!config('api.monitoring.enable_api_logging', true)) {
            return $next($request);
        }
        
        $startTime = microtime(true);
        $requestId = $request->attributes->get('request_id', 'unknown');
        
        // Log incoming request
        $this->logRequest($request, $requestId);
        
        $response = $next($request);
        
        // Log response
        $this->logResponse($request, $response, $startTime, $requestId);
        
        return $response;
    }
    
    /**
     * Log the incoming request
     */
    private function logRequest(Request $request, string $requestId): void
    {
        $logData = [
            'request_id' => $requestId,
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'headers' => $this->getSafeHeaders($request),
            'timestamp' => now()->toISOString(),
        ];
        
        // Add user information if available
        if ($request->has('user_id')) {
            $logData['user_id'] = $request->input('user_id');
        }
        
        // Add request body (excluding sensitive data)
        if ($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('PATCH')) {
            $logData['body'] = $this->getSafeRequestBody($request);
        }
        
        Log::channel('api')->info('API Request', $logData);
    }
    
    /**
     * Log the response
     */
    private function logResponse(Request $request, Response $response, float $startTime, string $requestId): void
    {
        $responseTime = round((microtime(true) - $startTime) * 1000, 2);
        
        $logData = [
            'request_id' => $requestId,
            'status_code' => $response->getStatusCode(),
            'response_time_ms' => $responseTime,
            'timestamp' => now()->toISOString(),
        ];
        
        // Log slow requests
        if ($responseTime > config('api.monitoring.slow_query_threshold', 1000)) {
            Log::channel('api')->warning('Slow API Request', $logData);
        } else {
            Log::channel('api')->info('API Response', $logData);
        }
    }
    
    /**
     * Get safe headers (excluding sensitive information)
     */
    private function getSafeHeaders(Request $request): array
    {
        $headers = $request->headers->all();
        
        // Remove sensitive headers
        $sensitiveHeaders = ['authorization', 'api-key', 'token', 'cookie'];
        
        foreach ($sensitiveHeaders as $header) {
            if (isset($headers[$header])) {
                $headers[$header] = ['***'];
            }
        }
        
        return $headers;
    }
    
    /**
     * Get safe request body (excluding sensitive fields)
     */
    private function getSafeRequestBody(Request $request): array
    {
        $body = $request->all();
        
        // Remove sensitive fields
        $sensitiveFields = ['password', 'token', 'api_key', 'secret', 'otp'];
        
        foreach ($sensitiveFields as $field) {
            if (isset($body[$field])) {
                $body[$field] = '***';
            }
        }
        
        return $body;
    }
}
