<?php

namespace App\Filament\Resources\InspectionResource\Pages;

use App\Filament\Resources\InspectionResource;
use App\Helpers\NotificationHelper;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInspection extends EditRecord
{
    protected static string $resource = InspectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function afterSave(): void
    {
        $inspection = $this->record->fresh();
        $oldData = $this->record->getOriginal();
        
        // Check if inspected_by changed
        if (isset($oldData['inspected_by']) && $oldData['inspected_by'] != $inspection->inspected_by) {
            // Notify old inspector if removed
            if ($oldData['inspected_by']) {
                NotificationHelper::send(
                    $oldData['inspected_by'],
                    'inspection_unassigned',
                    'Inspection Unassigned',
                    "{$inspection->category} inspection has been unassigned from you",
                    [
                        'inspection_id' => $inspection->id,
                        'project_id' => $inspection->project_id,
                        'action_url' => "/inspections"
                    ],
                    'medium'
                );
            }
            
            // Notify new inspector if assigned
            if ($inspection->inspected_by) {
                NotificationHelper::send(
                    $inspection->inspected_by,
                    'inspection_assigned',
                    'Inspection Assigned to You',
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
        
        // Check if status changed
        if (isset($oldData['status']) && $oldData['status'] != $inspection->status) {
            $project = \App\Models\Project::find($inspection->project_id);
            if ($project) {
                NotificationHelper::sendToProjectTeam(
                    $project->id,
                    'inspection_status_changed',
                    'Inspection Status Updated',
                    "{$inspection->category} inspection status changed to " . ucfirst(str_replace('_', ' ', $inspection->status)),
                    [
                        'inspection_id' => $inspection->id,
                        'project_id' => $project->id,
                        'category' => $inspection->category,
                        'old_status' => $oldData['status'],
                        'new_status' => $inspection->status,
                        'changed_by' => auth()->id(),
                        'action_url' => "/inspections/{$inspection->id}"
                    ],
                    'medium',
                    [auth()->id()]
                );
            }
        }
    }
}