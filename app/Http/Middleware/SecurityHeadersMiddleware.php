<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        if (!config('api.security_headers.enabled', true)) {
            return $response;
        }
        
        // Security Headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        
        // HSTS Header (only for HTTPS)
        if ($request->isSecure()) {
            $hstsMaxAge = config('api.security_headers.hsts_max_age', 31536000);
            $response->headers->set('Strict-Transport-Security', "max-age={$hstsMaxAge}; includeSubDomains");
        }
        
        // Content Security Policy
        $csp = config('api.security_headers.content_security_policy', "default-src 'self'");
        $response->headers->set('Content-Security-Policy', $csp);
        
        // Remove server information
        $response->headers->remove('Server');
        $response->headers->remove('X-Powered-By');
        
        // API-specific headers
        $response->headers->set('X-API-Version', config('api.version', 'v1'));
        $response->headers->set('X-Content-Source', 'api');
        
        return $response;
    }
}
