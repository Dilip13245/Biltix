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

        // Store in Laravel session
        session([
            'user_id' => $request->user_id,
            'token' => $request->token,
            'user' => $request->user,
            'logged_in' => true
        ]);

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
        session()->flush();
        return response()->json(['success' => true]);
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
}