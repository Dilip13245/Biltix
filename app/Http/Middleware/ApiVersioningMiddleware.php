<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiVersioningMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Extract version from URL or header
        $version = $this->extractVersion($request);
        
        // Set version in request attributes
        $request->attributes->set('api_version', $version);
        
        // Validate version
        if (!$this->isValidVersion($version)) {
            return response()->json([
                'code' => 400,
                'message' => 'Invalid API version. Supported versions: ' . implode(', ', $this->getSupportedVersions()),
                'data' => new \stdClass(),
            ], 400);
        }
        
        // Add version to response headers
        $response = $next($request);
        $response->headers->set('X-API-Version', $version);
        
        return $response;
    }
    
    /**
     * Extract API version from request
     */
    private function extractVersion(Request $request): string
    {
        // Check URL path first (e.g., /api/v1/users)
        $path = $request->path();
        if (preg_match('/^api\/(v\d+)/', $path, $matches)) {
            return $matches[1];
        }
        
        // Check Accept header (e.g., application/vnd.api+json;version=1)
        $acceptHeader = $request->header('Accept');
        if (preg_match('/version=(\d+)/', $acceptHeader, $matches)) {
            return 'v' . $matches[1];
        }
        
        // Check custom header
        $versionHeader = $request->header('X-API-Version');
        if ($versionHeader) {
            return $versionHeader;
        }
        
        // Return default version
        return config('api.default_version', 'v1');
    }
    
    /**
     * Check if version is valid
     */
    private function isValidVersion(string $version): bool
    {
        return in_array($version, $this->getSupportedVersions());
    }
    
    /**
     * Get supported API versions
     */
    private function getSupportedVersions(): array
    {
        return ['v1']; // Add more versions as needed
    }
}
