<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GanttActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GanttController extends Controller
{
    /**
     * List all activities for a project.
     */
    public function list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'code' => 400
            ]);
        }

        try {
            $activities = GanttActivity::where('project_id', $request->project_id)
                ->orderBy('start_date', 'asc')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Activities fetched successfully',
                'data' => $activities,
                'code' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch activities: ' . $e->getMessage(),
                'code' => 500
            ]);
        }
    }

    /**
     * Create a new activity.
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'workers_count' => 'nullable|integer|min:0',
            'equipment_count' => 'nullable|integer|min:0',
            'progress' => 'required|integer|min:0|max:100',
            'status' => 'nullable|string|in:planned,in_progress,completed,delayed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'code' => 400
            ]);
        }

        try {
            $status = $request->status;
            if (!$status) {
                $status = $this->determineStatus($request->start_date, $request->end_date, $request->progress);
            }

            $activity = GanttActivity::create([
                'project_id' => $request->project_id,
                'name' => $request->name,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'workers_count' => $request->workers_count ?? 0,
                'equipment_count' => $request->equipment_count ?? 0,
                'progress' => $request->progress,
                'status' => $status,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Activity created successfully',
                'data' => $activity,
                'code' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create activity: ' . $e->getMessage(),
                'code' => 500
            ]);
        }
    }

    /**
     * Update an activity.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'activity_id' => 'required|exists:gantt_activities,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'workers_count' => 'nullable|integer|min:0',
            'equipment_count' => 'nullable|integer|min:0',
            'progress' => 'required|integer|min:0|max:100',
            'status' => 'nullable|string|in:planned,in_progress,completed,delayed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'code' => 400
            ]);
        }

        try {
            $activity = GanttActivity::find($request->activity_id);

            // Check if user has access to this project/activity (assuming middleware handles auth, but good to check project ownership/access if needed)
            // For now, relying on API middleware.

            $status = $request->status;
            if (!$status) {
                $status = $this->determineStatus($request->start_date, $request->end_date, $request->progress);
            }

            $activity->update([
                'name' => $request->name,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'workers_count' => $request->workers_count ?? 0,
                'equipment_count' => $request->equipment_count ?? 0,
                'progress' => $request->progress,
                'status' => $status,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Activity updated successfully',
                'data' => $activity,
                'code' => 200
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update activity: ' . $e->getMessage(),
                'code' => 500
            ]);
        }
    }

    /**
     * Delete an activity.
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'activity_id' => 'required|exists:gantt_activities,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'code' => 400
            ]);
        }

        try {
            $activity = GanttActivity::find($request->activity_id);
            $activity->delete();

            return response()->json([
                'status' => true,
                'message' => 'Activity deleted successfully',
                'code' => 200
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete activity: ' . $e->getMessage(),
                'code' => 500
            ]);
        }
    }

    private function determineStatus($startDate, $endDate, $progress)
    {
        $now = now()->format('Y-m-d');
        $start = \Carbon\Carbon::parse($startDate)->format('Y-m-d');
        $end = \Carbon\Carbon::parse($endDate)->format('Y-m-d');

        if ($progress == 100) {
            return 'completed';
        }

        if ($end < $now) {
            return 'delayed';
        }

        if ($start > $now) {
            return 'planned';
        }

        return 'in_progress';
    }
}
