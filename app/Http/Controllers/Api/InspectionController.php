<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inspection;
use App\Models\InspectionTemplate;
use App\Models\InspectionResult;
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
                'template_id' => 'required|integer',
                'title' => 'required|string|max:255',
                'scheduled_date' => 'required|date',
                'inspector_id' => 'required|integer',
                'location' => 'required|string',
            ], [
                'user_id.required' => trans('api.inspections.user_id_required'),
                'project_id.required' => trans('api.inspections.project_id_required'),
                'template_id.required' => trans('api.inspections.template_id_required'),
                'title.required' => trans('api.inspections.title_required'),
                'scheduled_date.required' => trans('api.inspections.scheduled_date_required'),
                'inspector_id.required' => trans('api.inspections.inspector_id_required'),
                'location.required' => trans('api.inspections.location_required'),
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $inspectionDetails = new Inspection();
            $inspectionDetails->project_id = $request->project_id;
            $inspectionDetails->phase_id = $request->phase_id;
            $inspectionDetails->template_id = $request->template_id;
            $inspectionDetails->title = $request->title;
            $inspectionDetails->description = $request->description ?? '';
            $inspectionDetails->scheduled_date = $request->scheduled_date;
            $inspectionDetails->inspector_id = $request->inspector_id;
            $inspectionDetails->location = $request->location;
            $inspectionDetails->created_by = $request->user_id;
            $inspectionDetails->status = 'scheduled';
            $inspectionDetails->is_active = true;

            if ($inspectionDetails->save()) {
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
            $status = $request->input('status');
            $limit = $request->input('limit', 10);

            $query = Inspection::where('is_active', 1)->where('is_deleted', 0);

            if ($project_id) {
                $query->where('project_id', $project_id);
            }

            if ($status) {
                $query->where('status', $status);
            }

            $inspections = $query->paginate($limit);

            return $this->toJsonEnc($inspections, trans('api.inspections.list_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function details(Request $request)
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

            return $this->toJsonEnc($inspection, trans('api.inspections.details_retrieved'), Config::get('constant.SUCCESS'));
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

    public function generateReport(Request $request)
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

            $totalItems = $results->count();
            $passedItems = $results->where('result', 'pass')->count();
            $failedItems = $results->where('result', 'fail')->count();
            $scorePercentage = $totalItems > 0 ? ($passedItems / $totalItems) * 100 : 0;

            $reportData = [
                'inspection' => $inspection,
                'results' => $results,
                'summary' => [
                    'total_items' => $totalItems,
                    'passed_items' => $passedItems,
                    'failed_items' => $failedItems,
                    'score_percentage' => round($scorePercentage, 2),
                    'overall_result' => $failedItems > 0 ? 'fail' : 'pass'
                ]
            ];

            return $this->toJsonEnc($reportData, trans('api.inspections.report_generated'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }
}