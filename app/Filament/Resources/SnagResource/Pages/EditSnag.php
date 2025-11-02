<?php

namespace App\Filament\Resources\SnagResource\Pages;

use App\Filament\Resources\SnagResource;
use App\Helpers\NotificationHelper;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSnag extends EditRecord
{
    protected static string $resource = SnagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function afterSave(): void
    {
        $snag = $this->record->fresh();
        $oldData = $this->record->getOriginal();
        
        // Check if assigned_to changed
        if (isset($oldData['assigned_to']) && $oldData['assigned_to'] != $snag->assigned_to) {
            // Notify old assignee if removed
            if ($oldData['assigned_to']) {
                NotificationHelper::send(
                    $oldData['assigned_to'],
                    'snag_unassigned',
                    'Snag Unassigned',
                    "Snag '{$snag->title}' has been unassigned from you",
                    [
                        'snag_id' => $snag->id,
                        'snag_title' => $snag->title,
                        'project_id' => $snag->project_id,
                        'action_url' => "/snags"
                    ],
                    'medium'
                );
            }
            
            // Notify new assignee if assigned
            if ($snag->assigned_to) {
                NotificationHelper::send(
                    $snag->assigned_to,
                    'snag_assigned',
                    'Snag Assigned to You',
                    "Snag '{$snag->title}' has been assigned to you",
                    [
                        'snag_id' => $snag->id,
                        'snag_title' => $snag->title,
                        'project_id' => $snag->project_id,
                        'assigned_by' => auth()->id(),
                        'action_url' => "/snags/{$snag->id}"
                    ],
                    'high'
                );
            }
        }
        
        // Check if status changed
        if (isset($oldData['status']) && $oldData['status'] != $snag->status) {
            $project = \App\Models\Project::find($snag->project_id);
            if ($project) {
                $recipients = [$snag->reported_by];
                if ($project->project_manager_id) {
                    $recipients[] = $project->project_manager_id;
                }
                $recipients = array_unique(array_diff($recipients, [auth()->id()]));
                
                if (!empty($recipients)) {
                    NotificationHelper::send(
                        $recipients,
                        'snag_status_changed',
                        'Snag Status Updated',
                        "Snag '{$snag->title}' status changed to " . ucfirst(str_replace('_', ' ', $snag->status)),
                        [
                            'snag_id' => $snag->id,
                            'snag_title' => $snag->title,
                            'old_status' => $oldData['status'],
                            'new_status' => $snag->status,
                            'project_id' => $snag->project_id,
                            'changed_by' => auth()->id(),
                            'action_url' => "/snags/{$snag->id}"
                        ],
                        'medium'
                    );
                }
            }
        }
    }
}
