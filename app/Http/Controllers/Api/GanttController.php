<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GanttActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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

            // Recalculate progress dynamically
            $activities->transform(function ($activity) {
                $activity->progress = $this->calculateProgress($activity->start_date, $activity->end_date, $activity->status);
                return $activity;
            });

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
            // Progress is now calculated, not input
            'status' => 'required|string|in:todo,in_progress,complete,approve',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'code' => 400
            ]);
        }

        try {
            $progress = $this->calculateProgress($request->start_date, $request->end_date, $request->status);

            $activity = GanttActivity::create([
                'project_id' => $request->project_id,
                'name' => $request->name,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'workers_count' => $request->workers_count ?? 0,
                'equipment_count' => $request->equipment_count ?? 0,
                'progress' => $progress,
                'status' => $request->status,
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
            'status' => 'required|string|in:todo,in_progress,complete,approve',
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

            $progress = $this->calculateProgress($request->start_date, $request->end_date, $request->status);

            $activity->update([
                'name' => $request->name,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'workers_count' => $request->workers_count ?? 0,
                'equipment_count' => $request->equipment_count ?? 0,
                'progress' => $progress,
                'status' => $request->status,
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

    private function calculateProgress($startDate, $endDate, $status)
    {
        // If completed or approved, force 100%
        if (in_array($status, ['complete', 'approve'])) {
            return 100;
        }

        $now = Carbon::now()->startOfDay();
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->startOfDay();

        // If future
        if ($now->lt($start)) {
            return 0;
        }

        // If past end date (and not completed), capping at 100 (Delay handled by status)
        if ($now->gt($end)) {
            return 100;
        }

        // Calculate percentage
        $totalDuration = $end->diffInDays($start) + 1; // +1 to include start day
        $elapsed = $now->diffInDays($start) + 1;

        if ($totalDuration <= 0) return 100;

        $percentage = ($elapsed / $totalDuration) * 100;

        return round(min(100, max(0, $percentage)));
    }
}
