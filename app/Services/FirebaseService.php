<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class FirebaseService
{
    private $projectId;
    private $serviceAccountPath;
    private $credentialsPath;
    private $accessToken;

    public function __construct()
    {
        $fcmConfig = Config::get('push.fcm');
        $this->credentialsPath = $fcmConfig['credentials_path'] ?? config_path('firebase-credentials.json');
        $this->serviceAccountPath = $fcmConfig['service_account_path'] ?? env('FIREBASE_SERVICE_ACCOUNT_PATH', storage_path('app/firebase-service-account.json'));
        
        // Load project ID from credentials
        $this->loadProjectId();
    }

    /**
     * Load project ID from Firebase credentials JSON
     */
    private function loadProjectId()
    {
        if (File::exists($this->credentialsPath)) {
            $credentials = json_decode(File::get($this->credentialsPath), true);
            if (isset($credentials['project_info']['project_id'])) {
                $this->projectId = $credentials['project_info']['project_id'];
                return;
            }
        }

        // Fallback to environment variable
        $this->projectId = env('FIREBASE_PROJECT_ID', 'biltix-50deb');
    }

    /**
     * Get OAuth2 access token for FCM HTTP v1 API
     * Uses service account JSON file to generate JWT and exchange for access token
     */
    public function getAccessToken()
    {
        // Check cache first (tokens are valid for 1 hour)
        $cacheKey = 'firebase_access_token';
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Check if service account file exists
        if (!File::exists($this->serviceAccountPath)) {
            Log::warning('[Firebase] Service account file not found. Using legacy API fallback.');
            return null;
        }

        try {
            $serviceAccount = json_decode(File::get($this->serviceAccountPath), true);

            if (!isset($serviceAccount['type']) || $serviceAccount['type'] !== 'service_account') {
                Log::error('[Firebase] Invalid service account JSON format');
                return null;
            }

            // Generate JWT
            $jwt = $this->generateJWT($serviceAccount);

            // Exchange JWT for access token
            $tokenUrl = 'https://oauth2.googleapis.com/token';
            $response = Http::asForm()->post($tokenUrl, [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $accessToken = $data['access_token'] ?? null;
                
                if ($accessToken) {
                    // Cache for 55 minutes (tokens expire in 1 hour)
                    Cache::put($cacheKey, $accessToken, now()->addMinutes(55));
                    Log::info('[Firebase] Access token generated successfully');
                    return $accessToken;
                }
            }

            Log::error('[Firebase] Failed to get access token', [
                'response' => $response->json(),
                'status' => $response->status()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('[Firebase] Exception getting access token', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Generate JWT for service account authentication
     */
    private function generateJWT($serviceAccount)
    {
        $now = time();
        $expiry = $now + 3600; // 1 hour

        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT',
        ];

        $payload = [
            'iss' => $serviceAccount['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $expiry,
            'iat' => $now,
        ];

        $headerBase64 = $this->base64UrlEncode(json_encode($header));
        $payloadBase64 = $this->base64UrlEncode(json_encode($payload));

        $signatureInput = $headerBase64 . '.' . $payloadBase64;

        // Sign with private key
        $privateKey = openssl_pkey_get_private($serviceAccount['private_key']);
        if (!$privateKey) {
            throw new \Exception('Failed to load private key');
        }

        openssl_sign($signatureInput, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        openssl_free_key($privateKey);

        $signatureBase64 = $this->base64UrlEncode($signature);

        return $signatureInput . '.' . $signatureBase64;
    }

    /**
     * Base64 URL encode
     */
    private function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Get project ID
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * Check if service account is configured
     */
    public function hasServiceAccount()
    {
        return File::exists($this->serviceAccountPath);
    }
}

