<?php

namespace App\Filament\Resources\TeamMemberResource\Pages;

use App\Filament\Resources\TeamMemberResource;
use App\Helpers\NotificationHelper;
use Filament\Resources\Pages\CreateRecord;

class CreateTeamMember extends CreateRecord
{
    protected static string $resource = TeamMemberResource::class;
    
    protected function afterCreate(): void
    {
        $teamMember = $this->record;
        $addedBy = \App\Models\User::find(auth()->id());
        $newMember = \App\Models\User::find($teamMember->user_id);
        $project = \App\Models\Project::find($teamMember->project_id);
        
        if ($project && $addedBy && $newMember) {
            // Notify new member
            NotificationHelper::send(
                $teamMember->user_id,
                'team_member_added',
                'Added to Project Team',
                "You have been added to project '{$project->project_title}' team as {$teamMember->role_in_project}",
                [
                    'project_id' => $project->id,
                    'project_title' => $project->project_title,
                    'role_in_project' => $teamMember->role_in_project,
                    'added_by' => auth()->id(),
                    'added_by_name' => $addedBy->name,
                    'action_url' => "/projects/{$project->id}/team"
                ],
                'high'
            );
            
            // Notify project team
            NotificationHelper::sendToProjectTeam(
                $project->id,
                'team_member_added',
                'New Team Member Added',
                "{$newMember->name} has been added to project team",
                [
                    'project_id' => $project->id,
                    'project_title' => $project->project_title,
                    'member_id' => $teamMember->user_id,
                    'member_name' => $newMember->name,
                    'role_in_project' => $teamMember->role_in_project,
                    'added_by' => auth()->id(),
                    'action_url' => "/projects/{$project->id}/team"
                ],
                'medium',
                [$teamMember->user_id, auth()->id()]
            );
        }
    }
}