<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProjectActivity;
use App\Models\ProjectManpowerEquipment;
use App\Models\ProjectSafetyItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class ProjectProgressController extends Controller
{
    // Project Activities APIs
    public function listActivities(Request $request)
    {
        try {
            $project_id = $request->input('project_id');
            
            if (!$project_id) {
                return $this->toJsonEnc([], trans('api.project_progress.project_id_required'), Config::get('constant.ERROR'));
            }

            $activities = ProjectActivity::where('project_id', $project_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->orderBy('created_at', 'desc')
                ->get();

            return $this->toJsonEnc($activities, trans('api.project_progress.activities_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function addActivity(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'project_id' => 'required|integer',
                'user_id' => 'required|integer',
                'description' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $activity = ProjectActivity::create([
                'project_id' => $request->project_id,
                'description' => $request->description,
                'created_by' => $request->user_id,
            ]);

            return $this->toJsonEnc($activity, trans('api.project_progress.activity_added'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function updateActivity(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'activity_id' => 'required|integer',
                'description' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $activity = ProjectActivity::where('id', $request->activity_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$activity) {
                return $this->toJsonEnc([], trans('api.project_progress.activity_not_found'), Config::get('constant.NOT_FOUND'));
            }

            $activity->update(['description' => $request->description]);

            return $this->toJsonEnc($activity, trans('api.project_progress.activity_updated'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    // Manpower & Equipment APIs
    public function listManpowerEquipment(Request $request)
    {
        try {
            $project_id = $request->input('project_id');
            
            if (!$project_id) {
                return $this->toJsonEnc([], trans('api.project_progress.project_id_required'), Config::get('constant.ERROR'));
            }

            $items = ProjectManpowerEquipment::where('project_id', $project_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->orderBy('category')
                ->get();

            return $this->toJsonEnc($items, trans('api.project_progress.manpower_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function addManpowerEquipment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'project_id' => 'required|integer',
                'user_id' => 'required|integer',
                'category' => 'required|string|max:100',
                'count' => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $item = ProjectManpowerEquipment::create([
                'project_id' => $request->project_id,
                'category' => $request->category,
                'count' => $request->count,
                'created_by' => $request->user_id,
            ]);

            return $this->toJsonEnc($item, trans('api.project_progress.manpower_added'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function updateManpowerEquipment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'item_id' => 'required|integer',
                'category' => 'required|string|max:100',
                'count' => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $item = ProjectManpowerEquipment::where('id', $request->item_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$item) {
                return $this->toJsonEnc([], trans('api.project_progress.item_not_found'), Config::get('constant.NOT_FOUND'));
            }

            $item->update([
                'category' => $request->category,
                'count' => $request->count,
            ]);

            return $this->toJsonEnc($item, trans('api.project_progress.manpower_updated'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    // Safety Checklist APIs
    public function listSafetyItems(Request $request)
    {
        try {
            $project_id = $request->input('project_id');
            
            if (!$project_id) {
                return $this->toJsonEnc([], trans('api.project_progress.project_id_required'), Config::get('constant.ERROR'));
            }

            $items = ProjectSafetyItem::where('project_id', $project_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->orderBy('created_at', 'desc')
                ->get();

            return $this->toJsonEnc($items, trans('api.project_progress.safety_items_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function addSafetyItem(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'project_id' => 'required|integer',
                'user_id' => 'required|integer',
                'checklist_item' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $item = ProjectSafetyItem::create([
                'project_id' => $request->project_id,
                'checklist_item' => $request->checklist_item,
                'created_by' => $request->user_id,
            ]);

            return $this->toJsonEnc($item, trans('api.project_progress.safety_item_added'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function updateSafetyItem(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'item_id' => 'required|integer',
                'checklist_item' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $item = ProjectSafetyItem::where('id', $request->item_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$item) {
                return $this->toJsonEnc([], trans('api.project_progress.safety_item_not_found'), Config::get('constant.NOT_FOUND'));
            }

            $item->update(['checklist_item' => $request->checklist_item]);

            return $this->toJsonEnc($item, trans('api.project_progress.safety_item_updated'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }
}