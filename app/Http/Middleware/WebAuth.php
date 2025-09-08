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
        $lastActivity = session('last_activity');
        
        // Update last activity for active sessions
        if ($userId && $sessionToken) {
            session(['last_activity' => time()]);
        }

        // Check if session expired for non-remember-me logins
        if (!$rememberMe && $lastActivity) {
            // For non-remember-me: expire after 30 minutes of inactivity
            if (time() - $lastActivity > 30 * 60) {
                \Log::info('Session expired due to inactivity', ['inactive_time' => time() - $lastActivity]);
                session()->flush();
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['authenticated' => false], 401);
                }
                return redirect()->route('login')->with('error', 'Session expired due to inactivity. Please login again');
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