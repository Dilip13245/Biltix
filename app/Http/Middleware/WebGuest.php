<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class WebGuest
{
    public function handle(Request $request, Closure $next): Response
    {
        // Only redirect from login/register/forgot-password pages
        $guestOnlyPaths = ['/login', '/register', '/forgot-password'];
        
        if (in_array($request->getPathInfo(), $guestOnlyPaths)) {
            if (Session::has('user_id') && Session::has('api_token')) {
                \Log::info('WebGuest middleware redirecting authenticated user from: ' . $request->getPathInfo());
                return redirect()->route('dashboard');
            }
        }
        
        return $next($request);
    }
}