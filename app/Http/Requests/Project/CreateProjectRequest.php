<?php

namespace App\Http\Requests\Project;

use App\Http\Requests\BaseApiRequest;

class CreateProjectRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|string|in:residential,commercial,industrial,infrastructure',
            'status' => 'nullable|string|in:planning,active,on_hold,completed,cancelled',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'budget' => 'nullable|numeric|min:0',
            'location' => 'required|string|max:500',
            'client_name' => 'nullable|string|max:255',
            'client_contact' => 'nullable|string|max:255',
            'priority' => 'nullable|string|in:low,medium,high,critical',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
        ];
    }
    
    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'name.required' => 'Project name is required.',
            'type.required' => 'Project type is required.',
            'type.in' => 'Invalid project type selected.',
            'start_date.required' => 'Project start date is required.',
            'start_date.after_or_equal' => 'Start date must be today or later.',
            'end_date.after' => 'End date must be after start date.',
            'budget.numeric' => 'Budget must be a valid number.',
            'budget.min' => 'Budget must be a positive number.',
            'location.required' => 'Project location is required.',
            'priority.in' => 'Invalid priority level selected.',
        ]);
    }
}
