<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inspection;
use App\Models\InspectionChecklist;
use App\Models\InspectionImage;
use App\Helpers\FileHelper;
use App\Models\InspectionResult;
use App\Models\InspectionTemplate;
use App\Models\ProjectPhase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class InspectionController extends Controller
{
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'project_id' => 'required|integer',
                'phase_id' => 'nullable|integer',
                'category' => 'required|string|max:255',
                'description' => 'required|string',
                'checklist_items' => 'required|array|min:1',
                'checklist_items.*' => 'required|string|max:255',
                'images.*' => 'nullable|file|mimes:jpg,jpeg,png|max:10240',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $inspectionDetails = new Inspection();
            $inspectionDetails->project_id = $request->project_id;
            $inspectionDetails->phase_id = $request->phase_id;
            $inspectionDetails->category = $request->category;
            $inspectionDetails->description = $request->description;
            $inspectionDetails->created_by = $request->user_id;
            $inspectionDetails->status = 'open';
            $inspectionDetails->is_active = true;

            if ($inspectionDetails->save()) {
                // Create checklist items
                foreach ($request->checklist_items as $item) {
                    InspectionChecklist::create([
                        'inspection_id' => $inspectionDetails->id,
                        'checklist_item' => $item,
                        'is_checked' => false,
                        'is_active' => true,
                        'is_deleted' => false
                    ]);
                }

                // Handle image uploads
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $image) {
                        $imageData = FileHelper::uploadFile($image, 'inspections/images');
                        InspectionImage::create([
                            'inspection_id' => $inspectionDetails->id,
                            'image_path' => $imageData['path'],
                            'original_name' => $imageData['original_name'],
                            'file_size' => $imageData['size'],
                            'uploaded_by' => $request->user_id
                        ]);
                    }
                }

                $inspectionDetails->load(['checklists', 'images']);
                return $this->toJsonEnc($inspectionDetails, trans('api.inspections.created_success'), Config::get('constant.SUCCESS'));
            } else {
                return $this->toJsonEnc([], trans('api.inspections.creation_failed'), Config::get('constant.ERROR'));
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
            $phase_id = $request->input('phase_id');
            $status = $request->input('status');
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 10);

            $query = Inspection::with(['checklists', 'images', 'createdBy:id,name'])
                ->where('is_active', 1)
                ->where('is_deleted', 0);

            if ($project_id) {
                $query->where('project_id', $project_id);
            }

            if ($phase_id) {
                $query->where('phase_id', $phase_id);
            }

            if ($status) {
                $query->where('status', $status);
            }

            $inspections = $query->paginate($limit, ['*'], 'page', $page);

            $inspectionsData = collect($inspections->items())->map(function ($inspection) {
                $inspection->images = $inspection->images->map(function ($image) {
                    $image->image_url = asset('storage/' . $image->image_path);
                    return $image;
                });
                $inspection->created_by_name = $inspection->createdBy ? $inspection->createdBy->name : null;
                unset($inspection->createdBy); // Remove the full created_by object
                return $inspection;
            });

            $data = [
                'data' => $inspectionsData
            ];

            return $this->toJsonEnc($data, trans('api.inspections.list_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function details(Request $request)
    {
        try {
            $inspection_id = $request->input('inspection_id');
            $user_id = $request->input('user_id');

            $inspection = Inspection::with(['images', 'createdBy:id,name', 'inspectedBy:id,name'])
                ->where('id', $inspection_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$inspection) {
                return $this->toJsonEnc([], trans('api.inspections.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $inspection->images = $inspection->images->map(function ($image) {
                $image->image_url = asset('storage/' . $image->image_path);
                return $image;
            });
            
            $inspection->created_by_name = $inspection->createdBy ? $inspection->createdBy->name : null;
            $inspection->inspected_by_name = $inspection->inspectedBy ? $inspection->inspectedBy->name : null;
            unset($inspection->createdBy, $inspection->inspectedBy);

            return $this->toJsonEnc($inspection, trans('api.inspections.details_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }
    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'inspection_id' => 'required|integer',
                'checklist_updates' => 'nullable|array',
                'checklist_updates.*.id' => 'required|integer',
                'checklist_updates.*.is_checked' => 'required|boolean',
                'comment' => 'nullable|string',
                'images.*' => 'nullable|file|mimes:jpg,jpeg,png|max:10240',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $inspection_id = $request->input('inspection_id');
            $user_id = $request->input('user_id');

            $inspection = Inspection::where('id', $inspection_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$inspection) {
                return $this->toJsonEnc([], trans('api.inspections.not_found'), Config::get('constant.NOT_FOUND'));
            }

            // Update checklist items
            if ($request->has('checklist_updates')) {
                foreach ($request->checklist_updates as $update) {
                    InspectionChecklist::where('id', $update['id'])
                        ->where('inspection_id', $inspection_id)
                        ->update([
                            'is_checked' => $update['is_checked']
                        ]);
                }
            }

            // Handle new image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageData = FileHelper::uploadFile($image, 'inspections/images');
                    InspectionImage::create([
                        'inspection_id' => $inspection_id,
                        'image_path' => $imageData['path'],
                        'original_name' => $imageData['original_name'],
                        'file_size' => $imageData['size'],
                        'uploaded_by' => $user_id,
                        'is_active' => true,
                        'is_deleted' => false
                    ]);
                }
            }

            // Store comment in inspection
            if ($request->filled('comment')) {
                $inspection->comment = $request->comment;
                $inspection->save();
            }

            $inspection->load(['checklists', 'images']);
            return $this->toJsonEnc($inspection, trans('api.inspections.updated_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function templates(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            $category = $request->input('category');

            $query = InspectionTemplate::where('is_active', 1)->where('is_deleted', 0);

            if ($category) {
                $query->where('category', $category);
            }

            $templates = $query->get();

            return $this->toJsonEnc($templates, trans('api.inspections.templates_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function complete(Request $request)
    {
        try {
            $inspection_id = $request->input('inspection_id');
            $overall_result = $request->input('overall_result');
            $score_percentage = $request->input('score_percentage');
            $notes = $request->input('notes');
            $user_id = $request->input('user_id');

            $inspection = Inspection::where('id', $inspection_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$inspection) {
                return $this->toJsonEnc([], trans('api.inspections.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $inspection->status = 'completed';
            $inspection->overall_result = $overall_result;
            $inspection->score_percentage = $score_percentage;
            $inspection->notes = $notes ?? '';
            $inspection->completed_at = now();
            $inspection->save();

            return $this->toJsonEnc($inspection, trans('api.inspections.completed_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function approve(Request $request)
    {
        try {
            $inspection_id = $request->input('inspection_id');
            $user_id = $request->input('user_id');
            $approval_notes = $request->input('approval_notes');

            $inspection = Inspection::where('id', $inspection_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$inspection) {
                return $this->toJsonEnc([], trans('api.inspections.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $inspection->status = 'approved';
            $inspection->notes = $approval_notes ?? '';
            $inspection->save();

            return $this->toJsonEnc($inspection, trans('api.inspections.approved_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function results(Request $request)
    {
        try {
            $inspection_id = $request->input('inspection_id');
            $user_id = $request->input('user_id');

            $inspection = Inspection::where('id', $inspection_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$inspection) {
                return $this->toJsonEnc([], trans('api.inspections.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $results = InspectionResult::where('inspection_id', $inspection_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->get();

            $data = [
                'inspection' => $inspection,
                'results' => $results
            ];

            return $this->toJsonEnc($data, trans('api.inspections.results_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function phases(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            $project_id = $request->input('project_id');

            $query = ProjectPhase::select('id', 'title')
                ->where('is_active', 1)
                ->where('is_deleted', 0);

            if ($project_id) {
                $query->where('project_id', $project_id);
            }

            $phases = $query->get();

            return $this->toJsonEnc($phases, trans('api.inspections.phases_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function startInspection(Request $request)
    {
        try {
            $inspection_id = $request->input('inspection_id');
            $user_id = $request->input('user_id');

            $inspection = Inspection::where('id', $inspection_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$inspection) {
                return $this->toJsonEnc([], trans('api.inspections.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $inspection->status = 'in_progress';
            $inspection->started_at = now();
            $inspection->save();

            return $this->toJsonEnc($inspection, trans('api.inspections.started_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function saveChecklistItem(Request $request)
    {
        try {
            $inspection_id = $request->input('inspection_id');
            $item_name = $request->input('item_name');
            $result = $request->input('result');
            $notes = $request->input('notes');
            $images = $request->input('images', []);
            $user_id = $request->input('user_id');

            $inspection = Inspection::where('id', $inspection_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$inspection) {
                return $this->toJsonEnc([], trans('api.inspections.not_found'), Config::get('constant.NOT_FOUND'));
            }

            // Check if result already exists for this item
            $inspectionResult = InspectionResult::where('inspection_id', $inspection_id)
                ->where('item_name', $item_name)
                ->first();

            if ($inspectionResult) {
                $inspectionResult->result = $result;
                $inspectionResult->notes = $notes;
                $inspectionResult->images = $images;
                $inspectionResult->save();
            } else {
                $inspectionResult = new InspectionResult();
                $inspectionResult->inspection_id = $inspection_id;
                $inspectionResult->item_name = $item_name;
                $inspectionResult->result = $result;
                $inspectionResult->notes = $notes;
                $inspectionResult->images = $images;
                $inspectionResult->is_active = true;
                $inspectionResult->save();
            }

            return $this->toJsonEnc($inspectionResult, trans('api.inspections.item_saved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function submitInspection(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'inspection_id' => 'required|integer',
                'user_id' => 'required|integer',
                'checklist_updates' => 'required|array',
                'checklist_updates.*.id' => 'required|integer',
                'checklist_updates.*.is_checked' => 'required|boolean',
                'comment' => 'nullable|string',
                'images.*' => 'nullable|file|mimes:jpg,jpeg,png|max:10240',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $inspection_id = $request->input('inspection_id');
            $user_id = $request->input('user_id');

            $inspection = Inspection::where('id', $inspection_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$inspection) {
                return $this->toJsonEnc([], trans('api.inspections.not_found'), Config::get('constant.NOT_FOUND'));
            }

            // Update checklist items
            foreach ($request->checklist_updates as $update) {
                InspectionChecklist::where('id', $update['id'])
                    ->where('inspection_id', $inspection_id)
                    ->update([
                        'is_checked' => $update['is_checked'],
                        'updated_by' => $user_id,
                        'checked_at' => now()
                    ]);
            }

            // Update inspection with comment and status
            $inspection->comment = $request->comment;
            $inspection->status = 'completed';
            $inspection->inspected_by = $user_id;
            $inspection->save();

            // Handle additional image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageData = FileHelper::uploadFile($image, 'inspections/images');
                    InspectionImage::create([
                        'inspection_id' => $inspection_id,
                        'image_path' => $imageData['path'],
                        'original_name' => $imageData['original_name'],
                        'file_size' => $imageData['size'],
                        'uploaded_by' => $user_id
                    ]);
                }
            }

            $inspection->load(['checklists', 'images']);
            return $this->toJsonEnc($inspection, trans('api.inspections.submitted_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function mainScreen(Request $request)
    {
        try {
            $project_id = $request->input('project_id');
            $user_id = $request->input('user_id');

            // Get open inspection (top section)
            $openInspection = Inspection::with(['checklists', 'images'])
                ->where('project_id', $project_id)
                ->where('status', 'open')
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            // Get previous inspections (bottom section)
            $previousInspections = Inspection::where('project_id', $project_id)
                ->where('status', 'completed')
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->orderBy('updated_at', 'desc')
                ->limit(5)
                ->get();

            $data = [
                'open_inspection' => $openInspection,
                'previous_inspections' => $previousInspections
            ];

            return $this->toJsonEnc($data, 'Main screen data retrieved', Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }
}