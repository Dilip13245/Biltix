<?php

namespace App\Filament\Resources\InspectionResource\Pages;

use App\Filament\Resources\InspectionResource;
use App\Helpers\NotificationHelper;
use Filament\Resources\Pages\CreateRecord;

class CreateInspection extends CreateRecord
{
    protected static string $resource = InspectionResource::class;
    
    protected function afterCreate(): void
    {
        $inspection = $this->record;
        $creator = \App\Models\User::find(auth()->id());
        
        // Notify project team about new inspection
        $project = \App\Models\Project::find($inspection->project_id);
        if ($project) {
            NotificationHelper::sendToProjectTeam(
                $project->id,
                'inspection_created',
                'New Inspection Scheduled',
                "New {$inspection->category} inspection scheduled for project '{$project->project_title}'",
                [
                    'inspection_id' => $inspection->id,
                    'project_id' => $project->id,
                    'project_title' => $project->project_title,
                    'category' => $inspection->category,
                    'created_by' => auth()->id(),
                    'created_by_name' => $creator ? $creator->name : 'Admin',
                    'action_url' => "/inspections/{$inspection->id}"
                ],
                'high',
                [auth()->id()]
            );
        }
        
        // Notify inspected_by if assigned
        if ($inspection->inspected_by) {
            NotificationHelper::send(
                $inspection->inspected_by,
                'inspection_assigned',
                'New Inspection Assigned',
                "You have been assigned to {$inspection->category} inspection",
                [
                    'inspection_id' => $inspection->id,
                    'project_id' => $inspection->project_id,
                    'category' => $inspection->category,
                    'assigned_by' => auth()->id(),
                    'action_url' => "/inspections/{$inspection->id}"
                ],
                'high'
            );
        }
    }
}