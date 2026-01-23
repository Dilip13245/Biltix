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
        
        // Check if session expired for non-remember-me logins BEFORE updating last_activity
        // This prevents false logout when user returns after short inactivity
        if (!$rememberMe && $lastActivity) {
            // For non-remember-me: expire after 8 hours of inactivity
            $inactiveTime = time() - $lastActivity;
            if ($inactiveTime > 8 * 60 * 60) {
                \Log::info('Session expired due to inactivity', ['inactive_time' => $inactiveTime]);
                session()->flush();
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['authenticated' => false], 401);
                }
                return redirect()->route('login')->with('error', 'Session expired due to inactivity. Please login again');
            }
        }
        
        // Update last activity for active sessions (after expiry check)
        if ($userId && $sessionToken) {
            session(['last_activity' => time()]);
        }

        if (!$userId || !$sessionToken) {
            // Check if remember me cookie exists
            $rememberMeCookie = $request->cookie('remember_me_token');
            if ($rememberMeCookie) {
                // Remember me cookie exists but session is missing
                // Redirect to login page where JavaScript will restore the session
                // This prevents accessing protected routes without valid session
                \Log::info('Session missing but remember me cookie found, redirecting to login for restoration');
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['authenticated' => false, 'redirect' => route('login')], 401);
                }
                return redirect()->route('login');
            } else {
                // No remember me - redirect to login
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['authenticated' => false], 401);
                }
                return redirect()->route('login')->with('error', 'Please login to continue');
            }
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
        
        // Log user in for this request so Auth::user() works (needed for Broadcasting)
        \Illuminate\Support\Facades\Auth::login($user);

        $response = $next($request);
        
        // Add cache control headers to prevent back button access after logout
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        
        return $response;
    }
}