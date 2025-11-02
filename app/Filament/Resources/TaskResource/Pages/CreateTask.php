<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use App\Helpers\NotificationHelper;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;
    
    protected function afterCreate(): void
    {
        $task = $this->record;
        $creator = \App\Models\User::find(auth()->id());
        
        // Notify assigned user
        if ($task->assigned_to) {
            NotificationHelper::send(
                $task->assigned_to,
                'task_assigned',
                'New Task Assigned',
                "Task '{$task->title}' has been assigned to you",
                [
                    'task_id' => $task->id,
                    'task_title' => $task->title,
                    'task_number' => $task->task_number,
                    'project_id' => $task->project_id,
                    'due_date' => $task->due_date ? $task->due_date->toDateString() : null,
                    'assigned_by' => auth()->id(),
                    'assigned_by_name' => $creator ? $creator->name : 'Admin',
                    'action_url' => "/tasks/{$task->id}"
                ],
                'high'
            );
        }
        
        // Notify project manager
        $project = \App\Models\Project::find($task->project_id);
        if ($project && $project->project_manager_id) {
            NotificationHelper::send(
                $project->project_manager_id,
                'task_created',
                'New Task Created',
                "Task '{$task->title}' created in project '{$project->project_title}'",
                [
                    'task_id' => $task->id,
                    'task_title' => $task->title,
                    'project_id' => $project->id,
                    'created_by' => auth()->id(),
                    'action_url' => "/tasks/{$task->id}"
                ],
                'medium'
            );
        }
    }
}