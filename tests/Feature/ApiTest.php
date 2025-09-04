<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\UserDevice;

class ApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $apiKey;
    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->apiKey = config('constant.API_KEY');
        $this->user = User::factory()->create();
        $this->token = 'test-token-' . time();
        
        UserDevice::create([
            'user_id' => $this->user->id,
            'token' => $this->token,
            'device_type' => 'web',
            'device_token' => 'test-device-token',
        ]);
    }

    /**
     * Test health check endpoint
     */
    public function test_health_check()
    {
        $response = $this->getJson('/api/v1/health');
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'code',
                    'message',
                    'data' => [
                        'status',
                        'timestamp',
                        'version',
                        'environment',
                        'services',
                    ],
                ]);
    }

    /**
     * Test API key validation
     */
    public function test_api_key_validation()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(400)
                ->assertJson([
                    'code' => 401,
                    'message' => 'API key not found',
                ]);
    }

    /**
     * Test user login with valid API key
     */
    public function test_user_login_with_valid_api_key()
    {
        $response = $this->withHeaders([
            'api-key' => $this->apiKey,
        ])->postJson('/api/v1/auth/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'code',
                    'message',
                    'data',
                ]);
    }

    /**
     * Test protected endpoint without token
     */
    public function test_protected_endpoint_without_token()
    {
        $response = $this->withHeaders([
            'api-key' => $this->apiKey,
        ])->postJson('/api/v1/auth/get_user_profile', [
            'user_id' => $this->user->id,
        ]);

        $response->assertStatus(401)
                ->assertJson([
                    'code' => 401,
                    'message' => 'Token not found',
                ]);
    }

    /**
     * Test protected endpoint with valid token
     */
    public function test_protected_endpoint_with_valid_token()
    {
        $response = $this->withHeaders([
            'api-key' => $this->apiKey,
            'token' => $this->token,
        ])->postJson('/api/v1/auth/get_user_profile', [
            'user_id' => $this->user->id,
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'code',
                    'message',
                    'data',
                ]);
    }

    /**
     * Test rate limiting
     */
    public function test_rate_limiting()
    {
        $requests = 0;
        $maxRequests = 65; // Exceed the default limit of 60

        for ($i = 0; $i < $maxRequests; $i++) {
            $response = $this->withHeaders([
                'api-key' => $this->apiKey,
            ])->getJson('/api/v1/health');
            
            $requests++;
            
            if ($response->status() === 429) {
                break;
            }
        }

        $this->assertLessThanOrEqual($maxRequests, $requests);
    }

    /**
     * Test API documentation endpoint
     */
    public function test_api_documentation()
    {
        $response = $this->withHeaders([
            'api-key' => $this->apiKey,
        ])->getJson('/api/v1/docs');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'code',
                    'message',
                    'data' => [
                        'title',
                        'version',
                        'base_url',
                        'authentication',
                        'endpoints',
                        'response_format',
                        'error_codes',
                        'rate_limiting',
                    ],
                ]);
    }

    /**
     * Test validation error response
     */
    public function test_validation_error_response()
    {
        $response = $this->withHeaders([
            'api-key' => $this->apiKey,
        ])->postJson('/api/v1/auth/login', [
            'email' => 'invalid-email',
            'password' => '123', // Too short
        ]);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'code',
                    'message',
                    'data',
                    'errors',
                ]);
    }

    /**
     * Test security headers
     */
    public function test_security_headers()
    {
        $response = $this->getJson('/api/v1/health');

        $response->assertHeader('X-Content-Type-Options', 'nosniff')
                ->assertHeader('X-Frame-Options', 'DENY')
                ->assertHeader('X-XSS-Protection', '1; mode=block')
                ->assertHeader('X-API-Version', 'v1');
    }
}
