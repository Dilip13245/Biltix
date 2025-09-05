<?php

namespace App\Services\Api;

use App\Services\ApiService;

class AuthApiService extends ApiService
{
    public function signup($data)
    {
        return $this->post('v1/auth/signup', $data);
    }

    public function login($data)
    {
        $response = $this->post('v1/auth/login', $data);
        
        if ($response->isSuccess() && isset($response->data['token'])) {
            $this->setAuthToken($response->data['token']);
        }
        
        return $response;
    }

    public function sendOtp($data)
    {
        return $this->post('v1/auth/send_otp', $data);
    }

    public function verifyOtp($data)
    {
        return $this->post('v1/auth/verify_otp', $data);
    }

    public function resetPassword($data)
    {
        return $this->post('v1/auth/reset_password', $data);
    }

    public function getUserProfile($data = [])
    {
        return $this->post('v1/auth/get_user_profile', $data);
    }

    public function updateProfile($data)
    {
        return $this->post('v1/auth/update_profile', $data);
    }

    public function logout($data = [])
    {
        $response = $this->post('v1/auth/logout', $data);
        
        if ($response->isSuccess()) {
            $this->clearAuthToken();
        }
        
        return $response;
    }

    public function deleteAccount($data = [])
    {
        $response = $this->post('v1/auth/delete_account', $data);
        
        if ($response->isSuccess()) {
            $this->clearAuthToken();
        }
        
        return $response;
    }
}