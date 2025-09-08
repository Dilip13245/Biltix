<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\UserDevice;

class WebAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = session('user_id');
        $sessionToken = session('token');
        $rememberMe = session('remember_me', false);
        $sessionStartTime = session('session_start_time');
        
        // Session data loaded

        // Check if session expired for non-remember-me logins
        if (!$rememberMe && $sessionStartTime) {
            // For non-remember-me: expire after 30 minutes of inactivity
            if (time() - $sessionStartTime > 30 * 60) {
                \Log::info('Session expired for non-remember-me user', ['session_age' => time() - $sessionStartTime]);
                session()->flush();
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['authenticated' => false], 401);
                }
                return redirect()->route('login')->with('error', 'Session expired. Please login again');
            }
        }

        if (!$userId || !$sessionToken) {
            // For AJAX requests, return JSON instead of redirect
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['authenticated' => false], 401);
            }
            return redirect()->route('login')->with('error', 'Please login to continue');
        }

        // Verify token is still valid
        $deviceData = UserDevice::where('user_id', $userId)
            ->where('token', $sessionToken)
            ->first();

        if (!$deviceData) {
            session()->flush();
            // For AJAX requests, return JSON instead of redirect
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['authenticated' => false], 401);
            }
            return redirect()->route('login')->with('error', 'Session expired. Please login again');
        }

        // Get user and add to request
        $user = User::active()->find($userId);
        if (!$user) {
            session()->flush();
            // For AJAX requests, return JSON instead of redirect
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['authenticated' => false], 401);
            }
            return redirect()->route('login')->with('error', 'User not found or inactive');
        }

        // Share user with all views
        view()->share('currentUser', $user);
        $request->attributes->set('user', $user);

        $response = $next($request);
        
        // Add cache control headers to prevent back button access after logout
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        
        return $response;
    }
}