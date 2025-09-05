<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use App\Models\UserDevice;

class WebApiAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // For AJAX API calls from website - check both session and header
        $userId = Session::get('user_id') ?: $request->input('user_id');
        $token = Session::get('api_token') ?: $request->header('token');
        
        if (!$userId || !$token) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required',
                'code' => 401
            ], 401);
        }
        
        // Verify token is still valid
        $userDevice = UserDevice::where('user_id', $userId)
            ->where('token', $token)
            ->first();
            
        if (!$userDevice) {
            Session::flush();
            return response()->json([
                'success' => false,
                'message' => 'Session expired',
                'code' => 401
            ], 401);
        }
        
        // Add user info to request
        $request->merge([
            'user_id' => $userId,
            'auth_token' => $token
        ]);
        
        return $next($request);
    }
}