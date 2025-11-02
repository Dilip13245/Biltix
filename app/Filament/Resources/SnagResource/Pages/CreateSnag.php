<?php

namespace App\Filament\Resources\SnagResource\Pages;

use App\Filament\Resources\SnagResource;
use App\Helpers\NotificationHelper;
use Filament\Resources\Pages\CreateRecord;

class CreateSnag extends CreateRecord
{
    protected static string $resource = SnagResource::class;
    
    protected function afterCreate(): void
    {
        $snag = $this->record;
        $creator = \App\Models\User::find(auth()->id());
        
        // Notify project managers
        $project = \App\Models\Project::find($snag->project_id);
        if ($project && $creator) {
            NotificationHelper::sendToProjectManagers(
                $project->id,
                'snag_reported',
                'New Snag Reported',
                "Snag '{$snag->title}' reported in project '{$project->project_title}'",
                [
                    'snag_id' => $snag->id,
                    'snag_number' => $snag->snag_number,
                    'snag_title' => $snag->title,
                    'project_id' => $project->id,
                    'project_title' => $project->project_title,
                    'location' => $snag->location,
                    'reported_by' => auth()->id(),
                    'reported_by_name' => $creator->name,
                    'assigned_to' => $snag->assigned_to,
                    'action_url' => "/snags/{$snag->id}"
                ],
                'high'
            );
            
            // Notify assigned user if different from reporter
            if ($snag->assigned_to && $snag->assigned_to != auth()->id()) {
                NotificationHelper::send(
                    $snag->assigned_to,
                    'snag_assigned',
                    'Snag Assigned to You',
                    "Snag '{$snag->title}' has been assigned to you",
                    [
                        'snag_id' => $snag->id,
                        'snag_title' => $snag->title,
                        'project_id' => $project->id,
                        'assigned_by' => auth()->id(),
                        'action_url' => "/snags/{$snag->id}"
                    ],
                    'high'
                );
            }
        }
    }
}
