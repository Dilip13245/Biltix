<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\EncryptDecrypt;
use Illuminate\Support\Facades\Config;

abstract class BaseApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    
    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors()->toArray();
        
        $response = [
            'code' => 422,
            'message' => 'Validation failed',
            'data' => new \stdClass(),
            'errors' => $errors,
        ];
        
        if (Config::get('constant.ENCRYPTION_ENABLED') == 1) {
            throw new HttpResponseException(
                response()->json(EncryptDecrypt::bodyEncrypt(json_encode($response)), 422)
            );
        }
        
        throw new HttpResponseException(
            response()->json($response, 422)
        );
    }
    
    /**
     * Get the validation rules that apply to the request.
     */
    abstract public function rules(): array;
    
    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'required' => 'The :attribute field is required.',
            'email' => 'The :attribute must be a valid email address.',
            'min' => 'The :attribute must be at least :min characters.',
            'max' => 'The :attribute may not be greater than :max characters.',
            'unique' => 'The :attribute has already been taken.',
            'exists' => 'The selected :attribute is invalid.',
            'numeric' => 'The :attribute must be a number.',
            'integer' => 'The :attribute must be an integer.',
            'boolean' => 'The :attribute field must be true or false.',
            'date' => 'The :attribute is not a valid date.',
            'date_format' => 'The :attribute does not match the format :format.',
            'in' => 'The selected :attribute is invalid.',
            'image' => 'The :attribute must be an image.',
            'mimes' => 'The :attribute must be a file of type: :values.',
            'max_file_size' => 'The :attribute may not be greater than :max kilobytes.',
        ];
    }
    
    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'email' => 'email address',
            'password' => 'password',
            'name' => 'name',
            'phone' => 'phone number',
            'title' => 'title',
            'description' => 'description',
            'status' => 'status',
            'type' => 'type',
            'category' => 'category',
            'priority' => 'priority',
            'due_date' => 'due date',
            'start_date' => 'start date',
            'end_date' => 'end date',
        ];
    }
}
