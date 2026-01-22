<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserDevice;
use App\Models\User;

class AuthController extends Controller
{
    public function setSession(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'token' => 'required',
            'user' => 'required'
        ]);

        $rememberMe = $request->boolean('remember_me', false);
        
        // Store in Laravel session
        session([
            'user_id' => $request->user_id,
            'token' => $request->token,
            'user' => $request->user,
            'logged_in' => true,
            'remember_me' => $rememberMe
        ]);
        
        // Set session expiry based on remember me
        if (!$rememberMe) {
            // Session only: Set timestamps and browser session ID
            session([
                'session_start_time' => time(),
                'last_activity' => time(),
                'browser_session_id' => uniqid('browser_', true)
            ]);
            // Remove remember me cookie if exists
            cookie()->queue(cookie()->forget('remember_me_token'));
        } else {
            // Remember me: Still track activity for security
            session(['last_activity' => time()]);
            // Set remember me cookie for middleware to detect
            // Cookie will persist for 30 days and be available across tabs
            $cookie = cookie('remember_me_token', $request->token, 30 * 24 * 60); // 30 days
            cookie()->queue($cookie);
        }

        return response()->json(['success' => true]);
    }

    public function checkSession(Request $request)
    {
        $userId = session('user_id');
        $sessionToken = session('token');

        if (!$userId || !$sessionToken) {
            return response()->json(['authenticated' => false]);
        }

        // Check if token is still valid in database
        $deviceData = UserDevice::where('user_id', $userId)
            ->where('token', $sessionToken)
            ->first();

        if (!$deviceData) {
            // Token changed or user logged out from another device
            session()->flush();
            return response()->json(['authenticated' => false]);
        }

        return response()->json(['authenticated' => true]);
    }

    public function logout(Request $request)
    {
        $userId = session('user_id');
        $sessionToken = session('token');
        
        // Invalidate device token in database
        if ($userId && $sessionToken) {
            UserDevice::where('user_id', $userId)
                ->where('token', $sessionToken)
                ->update([
                    'token' => '',
                    'is_active' => false
                ]);
        }
        
        // Clear session
        session()->flush();
        
        // Remove remember me cookie on logout
        cookie()->queue(cookie()->forget('remember_me_token'));
        
        // For AJAX requests, return JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }
        
        // For regular requests, redirect to login
        return redirect()->route('login')->with('success', 'Logged out successfully');
    }

    public function showLogin()
    {
        return view('website.auth.login');
    }

    public function showRegister()
    {
        return view('website.auth.register');
    }

    public function showForgotPassword()
    {
        return view('website.auth.forgot-password');
    }

    public function register(Request $request)
    {
        // Handle registration logic
        return response()->json(['success' => true]);
    }

    public function sendOtp(Request $request)
    {
        // Handle OTP sending
        return response()->json(['success' => true]);
    }

    public function verifyOtp(Request $request)
    {
        // Handle OTP verification
        return response()->json(['success' => true]);
    }

    public function resetPassword(Request $request)
    {
        // Handle password reset
        return response()->json(['success' => true]);
    }

    public function verifySession(Request $request)
    {
        // Handle session verification
        return response()->json(['authenticated' => true]);
    }

    /**
     * Handle payment completion callback from Moyasar
     */
    public function paymentComplete(Request $request)
    {
        return view('website.auth.payment-complete');
    }
}