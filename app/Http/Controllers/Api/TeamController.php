<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Models\User;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    public function listMembers(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            $project_id = $request->input('project_id');
            $role_type = $request->input('role_type');
            $limit = $request->input('limit', 10);
            $page = $request->input('page', 1);

            $query = TeamMember::where('is_active', 1)->where('is_deleted', 0);

            if ($project_id) {
                $query->where('project_id', $project_id);
            }

            $teamMembers = $query->orderBy('created_at', 'desc')->paginate($limit, ['*'], 'page', $page)->items();

            // Add user details with role_name
            $teamMembers = collect($teamMembers)->map(function ($member) {
                $user = User::where('id', $member->user_id)
                    ->select('id', 'name', 'role', 'email', 'company_name', 'is_active')
                    ->first();
                
                if ($user) {
                    $user->role_name = str_replace('_', ' ', ucwords($user->role, '_'));
                    // Get company name from user's company_name field or default
                    $user->company = $user->company_name ?? 'BuildCorp Construction';
                    $user->status = $user->is_active ? 'Active' : 'Inactive';
                    $member->user = $user;
                }
                
                return $member;
            });

            return $this->toJsonEnc($teamMembers, trans('api.team.members_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function addMember(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'project_id' => 'required|integer',
                'member_user_id' => 'required|integer',
                'role_in_project' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            // Check if user already exists in the project
            $existing = TeamMember::where('user_id', $request->member_user_id)
                ->where('project_id', $request->project_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if ($existing) {
                return $this->toJsonEnc([], trans('api.team.already_assigned'), Config::get('constant.ERROR'));
            }

            $teamMemberDetails = new TeamMember();
            $teamMemberDetails->user_id = $request->member_user_id;
            $teamMemberDetails->project_id = $request->project_id;
            $teamMemberDetails->role_in_project = $request->role_in_project;
            $teamMemberDetails->assigned_at = now();
            $teamMemberDetails->assigned_by = $request->user_id;
            $teamMemberDetails->is_active = true;

            if ($teamMemberDetails->save()) {
                // Send notification for team member added
                $project = \App\Models\Project::find($request->project_id);
                $addedBy = \App\Models\User::find($request->user_id);
                $newMember = \App\Models\User::find($request->member_user_id);

                if ($project && $addedBy && $newMember) {
                    // Notify new member
                    NotificationHelper::send(
                        $request->member_user_id,
                        'team_member_added',
                        'Added to Project Team',
                        "You have been added to project '{$project->project_title}' team as {$request->role_in_project}",
                        [
                            'project_id' => $project->id,
                            'project_title' => $project->project_title,
                            'role_in_project' => $request->role_in_project,
                            'added_by' => $request->user_id,
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
                            'member_id' => $request->member_user_id,
                            'member_name' => $newMember->name,
                            'role_in_project' => $request->role_in_project,
                            'added_by' => $request->user_id,
                            'action_url' => "/projects/{$project->id}/team"
                        ],
                        'medium',
                        [$request->member_user_id, $request->user_id]
                    );
                }

                return $this->toJsonEnc($teamMemberDetails, trans('api.team.member_added'), Config::get('constant.SUCCESS'));
            } else {
                return $this->toJsonEnc([], trans('api.team.add_failed'), Config::get('constant.ERROR'));
            }
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function removeMember(Request $request)
    {
        try {
            $team_member_id = $request->input('team_member_id');
            $user_id = $request->input('user_id');

            $teamMember = TeamMember::where('id', $team_member_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$teamMember) {
                return $this->toJsonEnc([], trans('api.team.member_not_found'), Config::get('constant.NOT_FOUND'));
            }

            $teamMember->is_deleted = true;
            $teamMember->save();

            // Send notification for team member removed
            $project = \App\Models\Project::find($teamMember->project_id);
            $removedMember = \App\Models\User::find($teamMember->user_id);

            if ($project && $removedMember) {
                NotificationHelper::send(
                    $teamMember->user_id,
                    'team_member_removed',
                    'Removed from Project Team',
                    "You have been removed from project '{$project->project_title}' team",
                    [
                        'project_id' => $project->id,
                        'project_title' => $project->project_title,
                        'removed_by' => $user_id,
                        'action_url' => "/projects"
                    ],
                    'medium'
                );
            }

            return $this->toJsonEnc([], trans('api.team.member_removed'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function assignProject(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'project_id' => 'required|integer',
                'member_user_id' => 'required|integer',
                'role_in_project' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            // Check if already assigned
            $existing = TeamMember::where('user_id', $request->member_user_id)
                ->where('project_id', $request->project_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if ($existing) {
                return $this->toJsonEnc([], trans('api.team.already_assigned'), Config::get('constant.ERROR'));
            }

            $teamMemberDetails = new TeamMember();
            $teamMemberDetails->user_id = $request->member_user_id;
            $teamMemberDetails->project_id = $request->project_id;
            $teamMemberDetails->role_in_project = $request->role_in_project;
            $teamMemberDetails->assigned_at = now();
            $teamMemberDetails->assigned_by = $request->user_id;
            $teamMemberDetails->is_active = true;
            $teamMemberDetails->save();

            // Send notification for project assignment (same as addMember)
            $project = \App\Models\Project::find($request->project_id);
            $addedBy = \App\Models\User::find($request->user_id);
            $newMember = \App\Models\User::find($request->member_user_id);

            if ($project && $addedBy && $newMember) {
                NotificationHelper::send(
                    $request->member_user_id,
                    'team_member_added',
                    'Added to Project Team',
                    "You have been added to project '{$project->project_title}' team as {$request->role_in_project}",
                    [
                        'project_id' => $project->id,
                        'project_title' => $project->project_title,
                        'role_in_project' => $request->role_in_project,
                        'added_by' => $request->user_id,
                        'added_by_name' => $addedBy->name,
                        'action_url' => "/projects/{$project->id}/team"
                    ],
                    'high'
                );
            }

            return $this->toJsonEnc($teamMemberDetails, trans('api.team.project_assigned'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function memberDetails(Request $request)
    {
        try {
            $member_user_id = $request->input('member_user_id');
            $user_id = $request->input('user_id');

            $user = User::where('id', $member_user_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$user) {
                return $this->toJsonEnc([], trans('api.team.member_not_found'), Config::get('constant.NOT_FOUND'));
            }

            return $this->toJsonEnc($user, trans('api.team.member_details_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function updateRole(Request $request)
    {
        try {
            $team_member_id = $request->input('team_member_id');
            $role_in_project = $request->input('role_in_project');
            $user_id = $request->input('user_id');

            $teamMember = TeamMember::where('id', $team_member_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$teamMember) {
                return $this->toJsonEnc([], trans('api.team.member_not_found'), Config::get('constant.NOT_FOUND'));
            }

            $oldRole = $teamMember->role_in_project;
            $teamMember->role_in_project = $role_in_project;
            $teamMember->save();

            // Send notification for role update
            $project = \App\Models\Project::find($teamMember->project_id);
            if ($project) {
                NotificationHelper::send(
                    $teamMember->user_id,
                    'team_role_updated',
                    'Team Role Updated',
                    "Your role in project '{$project->project_title}' has been changed to {$role_in_project}",
                    [
                        'project_id' => $project->id,
                        'project_title' => $project->project_title,
                        'old_role' => $oldRole,
                        'new_role' => $role_in_project,
                        'updated_by' => $user_id,
                        'action_url' => "/projects/{$project->id}/team"
                    ],
                    'medium'
                );
            }

            return $this->toJsonEnc($teamMember, trans('api.team.role_updated'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }
}