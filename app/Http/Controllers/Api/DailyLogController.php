<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyLog;
use App\Models\DailyLogRoleDescription;
use App\Models\EquipmentLog;
use App\Models\StaffLog;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class DailyLogController extends Controller
{
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'project_id' => 'required|integer',
                'log_date' => 'required|date',
                'work_performed' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $dailyLogDetails = new DailyLog();
            $dailyLogDetails->project_id = $request->project_id;
            $dailyLogDetails->log_date = $request->log_date;
            $dailyLogDetails->logged_by = $request->user_id;
            $dailyLogDetails->weather_conditions = $request->weather_conditions ?? '';
            $dailyLogDetails->temperature = $request->temperature;
            $dailyLogDetails->work_performed = $request->work_performed;
            $dailyLogDetails->issues_encountered = $request->issues_encountered ?? '';
            $dailyLogDetails->notes = $request->notes ?? '';
            $dailyLogDetails->images = $request->images ?? [];
            $dailyLogDetails->is_active = true;

            if ($dailyLogDetails->save()) {
                // Send notification for daily log creation
                $project = \App\Models\Project::find($request->project_id);
                $logger = \App\Models\User::find($request->user_id);
                if ($project && $logger) {
                    // Direct notification to logger
                    NotificationHelper::send(
                        $request->user_id,
                        'daily_log_created',
                        'Daily Log Created Successfully',
                        "Daily log for {$request->log_date} has been created successfully",
                        [
                            'log_id' => $dailyLogDetails->id,
                            'project_id' => $project->id,
                            'log_date' => $request->log_date,
                            'action_url' => "/projects/{$project->id}/daily-logs/{$dailyLogDetails->id}"
                        ],
                        'low'
                    );
                    
                    // Notify project managers (excluding logger)
                    NotificationHelper::sendToProjectManagers(
                        $project->id,
                        'daily_log_created',
                        'Daily Log Entry Created',
                        "{$logger->name} created daily log for {$request->log_date}",
                        [
                            'log_id' => $dailyLogDetails->id,
                            'project_id' => $project->id,
                            'project_title' => $project->project_title,
                            'log_date' => $request->log_date,
                            'logged_by' => $request->user_id,
                            'logged_by_name' => $logger->name,
                            'action_url' => "/projects/{$project->id}/daily-logs/{$dailyLogDetails->id}"
                        ],
                        'low',
                        [$request->user_id]
                    );
                }

                return $this->toJsonEnc($dailyLogDetails, trans('api.daily_logs.created_success'), Config::get('constant.SUCCESS'));
            } else {
                return $this->toJsonEnc([], trans('api.daily_logs.creation_failed'), Config::get('constant.ERROR'));
            }
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function list(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            $project_id = $request->input('project_id');
            $log_date = $request->input('log_date');
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 10);

            $query = DailyLog::where('is_active', 1)->where('is_deleted', 0);

            if ($project_id) {
                $query->where('project_id', $project_id);
            }

            if ($log_date) {
                $query->where('log_date', $log_date);
            }

            $dailyLogs = $query->paginate($limit, ['*'], 'page', $page);

            $data = [
                'data' => $dailyLogs->items()
            ];

            return $this->toJsonEnc($data, trans('api.daily_logs.list_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function equipmentLogs(Request $request)
    {
        try {
            $daily_log_id = $request->input('daily_log_id');
            $equipment_data = $request->input('equipment_data', []);

            foreach ($equipment_data as $equipment) {
                $equipmentLog = new EquipmentLog();
                $equipmentLog->daily_log_id = $daily_log_id;
                $equipmentLog->equipment_id = $equipment['equipment_id'];
                $equipmentLog->equipment_type = $equipment['equipment_type'];
                $equipmentLog->operator_name = $equipment['operator_name'] ?? '';
                $equipmentLog->status = $equipment['status'] ?? 'active';
                $equipmentLog->hours_used = $equipment['hours_used'];
                $equipmentLog->location = $equipment['location'] ?? '';
                $equipmentLog->is_active = true;
                $equipmentLog->save();
            }

            return $this->toJsonEnc([], trans('api.daily_logs.equipment_logged'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function staffLogs(Request $request)
    {
        try {
            $daily_log_id = $request->input('daily_log_id');
            $staff_data = $request->input('staff_data', []);

            foreach ($staff_data as $staff) {
                $staffLog = new StaffLog();
                $staffLog->daily_log_id = $daily_log_id;
                $staffLog->staff_type = $staff['staff_type'];
                $staffLog->count = $staff['count'];
                $staffLog->hours_worked = $staff['hours_worked'];
                $staffLog->tasks_performed = $staff['tasks_performed'] ?? '';
                $staffLog->is_active = true;
                $staffLog->save();
            }

            return $this->toJsonEnc([], trans('api.daily_logs.staff_logged'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function stats(Request $request)
    {
        try {
            $project_id = $request->input('project_id');
            $log_date = $request->input('log_date', date('Y-m-d'));

            $dailyLog = DailyLog::where('project_id', $project_id)
                ->where('log_date', $log_date)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$dailyLog) {
                // Return empty stats instead of 404
                $stats = [
                    'active_equipment' => 0,
                    'staff_present' => 0,
                    'weather_conditions' => '',
                    'temperature' => null,
                    'log_date' => $log_date,
                    'has_log' => false
                ];
                return $this->toJsonEnc($stats, trans('api.daily_logs.no_log_found'), Config::get('constant.SUCCESS'));
            }

            $equipmentCount = EquipmentLog::where('daily_log_id', $dailyLog->id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->count();

            $staffCount = StaffLog::where('daily_log_id', $dailyLog->id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->sum('count');

            $stats = [
                'active_equipment' => $equipmentCount,
                'staff_present' => $staffCount,
                'weather_conditions' => $dailyLog->weather_conditions,
                'temperature' => $dailyLog->temperature,
                'log_date' => $log_date,
                'has_log' => true
            ];

            return $this->toJsonEnc($stats, trans('api.daily_logs.stats_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    /**
     * Add role-wise description for daily log (supports multiple descriptions in array)
     */
    public function addRoleDescription(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'project_id' => 'required|integer|exists:projects,id',
                'role' => 'required|string|max:255',
                'descriptions' => 'required|array|min:1',
                'descriptions.*' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            // Check if project exists and is active
            $project = \App\Models\Project::where('id', $request->project_id)
                ->where('is_active', true)
                ->where('is_deleted', false)
                ->first();

            if (!$project) {
                return $this->toJsonEnc([], trans('api.daily_logs.project_not_found_or_inactive'), Config::get('constant.NOT_FOUND'));
            }

            // Store multiple descriptions as separate records with same role
            $createdDescriptions = [];
            foreach ($request->descriptions as $description) {
                $roleDescription = DailyLogRoleDescription::create([
                    'project_id' => $request->project_id,
                    'created_by' => $request->user_id,
                    'role' => $request->role,
                    'description' => $description,
                    'is_active' => true,
                    'is_deleted' => false,
                ]);
                $createdDescriptions[] = $roleDescription;
            }

            return $this->toJsonEnc($createdDescriptions, trans('api.daily_logs.role_descriptions_added'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    /**
     * Update role-wise description for daily log
     */
    public function updateRoleDescription(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'role_description_id' => 'required|integer|exists:daily_log_role_descriptions,id',
                'role' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'is_active' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            // Find the role description
            $roleDescription = DailyLogRoleDescription::where('id', $request->role_description_id)
                ->where('is_deleted', false)
                ->first();

            if (!$roleDescription) {
                return $this->toJsonEnc([], trans('api.daily_logs.role_description_not_found'), Config::get('constant.NOT_FOUND'));
            }

            // Check if at least one field is provided for update
            if (!$request->has('role') && !$request->has('description') && !$request->has('is_active')) {
                return $this->toJsonEnc([], trans('api.daily_logs.provide_field_to_update'), Config::get('constant.ERROR'));
            }

            // Update fields if provided
            if ($request->has('role')) {
                $roleDescription->role = $request->role;
            }

            if ($request->has('description')) {
                $roleDescription->description = $request->description;
            }

            if ($request->has('is_active')) {
                $roleDescription->is_active = $request->is_active;
            }

            $roleDescription->save();

            return $this->toJsonEnc($roleDescription, trans('api.daily_logs.role_description_updated'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    /**
     * List role descriptions (today's date only, filtered by project_id)
     */
    public function listRoleDescriptions(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'project_id' => 'required|integer|exists:projects,id',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            // Get today's date
            $today = date('Y-m-d');
            
            $roleDescriptions = DailyLogRoleDescription::where('project_id', $request->project_id)
                ->where('is_active', true)
                ->where('is_deleted', false)
                ->whereDate('created_at', $today)
                ->orderBy('created_at', 'desc')
                ->get();

            return $this->toJsonEnc($roleDescriptions, trans('api.daily_logs.role_descriptions_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }
}