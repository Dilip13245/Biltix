<?php

namespace App\Traits;

use App\Services\ApiResponse;
use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    protected function handleApiResponse(ApiResponse $response, $redirectOnSuccess = null, $redirectOnError = null)
    {
        if (request()->expectsJson()) {
            return response()->json($response->toArray(), $response->getCode());
        }

        if ($response->isSuccess()) {
            session()->flash('success', $response->getMessage());
            if ($redirectOnSuccess) {
                return redirect($redirectOnSuccess);
            }
        } else {
            session()->flash('error', $response->getMessage());
            if ($redirectOnError) {
                return redirect($redirectOnError);
            }
        }

        return back();
    }

    protected function successResponse($message, $data = [], $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'code' => $code
        ], $code);
    }

    protected function errorResponse($message, $data = [], $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data,
            'code' => $code
        ], $code);
    }

    protected function validationErrorResponse($errors, $message = 'Validation failed'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $errors,
            'code' => 422
        ], 422);
    }
}