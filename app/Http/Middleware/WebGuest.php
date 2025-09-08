<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebGuest
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = session('user_id');
        $sessionToken = session('token');

        // If already logged in, redirect to dashboard
        if ($userId && $sessionToken) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}