<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use App\Helpers\NotificationHelper;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTask extends EditRecord
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function afterSave(): void
    {
        $task = $this->record->fresh();
        $oldData = $this->record->getOriginal();
        $updater = \App\Models\User::find(auth()->id());
        
        // Check if assigned_to changed
        if (isset($oldData['assigned_to']) && $oldData['assigned_to'] != $task->assigned_to) {
            // Notify old assignee if removed
            if ($oldData['assigned_to']) {
                NotificationHelper::send(
                    $oldData['assigned_to'],
                    'task_unassigned',
                    'Task Unassigned',
                    "Task '{$task->title}' has been unassigned from you",
                    [
                        'task_id' => $task->id,
                        'task_title' => $task->title,
                        'project_id' => $task->project_id,
                        'action_url' => "/tasks"
                    ],
                    'medium'
                );
            }
            
            // Notify new assignee if assigned
            if ($task->assigned_to) {
                NotificationHelper::send(
                    $task->assigned_to,
                    'task_assigned',
                    'Task Assigned to You',
                    "Task '{$task->title}' has been assigned to you",
                    [
                        'task_id' => $task->id,
                        'task_title' => $task->title,
                        'project_id' => $task->project_id,
                        'assigned_by' => auth()->id(),
                        'assigned_by_name' => $updater ? $updater->name : 'Admin',
                        'action_url' => "/tasks/{$task->id}"
                    ],
                    'high'
                );
            }
        }
        
        // Check if status changed
        if (isset($oldData['status']) && $oldData['status'] != $task->status) {
            $project = \App\Models\Project::find($task->project_id);
            $recipients = [$task->created_by];
            if ($project && $project->project_manager_id) {
                $recipients[] = $project->project_manager_id;
            }
            $recipients = array_unique(array_diff($recipients, [auth()->id()]));
            
            if (!empty($recipients)) {
                NotificationHelper::send(
                    $recipients,
                    'task_status_changed',
                    'Task Status Updated',
                    "Task '{$task->title}' status changed to " . ucfirst(str_replace('_', ' ', $task->status)),
                    [
                        'task_id' => $task->id,
                        'task_title' => $task->title,
                        'old_status' => $oldData['status'],
                        'new_status' => $task->status,
                        'changed_by' => auth()->id(),
                        'project_id' => $task->project_id,
                        'action_url' => "/tasks/{$task->id}"
                    ],
                    'medium'
                );
            }
        }
        
        // Check if due_date changed
        if (isset($oldData['due_date']) && $oldData['due_date'] != $task->due_date && $task->assigned_to) {
            NotificationHelper::send(
                $task->assigned_to,
                'task_due_date_changed',
                'Task Due Date Changed',
                "Task '{$task->title}' due date has been updated",
                [
                    'task_id' => $task->id,
                    'task_title' => $task->title,
                    'old_due_date' => $oldData['due_date'],
                    'new_due_date' => $task->due_date ? $task->due_date->toDateString() : null,
                    'project_id' => $task->project_id,
                    'action_url' => "/tasks/{$task->id}"
                ],
                'medium'
            );
        }
    }
}