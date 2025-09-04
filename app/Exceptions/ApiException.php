<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Helpers\EncryptDecrypt;
use Illuminate\Support\Facades\Config;

class ApiException extends Exception
{
    protected $statusCode;
    protected $errors;
    
    public function __construct(string $message = 'API Error', int $statusCode = 400, array $errors = [])
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
        $this->errors = $errors;
    }
    
    /**
     * Get the status code
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    
    /**
     * Get the errors
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
    
    /**
     * Render the exception as an HTTP response
     */
    public function render(): JsonResponse
    {
        $response = [
            'code' => $this->statusCode,
            'message' => $this->getMessage(),
            'data' => new \stdClass(),
        ];
        
        if (!empty($this->errors)) {
            $response['errors'] = $this->errors;
        }
        
        if (Config::get('constant.ENCRYPTION_ENABLED') == 1) {
            return response()->json(EncryptDecrypt::bodyEncrypt(json_encode($response)), $this->statusCode);
        }
        
        return response()->json($response, $this->statusCode);
    }
}
