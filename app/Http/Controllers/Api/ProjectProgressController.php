<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProjectActivity;
use App\Models\ProjectManpowerEquipment;
use App\Models\ProjectSafetyItem;
use App\Models\ProjectQualityWork;
use App\Models\ProjectMaterialAdequacy;
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
                'descriptions' => 'required|array|min:1',
                'descriptions.*' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $activities = [];
            foreach ($request->descriptions as $description) {
                $activities[] = ProjectActivity::create([
                    'project_id' => $request->project_id,
                    'description' => $description,
                    'created_by' => $request->user_id,
                ]);
            }
            
            // Send notification for activities added
            $project = \App\Models\Project::find($request->project_id);
            $creator = \App\Models\User::find($request->user_id);
            if ($project && $creator) {
                $activityCount = count($activities);
                \App\Helpers\NotificationHelper::sendToProjectTeam(
                    $request->project_id,
                    'activities_added',
                    'Project Activities Added',
                    "{$creator->name} added {$activityCount} activit" . ($activityCount > 1 ? 'ies' : 'y'),
                    [
                        'project_id' => $project->id,
                        'activity_count' => $activityCount,
                        'action_url' => "/projects/{$project->id}/progress"
                    ],
                    'low',
                    [$request->user_id]
                );
            }

            return $this->toJsonEnc($activities, trans('api.project_progress.activities_added'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function updateActivity(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'activities' => 'required|array|min:1',
                'activities.*.id' => 'required|integer',
                'activities.*.description' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $updatedActivities = [];
            foreach ($request->activities as $activityData) {
                $activity = ProjectActivity::where('id', $activityData['id'])
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->first();
                
                if ($activity) {
                    $activity->update(['description' => $activityData['description']]);
                    $updatedActivities[] = $activity;
                }
            }

            return $this->toJsonEnc($updatedActivities, 'Activities updated successfully', Config::get('constant.SUCCESS'));
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
                'items' => 'required|array|min:1',
                'items.*.category' => 'required|string|max:100',
                'items.*.count' => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $items = [];
            foreach ($request->items as $itemData) {
                $items[] = ProjectManpowerEquipment::create([
                    'project_id' => $request->project_id,
                    'category' => $itemData['category'],
                    'count' => $itemData['count'],
                    'created_by' => $request->user_id,
                ]);
            }
            
            // Send notification for manpower/equipment added
            $project = \App\Models\Project::find($request->project_id);
            $creator = \App\Models\User::find($request->user_id);
            if ($project && $creator) {
                \App\Helpers\NotificationHelper::sendToProjectTeam(
                    $request->project_id,
                    'manpower_added',
                    'Manpower/Equipment Updated',
                    "{$creator->name} updated manpower and equipment data",
                    [
                        'project_id' => $project->id,
                        'action_url' => "/projects/{$project->id}/progress"
                    ],
                    'low',
                    [$request->user_id]
                );
            }

            return $this->toJsonEnc($items, trans('api.project_progress.manpower_added'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function updateManpowerEquipment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'manpower' => 'required|array|min:1',
                'manpower.*.id' => 'required|integer',
                'manpower.*.category' => 'required|string|max:100',
                'manpower.*.count' => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $updatedItems = [];
            foreach ($request->manpower as $manpowerData) {
                $item = ProjectManpowerEquipment::where('id', $manpowerData['id'])
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->first();
                
                if ($item) {
                    $item->update([
                        'category' => $manpowerData['category'],
                        'count' => $manpowerData['count'],
                    ]);
                    $updatedItems[] = $item;
                }
            }

            return $this->toJsonEnc($updatedItems, 'Manpower equipment updated successfully', Config::get('constant.SUCCESS'));
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
                'checklist_items' => 'required|array|min:1',
                'checklist_items.*' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $items = [];
            foreach ($request->checklist_items as $checklistItem) {
                $items[] = ProjectSafetyItem::create([
                    'project_id' => $request->project_id,
                    'checklist_item' => $checklistItem,
                    'created_by' => $request->user_id,
                ]);
            }
            
            // Send notification for safety items added
            $project = \App\Models\Project::find($request->project_id);
            $creator = \App\Models\User::find($request->user_id);
            if ($project && $creator) {
                \App\Helpers\NotificationHelper::sendToProjectTeam(
                    $request->project_id,
                    'safety_items_added',
                    'Safety Checklist Updated',
                    "{$creator->name} updated safety checklist items",
                    [
                        'project_id' => $project->id,
                        'action_url' => "/projects/{$project->id}/progress"
                    ],
                    'low',
                    [$request->user_id]
                );
            }

            return $this->toJsonEnc($items, trans('api.project_progress.safety_items_added'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function updateSafetyItem(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'safety_items' => 'required|array|min:1',
                'safety_items.*.id' => 'required|integer',
                'safety_items.*.checklist_item' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $updatedItems = [];
            foreach ($request->safety_items as $safetyData) {
                $item = ProjectSafetyItem::where('id', $safetyData['id'])
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->first();
                
                if ($item) {
                    $item->update(['checklist_item' => $safetyData['checklist_item']]);
                    $updatedItems[] = $item;
                }
            }

            return $this->toJsonEnc($updatedItems, 'Safety items updated successfully', Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    // Delete Methods
    public function deleteActivity(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'activity_id' => 'required|integer',
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

            $activity->update(['is_deleted' => 1]);

            return $this->toJsonEnc([], trans('api.project_progress.activity_deleted_successfully'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function deleteManpowerEquipment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'item_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $item = ProjectManpowerEquipment::where('id', $request->item_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$item) {
                return $this->toJsonEnc([], trans('api.project_progress.manpower_not_found'), Config::get('constant.NOT_FOUND'));
            }

            $item->update(['is_deleted' => 1]);

            return $this->toJsonEnc([], trans('api.project_progress.manpower_deleted_successfully'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function deleteSafetyItem(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'item_id' => 'required|integer',
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

            $item->update(['is_deleted' => 1]);

            return $this->toJsonEnc([], trans('api.project_progress.safety_item_deleted_successfully'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    // Quality and Speed of Work APIs
    public function listQualityWork(Request $request)
    {
        try {
            $project_id = $request->input('project_id');
            
            if (!$project_id) {
                return $this->toJsonEnc([], trans('api.project_progress.project_id_required'), Config::get('constant.ERROR'));
            }

            $items = ProjectQualityWork::where('project_id', $project_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->orderBy('created_at', 'desc')
                ->get();

            return $this->toJsonEnc($items, trans('api.project_progress.quality_work_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function addQualityWork(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'project_id' => 'required|integer',
                'user_id' => 'required|integer',
                'descriptions' => 'required|array|min:1',
                'descriptions.*' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $items = [];
            foreach ($request->descriptions as $description) {
                $items[] = ProjectQualityWork::create([
                    'project_id' => $request->project_id,
                    'description' => $description,
                    'created_by' => $request->user_id,
                ]);
            }

            return $this->toJsonEnc($items, trans('api.project_progress.quality_work_added'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function updateQualityWork(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'quality_work' => 'required|array|min:1',
                'quality_work.*.id' => 'required|integer',
                'quality_work.*.description' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $updatedItems = [];
            foreach ($request->quality_work as $data) {
                $item = ProjectQualityWork::where('id', $data['id'])
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->first();
                
                if ($item) {
                    $item->update(['description' => $data['description']]);
                    $updatedItems[] = $item;
                }
            }

            return $this->toJsonEnc($updatedItems, 'Quality work updated successfully', Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function deleteQualityWork(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'item_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $item = ProjectQualityWork::where('id', $request->item_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$item) {
                return $this->toJsonEnc([], 'Quality work item not found', Config::get('constant.NOT_FOUND'));
            }

            $item->update(['is_deleted' => 1]);

            return $this->toJsonEnc([], 'Quality work item deleted successfully', Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    // Material Adequacy APIs
    public function listMaterialAdequacy(Request $request)
    {
        try {
            $project_id = $request->input('project_id');
            
            if (!$project_id) {
                return $this->toJsonEnc([], trans('api.project_progress.project_id_required'), Config::get('constant.ERROR'));
            }

            $items = ProjectMaterialAdequacy::where('project_id', $project_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->orderBy('created_at', 'desc')
                ->get();

            return $this->toJsonEnc($items, trans('api.project_progress.material_adequacy_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function addMaterialAdequacy(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'project_id' => 'required|integer',
                'user_id' => 'required|integer',
                'descriptions' => 'required|array|min:1',
                'descriptions.*' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $items = [];
            foreach ($request->descriptions as $description) {
                $items[] = ProjectMaterialAdequacy::create([
                    'project_id' => $request->project_id,
                    'description' => $description,
                    'created_by' => $request->user_id,
                ]);
            }

            return $this->toJsonEnc($items, trans('api.project_progress.material_adequacy_added'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function updateMaterialAdequacy(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'material_adequacy' => 'required|array|min:1',
                'material_adequacy.*.id' => 'required|integer',
                'material_adequacy.*.description' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $updatedItems = [];
            foreach ($request->material_adequacy as $data) {
                $item = ProjectMaterialAdequacy::where('id', $data['id'])
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->first();
                
                if ($item) {
                    $item->update(['description' => $data['description']]);
                    $updatedItems[] = $item;
                }
            }

            return $this->toJsonEnc($updatedItems, 'Material adequacy updated successfully', Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function deleteMaterialAdequacy(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'item_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $item = ProjectMaterialAdequacy::where('id', $request->item_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$item) {
                return $this->toJsonEnc([], 'Material adequacy item not found', Config::get('constant.NOT_FOUND'));
            }

            $item->update(['is_deleted' => 1]);

            return $this->toJsonEnc([], 'Material adequacy item deleted successfully', Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

}