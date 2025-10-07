<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Config;

class UserController extends Controller
{
    /**
     * Get list of project managers for dropdown
     */
    public function getProjectManagers(Request $request)
    {
        try {
            $projectManagers = User::where('role', 'project_manager')
                ->where('is_active', 1)
                ->where('is_verified', 1)
                ->where('is_deleted', 0)
                ->select('id', 'name', 'email', 'company_name')
                ->orderBy('name', 'asc')
                ->get();

            return $this->toJsonEnc($projectManagers, trans('api.users.project_managers_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    /**
     * Get list of technical engineers for dropdown
     */
    public function getTechnicalEngineers(Request $request)
    {
        try {
            $technicalEngineers = User::where('role', 'site_engineer')
                ->where('is_active', 1)
                ->where('is_verified', 1)
                ->where('is_deleted', 0)
                ->select('id', 'name', 'email', 'company_name')
                ->orderBy('name', 'asc')
                ->get();

            return $this->toJsonEnc($technicalEngineers, trans('api.users.technical_engineers_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    /**
     * Get all users by role for dropdown
     */
    public function getUsersByRole(Request $request)
    {
        try {
            $role = $request->input('role');
            
            $query = User::where('is_active', 1)
                ->where('is_verified', 1)
                ->where('is_deleted', 0)
                ->select('id', 'name', 'email', 'company_name', 'role')
                ->orderBy('name', 'asc');
            
            if ($role) {
                $validRoles = ['project_manager', 'site_engineer', 'contractor', 'consultant', 'stakeholder'];
                
                if (!in_array($role, $validRoles)) {
                    return $this->toJsonEnc([], trans('api.users.invalid_role'), Config::get('constant.ERROR'));
                }
                
                $query->where('role', $role);
            }

            $users = $query->get();

            return $this->toJsonEnc($users, trans('api.users.users_by_role_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function getProjectTeamMembers(Request $request)
    {
        try {
            $project_id = $request->input('project_id');

            if (!$project_id) {
                return $this->toJsonEnc([], trans('api.tasks.project_id_required'), Config::get('constant.ERROR'));
            }

            $teamMembers = \App\Models\TeamMember::with('user')
                ->where('project_id', $project_id)
                ->where('is_active', true)
                ->where('is_deleted', false)
                ->get()
                ->map(function ($member) {
                    return [
                        'id' => $member->user->id,
                        'name' => $member->user->name,
                        'email' => $member->user->email,
                        'role_in_project' => $member->role_in_project
                    ];
                });

            return $this->toJsonEnc($teamMembers, trans('api.tasks.team_members_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }
}