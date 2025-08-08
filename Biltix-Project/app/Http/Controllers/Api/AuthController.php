<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return $this->validateResponse($validator->errors());
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->toJson([], 'Invalid credentials', 401);
        }

        if (!$user->is_active) {
            return $this->toJson([], 'Account is deactivated', 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'designation' => $user->designation,
            'profile_image' => $user->profile_image ? asset('storage/users/' . $user->profile_image) : null,
            'token' => $token,
        ];

        return $this->toJson($userData, 'Login successful', 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string|max:20',
            'role' => 'required|string|in:manager,engineer,worker,inspector',
            'designation' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->validateResponse($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => $request->role,
            'designation' => $request->designation,
            'is_active' => true,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'designation' => $user->designation,
            'profile_image' => null,
            'token' => $token,
        ];

        return $this->toJson($userData, 'Registration successful', 201);
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'designation' => $user->designation,
            'profile_image' => $user->profile_image ? asset('storage/users/' . $user->profile_image) : null,
        ];

        return $this->toJson($userData, 'Profile retrieved successfully', 200);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'designation' => 'required|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->validateResponse($validator->errors());
        }

        $data = $request->only(['name', 'phone', 'designation']);

        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image) {
                \App\Helpers\ImageHelper::delete('users', $user->profile_image);
            }

            $imageName = \App\Helpers\ImageHelper::upload(
                $request->file('profile_image'),
                'users',
                $request->name
            );
            $data['profile_image'] = $imageName;
        }

        $user->update($data);

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'designation' => $user->designation,
            'profile_image' => $user->profile_image ? asset('storage/users/' . $user->profile_image) : null,
        ];

        return $this->toJson($userData, 'Profile updated successfully', 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->toJson([], 'Logged out successfully', 200);
    }
}