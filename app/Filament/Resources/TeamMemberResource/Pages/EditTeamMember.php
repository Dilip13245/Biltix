<?php

namespace App\Filament\Resources\TeamMemberResource\Pages;

use App\Filament\Resources\TeamMemberResource;
use App\Helpers\NotificationHelper;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeamMember extends EditRecord
{
    protected static string $resource = TeamMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function afterSave(): void
    {
        $teamMember = $this->record->fresh();
        $oldData = $this->record->getOriginal();
        $project = \App\Models\Project::find($teamMember->project_id);
        
        // Check if role_in_project changed
        if (isset($oldData['role_in_project']) && $oldData['role_in_project'] != $teamMember->role_in_project && $project) {
            NotificationHelper::send(
                $teamMember->user_id,
                'team_role_updated',
                'Team Role Updated',
                "Your role in project '{$project->project_title}' has been changed to {$teamMember->role_in_project}",
                [
                    'project_id' => $project->id,
                    'project_title' => $project->project_title,
                    'old_role' => $oldData['role_in_project'],
                    'new_role' => $teamMember->role_in_project,
                    'updated_by' => auth()->id(),
                    'action_url' => "/projects/{$project->id}/team"
                ],
                'medium'
            );
        }
    }
}