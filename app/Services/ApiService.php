<?php

namespace App\Services;

use App\Helpers\EncryptDecrypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class ApiService
{
    private $baseUrl;
    private $apiKey;
    private $encryptionEnabled;
    private $timeout;
    private $headers;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('constant.API_BASE_URL', 'http://construction.stgserver.site/api'), '/');
        $this->apiKey = config('constant.API_KEY');
        $this->encryptionEnabled = config('constant.ENCRYPTION_ENABLED', 0);
        $this->timeout = 30;
        $this->setupHeaders();
    }

    private function setupHeaders()
    {
        $this->headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'api-key' => $this->encryptionEnabled ? EncryptDecrypt::bodyEncrypt($this->apiKey) : $this->apiKey,
            'language' => App::getLocale(),
        ];

        // Add token if user is authenticated
        if ($token = $this->getAuthToken()) {
            $this->headers['token'] = $this->encryptionEnabled ? EncryptDecrypt::bodyEncrypt($token) : $token;
        }
    }

    public function get($endpoint, $params = [])
    {
        return $this->makeRequest('GET', $endpoint, $params);
    }

    public function post($endpoint, $data = [])
    {
        return $this->makeRequest('POST', $endpoint, $data);
    }

    public function put($endpoint, $data = [])
    {
        return $this->makeRequest('PUT', $endpoint, $data);
    }

    public function delete($endpoint, $data = [])
    {
        return $this->makeRequest('DELETE', $endpoint, $data);
    }

    private function makeRequest($method, $endpoint, $data = [])
    {
        try {
            $url = $this->baseUrl . '/' . ltrim($endpoint, '/');
            
            Log::info("API Request: {$method} {$url}", [
                'data' => $data,
                'headers' => collect($this->headers)->except(['api-key', 'token'])->toArray()
            ]);

            $httpClient = Http::withHeaders($this->headers)->timeout($this->timeout);

            if ($method === 'GET') {
                $response = $httpClient->get($url, $data);
            } else {
                $requestBody = $this->prepareRequestBody($data);
                $response = $httpClient->send($method, $url, [
                    'body' => $requestBody
                ]);
            }

            return $this->handleResponse($response);

        } catch (\Exception $e) {
            Log::error("API Request Failed: {$method} {$endpoint}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return new ApiResponse(false, 'Connection error: ' . $e->getMessage(), [], 500);
        }
    }

    private function prepareRequestBody($data)
    {
        $jsonData = json_encode($data);
        
        if ($this->encryptionEnabled) {
            return EncryptDecrypt::bodyEncrypt($jsonData);
        }
        
        return $jsonData;
    }

    private function handleResponse($response)
    {
        $statusCode = $response->status();
        $body = $response->body();

        try {
            if ($this->encryptionEnabled) {
                $decryptedBody = EncryptDecrypt::bodyDecrypt($body);
                $responseData = json_decode($decryptedBody, true);
            } else {
                $responseData = $response->json();
            }

            $success = isset($responseData['code']) && $responseData['code'] == 200;
            $message = $responseData['message'] ?? 'Unknown response';
            $data = $responseData['data'] ?? [];
            $code = $responseData['code'] ?? $statusCode;

            Log::info("API Response: {$statusCode}", [
                'success' => $success,
                'message' => $message,
                'data_keys' => is_array($data) ? array_keys($data) : 'not_array'
            ]);

            return new ApiResponse($success, $message, $data, $code);

        } catch (\Exception $e) {
            Log::error("API Response Parse Error", [
                'error' => $e->getMessage(),
                'body' => $body
            ]);

            return new ApiResponse(false, 'Invalid response format', [], $statusCode);
        }
    }

    private function getAuthToken()
    {
        return Session::get('api_token') ?? Session::get('user_token');
    }

    public function setAuthToken($token)
    {
        Session::put('api_token', $token);
        $this->setupHeaders(); // Refresh headers with new token
        return $this;
    }

    public function clearAuthToken()
    {
        Session::forget('api_token');
        Session::forget('user_token');
        $this->setupHeaders(); // Refresh headers without token
        return $this;
    }

    public function withLanguage($language)
    {
        $this->headers['language'] = $language;
        return $this;
    }

    public function withTimeout($timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }
}