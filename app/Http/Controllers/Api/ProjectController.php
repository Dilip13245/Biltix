<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Models\Task;
use App\Models\Inspection;
use App\Helpers\NumberHelper;
use App\Models\ProjectPhase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|in:residential,commercial,industrial,renovation',
                'location' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'budget' => 'nullable|numeric|min:0',
                'client_name' => 'nullable|string|max:255',
                'client_email' => 'nullable|email',
                'client_phone' => 'nullable|string|max:20',
                'project_manager_id' => 'nullable|integer',
            ], [
                'user_id.required' => trans('api.projects.user_id_required'),
                'name.required' => trans('api.projects.name_required'),
                'type.required' => trans('api.projects.type_required'),
                'location.required' => trans('api.projects.location_required'),
                'start_date.required' => trans('api.projects.start_date_required'),
                'end_date.required' => trans('api.projects.end_date_required'),
                'end_date.after' => trans('api.projects.end_date_after_start'),
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $projectDetails = new Project();
            $projectDetails->project_code = NumberHelper::generateProjectCode();
            $projectDetails->name = $request->name;
            $projectDetails->description = $request->description ?? '';
            $projectDetails->type = $request->type;
            $projectDetails->location = $request->location;
            $projectDetails->start_date = $request->start_date;
            $projectDetails->end_date = $request->end_date;
            $projectDetails->budget = $request->budget ?? 0;
            $projectDetails->client_name = $request->client_name ?? '';
            $projectDetails->client_email = $request->client_email ?? '';
            $projectDetails->client_phone = $request->client_phone ?? '';
            $projectDetails->project_manager_id = $request->project_manager_id ?? $request->user_id;
            $projectDetails->created_by = $request->user_id;
            $projectDetails->status = 'planning';
            $projectDetails->is_active = true;

            if ($projectDetails->save()) {
                return $this->toJsonEnc($projectDetails, trans('api.projects.created_success'), Config::get('constant.SUCCESS'));
            } else {
                return $this->toJsonEnc([], trans('api.projects.creation_failed'), Config::get('constant.ERROR'));
            }
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function list(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            $status = $request->input('status');
            $search = $request->input('search');
            $limit = $request->input('limit', 10);

            $query = Project::where('is_active', 1)->where('is_deleted', 0);

            // Role-based filtering
            $user = User::where('id', $user_id)->where('is_active', 1)->first();
            if ($user) {
                switch ($user->role) {
                    case 'contractor':
                        $query->where('created_by', $user_id);
                        break;
                    case 'project_manager':
                        $query->where('project_manager_id', $user_id);
                        break;
                    default:
                        // For other roles, show projects they're assigned to
                        break;
                }
            }

            if ($status) {
                $query->where('status', $status);
            }

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%");
                });
            }

            $projects = $query->paginate($limit);

            return $this->toJsonEnc($projects, trans('api.projects.list_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function details(Request $request)
    {
        try {
            $project_id = $request->input('project_id');
            $user_id = $request->input('user_id');

            $project = Project::where('id', $project_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$project) {
                return $this->toJsonEnc([], trans('api.projects.not_found'), Config::get('constant.NOT_FOUND'));
            }

            return $this->toJsonEnc($project, trans('api.projects.details_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function update(Request $request)
    {
        try {
            $project_id = $request->input('project_id');
            $user_id = $request->input('user_id');

            $project = Project::where('id', $project_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$project) {
                return $this->toJsonEnc([], trans('api.projects.not_found'), Config::get('constant.NOT_FOUND'));
            }

            if ($request->filled('name')) $project->name = $request->name;
            if ($request->filled('description')) $project->description = $request->description;
            if ($request->filled('status')) $project->status = $request->status;
            if ($request->filled('budget')) $project->budget = $request->budget;

            $project->save();

            return $this->toJsonEnc($project, trans('api.projects.updated_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function delete(Request $request)
    {
        try {
            $project_id = $request->input('project_id');
            $user_id = $request->input('user_id');

            $project = Project::where('id', $project_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$project) {
                return $this->toJsonEnc([], trans('api.projects.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $project->is_deleted = true;
            $project->save();

            return $this->toJsonEnc([], trans('api.projects.deleted_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function dashboardStats(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            
            $activeProjects = Project::where('status', 'active')
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->count();
                
            $pendingTasks = Task::where('status', 'pending')
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->count();
                
            $inspectionsDue = Inspection::where('status', 'scheduled')
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->count();
                
            $completedThisMonth = Project::where('status', 'completed')
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->whereMonth('updated_at', now()->month)
                ->count();
            
            $stats = [
                'active_projects' => $activeProjects,
                'pending_tasks' => $pendingTasks,
                'inspections_due' => $inspectionsDue,
                'completed_this_month' => $completedThisMonth,
            ];

            return $this->toJsonEnc($stats, trans('api.projects.stats_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function progressReport(Request $request)
    {
        try {
            $project_id = $request->input('project_id');
            $user_id = $request->input('user_id');

            $project = Project::where('id', $project_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$project) {
                return $this->toJsonEnc([], trans('api.projects.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $progressData = [
                'project_name' => $project->name,
                'overall_progress' => $project->progress_percentage,
                'start_date' => $project->start_date,
                'end_date' => $project->end_date,
                'status' => $project->status,
                'budget' => $project->budget,
                'actual_cost' => $project->actual_cost,
            ];

            return $this->toJsonEnc($progressData, trans('api.projects.progress_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function createPhase(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'project_id' => 'required|integer',
                'name' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $phaseDetails = new ProjectPhase();
            $phaseDetails->project_id = $request->project_id;
            $phaseDetails->name = $request->name;
            $phaseDetails->description = $request->description ?? '';
            $phaseDetails->phase_order = $request->phase_order ?? 1;
            $phaseDetails->start_date = $request->start_date;
            $phaseDetails->end_date = $request->end_date;
            $phaseDetails->budget_allocated = $request->budget_allocated ?? 0;
            $phaseDetails->created_by = $request->user_id;
            $phaseDetails->status = 'pending';
            $phaseDetails->is_active = true;

            if ($phaseDetails->save()) {
                return $this->toJsonEnc($phaseDetails, trans('api.projects.phase_created'), Config::get('constant.SUCCESS'));
            } else {
                return $this->toJsonEnc([], trans('api.projects.phase_creation_failed'), Config::get('constant.ERROR'));
            }
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function listPhases(Request $request)
    {
        try {
            $project_id = $request->input('project_id');
            $user_id = $request->input('user_id');

            $phases = ProjectPhase::where('project_id', $project_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->orderBy('phase_order')
                ->get();

            return $this->toJsonEnc($phases, trans('api.projects.phases_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function updatePhase(Request $request)
    {
        try {
            $phase_id = $request->input('phase_id');
            $user_id = $request->input('user_id');

            $phase = ProjectPhase::where('id', $phase_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$phase) {
                return $this->toJsonEnc([], trans('api.projects.phase_not_found'), Config::get('constant.NOT_FOUND'));
            }

            if ($request->filled('name')) $phase->name = $request->name;
            if ($request->filled('description')) $phase->description = $request->description;
            if ($request->filled('status')) $phase->status = $request->status;
            if ($request->filled('progress_percentage')) $phase->progress_percentage = $request->progress_percentage;

            $phase->save();

            return $this->toJsonEnc($phase, trans('api.projects.phase_updated'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function deletePhase(Request $request)
    {
        try {
            $phase_id = $request->input('phase_id');
            $user_id = $request->input('user_id');

            $phase = ProjectPhase::where('id', $phase_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$phase) {
                return $this->toJsonEnc([], trans('api.projects.phase_not_found'), Config::get('constant.NOT_FOUND'));
            }

            $phase->is_deleted = true;
            $phase->save();

            return $this->toJsonEnc([], trans('api.projects.phase_deleted'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function timeline(Request $request)
    {
        try {
            $project_id = $request->input('project_id');
            $user_id = $request->input('user_id');

            $project = Project::where('id', $project_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$project) {
                return $this->toJsonEnc([], trans('api.projects.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $phases = ProjectPhase::where('project_id', $project_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->orderBy('phase_order')
                ->get();

            $timelineData = [
                'project' => $project,
                'phases' => $phases,
                'total_duration' => $project->start_date->diffInDays($project->end_date),
                'days_elapsed' => $project->start_date->diffInDays(now()),
                'days_remaining' => now()->diffInDays($project->end_date),
            ];

            return $this->toJsonEnc($timelineData, trans('api.projects.timeline_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }
}