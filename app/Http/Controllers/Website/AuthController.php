<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Services\Api\AuthApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $authApi;

    public function __construct()
    {
        $this->authApi = new AuthApiService();
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

    public function setSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'token' => 'required',
            'user' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'code' => 422
            ], 422);
        }

        // Store in Laravel session
        Session::put('user_id', $request->user_id);
        Session::put('api_token', $request->token);
        Session::put('user', $request->user);
        
        return response()->json([
            'success' => true,
            'message' => 'Session set successfully',
            'code' => 200
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'password' => 'required|min:6',
            'role' => 'required|in:contractor,consultant,site_engineer,project_manager,stakeholder',
            'company_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'code' => 422
            ], 422);
        }

        // Call API for registration
        $response = $this->authApi->signup([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
            'role' => $request->role,
            'company_name' => $request->company_name,
            'designation' => $request->designation,
            'employee_count' => $request->employee_count,
            'device_type' => 'W',
            'members' => $request->members ?? [],
        ]);

        if ($response->isSuccess()) {
            $userData = $response->getData();
            
            // Auto-login after registration
            Session::put('user_id', $userData['id']);
            Session::put('api_token', $userData['token']);
            Session::put('user', $userData);
            
            return response()->json([
                'success' => true,
                'message' => $response->getMessage(),
                'data' => $userData,
                'code' => 200
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $response->getMessage(),
            'code' => $response->getCode()
        ], $response->getCode());
    }

    public function logout(Request $request)
    {
        // Clear Laravel session completely
        Session::flush();
        Session::invalidate();
        Session::regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully',
                'code' => 200
            ]);
        }

        return redirect()->route('login')->with('success', 'Logged out successfully');
    }

    public function verifySession(Request $request)
    {
        $userId = Session::get('user_id');
        $token = Session::get('api_token');
        
        if (!$userId || !$token) {
            return response()->json([
                'success' => false,
                'message' => 'No active session',
                'code' => 401
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Session valid',
            'code' => 200
        ]);
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'code' => 422
            ], 422);
        }

        $response = $this->authApi->sendOtp([
            'type' => 'forgot',
            'phone' => $request->phone,
        ]);

        return response()->json([
            'success' => $response->isSuccess(),
            'message' => $response->getMessage(),
            'data' => $response->getData(),
            'code' => $response->getCode()
        ], $response->getCode());
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'otp' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'code' => 422
            ], 422);
        }

        $response = $this->authApi->verifyOtp([
            'phone' => $request->phone,
            'otp' => $request->otp,
            'type' => 'forgot',
        ]);

        return response()->json([
            'success' => $response->isSuccess(),
            'message' => $response->getMessage(),
            'data' => $response->getData(),
            'code' => $response->getCode()
        ], $response->getCode());
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'code' => 422
            ], 422);
        }

        $response = $this->authApi->resetPassword([
            'phone' => $request->phone,
            'new_password' => $request->new_password,
            'confirm_password' => $request->confirm_password,
        ]);

        return response()->json([
            'success' => $response->isSuccess(),
            'message' => $response->getMessage(),
            'data' => $response->getData(),
            'code' => $response->getCode()
        ], $response->getCode());
    }
}