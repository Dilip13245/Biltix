<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ApiService
{
    /**
     * Make an HTTP request to an external API
     */
    public function makeRequest(string $method, string $url, array $data = [], array $headers = []): array
    {
        try {
            $response = Http::withHeaders($headers)
                ->timeout(30)
                ->retry(3, 1000)
                ->$method($url, $data);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                    'status' => $response->status(),
                ];
            }

            return [
                'success' => false,
                'error' => $response->body(),
                'status' => $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('API Request Failed', [
                'method' => $method,
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Get cached data or make API request
     */
    public function getCachedData(string $key, callable $callback, int $ttl = 3600): array
    {
        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * Validate API response structure
     */
    public function validateResponse(array $response, array $requiredFields = []): bool
    {
        if (!isset($response['code']) || !isset($response['message'])) {
            return false;
        }

        foreach ($requiredFields as $field) {
            if (!isset($response[$field])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Format error response
     */
    public function formatErrorResponse(string $message, int $code = 400, array $errors = []): array
    {
        $response = [
            'code' => $code,
            'message' => $message,
            'data' => new \stdClass(),
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return $response;
    }

    /**
     * Format success response
     */
    public function formatSuccessResponse($data = null, string $message = 'Success', int $code = 200): array
    {
        return [
            'code' => $code,
            'message' => $message,
            'data' => $data ?? new \stdClass(),
        ];
    }

    /**
     * Rate limiting check
     */
    public function checkRateLimit(string $key, int $limit = 60, int $window = 3600): bool
    {
        $current = Cache::get($key, 0);
        
        if ($current >= $limit) {
            return false;
        }

        Cache::increment($key);
        Cache::expire($key, $window);

        return true;
    }

    /**
     * Generate API documentation
     */
    public function generateApiDocs(): array
    {
        $routes = \Route::getRoutes();
        $docs = [];

        foreach ($routes as $route) {
            if (str_starts_with($route->uri(), 'api/')) {
                $docs[] = [
                    'method' => implode('|', $route->methods()),
                    'uri' => $route->uri(),
                    'name' => $route->getName(),
                    'middleware' => $route->gatherMiddleware(),
                ];
            }
        }

        return $docs;
    }
}
