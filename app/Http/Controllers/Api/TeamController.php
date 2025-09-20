<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Models\User;
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

            $teamMembers = $query->paginate($limit, ['*'], 'page', $page)->items();

            // Add user details with role_name
            $teamMembers = collect($teamMembers)->map(function ($member) {
                $user = User::where('id', $member->user_id)
                    ->select('id', 'name', 'role')
                    ->first();
                
                if ($user) {
                    $user->role_name = str_replace('_', ' ', ucwords($user->role, '_'));
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

            $teamMemberDetails = new TeamMember();
            $teamMemberDetails->user_id = $request->member_user_id;
            $teamMemberDetails->project_id = $request->project_id;
            $teamMemberDetails->role_in_project = $request->role_in_project;
            $teamMemberDetails->assigned_at = now();
            $teamMemberDetails->assigned_by = $request->user_id;
            $teamMemberDetails->is_active = true;

            if ($teamMemberDetails->save()) {
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

            $teamMember->role_in_project = $role_in_project;
            $teamMember->save();

            return $this->toJsonEnc($teamMember, trans('api.team.role_updated'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }
}