<?php

namespace App\Http\Requests\Task;

use App\Http\Requests\BaseApiRequest;

class CreateTaskRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'project_id' => 'required|integer|exists:projects,id',
            'phase_id' => 'nullable|integer|exists:project_phases,id',
            'assigned_to' => 'nullable|integer|exists:users,id',
            'priority' => 'required|string|in:low,medium,high,critical',
            'status' => 'nullable|string|in:pending,in_progress,completed,on_hold,cancelled',
            'due_date' => 'nullable|date|after_or_equal:today',
            'estimated_hours' => 'nullable|numeric|min:0',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'dependencies' => 'nullable|array',
            'dependencies.*' => 'integer|exists:tasks,id',
        ];
    }
    
    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'title.required' => 'Task title is required.',
            'project_id.required' => 'Project ID is required.',
            'project_id.exists' => 'Selected project does not exist.',
            'phase_id.exists' => 'Selected project phase does not exist.',
            'assigned_to.exists' => 'Selected user does not exist.',
            'priority.required' => 'Task priority is required.',
            'priority.in' => 'Invalid priority level selected.',
            'status.in' => 'Invalid status selected.',
            'due_date.after_or_equal' => 'Due date must be today or later.',
            'estimated_hours.numeric' => 'Estimated hours must be a valid number.',
            'estimated_hours.min' => 'Estimated hours must be a positive number.',
        ]);
    }
}
