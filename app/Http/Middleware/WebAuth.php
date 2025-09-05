<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use App\Models\UserDevice;

class WebAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated via session
        $userId = Session::get('user_id');
        $token = Session::get('api_token');
        
        \Log::info('WebAuth check', ['user_id' => $userId, 'token' => $token ? 'exists' : 'missing', 'path' => $request->getPathInfo()]);
        
        if (!$userId || !$token) {
            return $this->redirectToLogin($request);
        }
        
        // Verify token is still valid in database
        $userDevice = UserDevice::where('user_id', $userId)
            ->where('token', $token)
            ->first();
            
        if (!$userDevice) {
            // Token invalid, clear session and redirect
            Session::flush();
            return $this->redirectToLogin($request);
        }
        
        // Add user info to request for controllers
        $request->merge([
            'auth_user_id' => $userId,
            'auth_token' => $token
        ]);
        
        return $next($request);
    }
    
    private function redirectToLogin(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required',
                'redirect' => route('login')
            ], 401);
        }
        
        return redirect()->route('login')->with('error', 'Please login to continue');
    }
}