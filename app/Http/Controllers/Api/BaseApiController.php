<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\EncryptDecrypt;
use Illuminate\Support\Facades\Config;
use stdClass;

abstract class BaseApiController extends Controller
{
    /**
     * Success response format
     */
    protected function successResponse($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        $response = [
            'code' => $code,
            'message' => $message,
            'data' => $data ?? new stdClass(),
        ];
        
        if (Config::get('constant.ENCRYPTION_ENABLED') == 1) {
            return response()->json(EncryptDecrypt::bodyEncrypt(json_encode($response)), $code);
        }
        
        return response()->json($response, $code);
    }
    
    /**
     * Error response format
     */
    protected function errorResponse(string $message = 'An error occurred', int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'code' => $code,
            'message' => $message,
            'data' => new stdClass(),
        ];
        
        if ($errors) {
            $response['errors'] = $errors;
        }
        
        if (Config::get('constant.ENCRYPTION_ENABLED') == 1) {
            return response()->json(EncryptDecrypt::bodyEncrypt(json_encode($response)), $code);
        }
        
        return response()->json($response, $code);
    }
    
    /**
     * Validation error response
     */
    protected function validationErrorResponse($errors): JsonResponse
    {
        return $this->errorResponse('Validation failed', 422, $errors);
    }
    
    /**
     * Unauthorized response
     */
    protected function unauthorizedResponse(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->errorResponse($message, 401);
    }
    
    /**
     * Forbidden response
     */
    protected function forbiddenResponse(string $message = 'Forbidden'): JsonResponse
    {
        return $this->errorResponse($message, 403);
    }
    
    /**
     * Not found response
     */
    protected function notFoundResponse(string $message = 'Resource not found'): JsonResponse
    {
        return $this->errorResponse($message, 404);
    }
    
    /**
     * Internal server error response
     */
    protected function serverErrorResponse(string $message = 'Internal server error'): JsonResponse
    {
        return $this->errorResponse($message, 500);
    }
    
    /**
     * Validate request data
     */
    protected function validateRequest(Request $request, array $rules, array $messages = []): array
    {
        $validator = Validator::make($request->all(), $rules, $messages);
        
        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }
        
        return $validator->validated();
    }
    
    /**
     * Get paginated response
     */
    protected function paginatedResponse($data, int $total, int $perPage, int $currentPage): JsonResponse
    {
        $response = [
            'code' => 200,
            'message' => 'Success',
            'data' => $data,
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $currentPage,
                'last_page' => ceil($total / $perPage),
                'from' => ($currentPage - 1) * $perPage + 1,
                'to' => min($currentPage * $perPage, $total),
            ],
        ];
        
        if (Config::get('constant.ENCRYPTION_ENABLED') == 1) {
            return response()->json(EncryptDecrypt::bodyEncrypt(json_encode($response)), 200);
        }
        
        return response()->json($response, 200);
    }
    
    /**
     * Get user ID from request
     */
    protected function getUserId(Request $request): ?int
    {
        return $request->input('user_id') ?? $request->user()?->id;
    }
    
    /**
     * Get API version from request
     */
    protected function getApiVersion(Request $request): string
    {
        return $request->attributes->get('api_version', 'v1');
    }
    
    /**
     * Handle exceptions
     */
    protected function handleException(\Exception $e): JsonResponse
    {
        // Log the exception
        \Log::error('API Exception: ' . $e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);
        
        // Return appropriate error response
        if ($e instanceof \Illuminate\Validation\ValidationException) {
            return $this->validationErrorResponse($e->errors());
        }
        
        if ($e instanceof \Illuminate\Auth\AuthenticationException) {
            return $this->unauthorizedResponse('Authentication required');
        }
        
        if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
            return $this->forbiddenResponse('Access denied');
        }
        
        // For production, return generic error message
        if (config('app.env') === 'production') {
            return $this->serverErrorResponse('An unexpected error occurred');
        }
        
        // For development, return detailed error
        return $this->serverErrorResponse($e->getMessage());
    }
}
