<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Services\ApiService;
use Illuminate\Http\Request;

class DocumentationController extends BaseApiController
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * Get API documentation
     */
    public function index(Request $request)
    {
        try {
            $docs = [
                'title' => 'Biltix API Documentation',
                'version' => $this->getApiVersion($request),
                'base_url' => config('app.url') . '/api',
                'authentication' => $this->getAuthenticationInfo(),
                'endpoints' => $this->getEndpoints(),
                'response_format' => $this->getResponseFormat(),
                'error_codes' => $this->getErrorCodes(),
                'rate_limiting' => $this->getRateLimitingInfo(),
            ];

            return $this->successResponse($docs, 'API documentation retrieved successfully');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get authentication information
     */
    private function getAuthenticationInfo(): array
    {
        return [
            'type' => 'API Key + Token',
            'api_key_header' => 'api-key',
            'token_header' => 'token',
            'description' => 'All requests require an API key in the header. Protected endpoints also require a user token.',
            'encryption' => config('constant.ENCRYPTION_ENABLED') == 1 ? 'Enabled' : 'Disabled',
        ];
    }

    /**
     * Get available endpoints
     */
    private function getEndpoints(): array
    {
        return [
            'auth' => [
                'POST /auth/signup' => 'User registration',
                'POST /auth/login' => 'User login',
                'POST /auth/logout' => 'User logout',
                'POST /auth/send_otp' => 'Send OTP for verification',
                'POST /auth/verify_otp' => 'Verify OTP',
                'POST /auth/reset_password' => 'Reset password',
                'POST /auth/get_user_profile' => 'Get user profile (Protected)',
                'POST /auth/update_profile' => 'Update user profile (Protected)',
            ],
            'projects' => [
                'POST /projects/create' => 'Create new project (Protected)',
                'POST /projects/list' => 'List projects (Protected)',
                'POST /projects/details' => 'Get project details (Protected)',
                'POST /projects/update' => 'Update project (Protected)',
                'POST /projects/delete' => 'Delete project (Protected)',
                'POST /projects/dashboard_stats' => 'Get dashboard statistics (Protected)',
            ],
            'tasks' => [
                'POST /tasks/create' => 'Create new task (Protected)',
                'POST /tasks/list' => 'List tasks (Protected)',
                'POST /tasks/details' => 'Get task details (Protected)',
                'POST /tasks/update' => 'Update task (Protected)',
                'POST /tasks/delete' => 'Delete task (Protected)',
                'POST /tasks/change_status' => 'Change task status (Protected)',
            ],
            'health' => [
                'GET /health' => 'Health check',
                'GET /health/status' => 'Detailed system status',
            ],
        ];
    }

    /**
     * Get response format information
     */
    private function getResponseFormat(): array
    {
        return [
            'success_response' => [
                'code' => 200,
                'message' => 'Success',
                'data' => 'Response data object',
                'meta' => [
                    'timestamp' => 'ISO 8601 timestamp',
                    'version' => 'API version',
                    'request_id' => 'Unique request identifier',
                    'response_time_ms' => 'Response time in milliseconds',
                ],
            ],
            'error_response' => [
                'code' => 400,
                'message' => 'Error message',
                'data' => 'Empty object',
                'errors' => 'Validation errors (if applicable)',
            ],
        ];
    }

    /**
     * Get error codes
     */
    private function getErrorCodes(): array
    {
        return [
            200 => 'Success',
            201 => 'Created',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            422 => 'Validation Error',
            429 => 'Too Many Requests',
            500 => 'Internal Server Error',
        ];
    }

    /**
     * Get rate limiting information
     */
    private function getRateLimitingInfo(): array
    {
        return [
            'limit' => config('api.rate_limiting.limit', 60),
            'window' => config('api.rate_limiting.window', 1) . ' minute(s)',
            'headers' => [
                'X-RateLimit-Limit' => 'Request limit per window',
                'X-RateLimit-Remaining' => 'Remaining requests in current window',
                'X-RateLimit-Reset' => 'Time when the rate limit resets',
            ],
        ];
    }
}
