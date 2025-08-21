<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyLog;
use App\Models\EquipmentLog;
use App\Models\StaffLog;
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
            $limit = $request->input('limit', 10);

            $query = DailyLog::where('is_active', 1)->where('is_deleted', 0);

            if ($project_id) {
                $query->where('project_id', $project_id);
            }

            if ($log_date) {
                $query->where('log_date', $log_date);
            }

            $dailyLogs = $query->paginate($limit);

            return $this->toJsonEnc($dailyLogs, trans('api.daily_logs.list_retrieved'), Config::get('constant.SUCCESS'));
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
                return $this->toJsonEnc($stats, 'No daily log found for this date', Config::get('constant.SUCCESS'));
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
}