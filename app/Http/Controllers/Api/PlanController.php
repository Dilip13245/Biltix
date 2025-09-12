<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\PlanMarkup;
use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
    public function upload(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'project_id' => 'required|integer',
                'title' => 'required|string|max:255',
                'drawing_number' => 'required|string|max:50',
                'files' => 'required|array',
                'files.*' => 'file|max:25600', // 25MB max per file
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $uploadedPlans = [];
            $files = $request->file('files');
            
            foreach ($files as $index => $file) {
                $fileData = FileHelper::uploadFile($file, 'plans');
                
                $planDetails = new Plan();
                $planDetails->project_id = $request->project_id;
                $planDetails->title = $request->title . ($index > 0 ? ' - ' . ($index + 1) : '');
                $planDetails->drawing_number = $request->drawing_number . ($index > 0 ? '-' . ($index + 1) : '');
                $planDetails->file_name = $fileData['filename'];
                $planDetails->file_path = $fileData['path'];
                $planDetails->file_size = $fileData['size'];
                $planDetails->file_type = $fileData['mime_type'];
                $planDetails->uploaded_by = $request->user_id;
                $planDetails->is_active = true;

                if ($planDetails->save()) {
                    $uploadedPlans[] = $planDetails;
                }
            }

            if (count($uploadedPlans) > 0) {
                return $this->toJsonEnc($uploadedPlans, trans('api.plans.uploaded_success'), Config::get('constant.SUCCESS'));
            } else {
                return $this->toJsonEnc([], trans('api.plans.upload_failed'), Config::get('constant.ERROR'));
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
            $plan_type = $request->input('plan_type');
            $limit = $request->input('limit', 10);

            $query = Plan::active();

            if ($project_id) {
                $query->where('project_id', $project_id);
            }

            if ($plan_type) {
                $query->where('plan_type', $plan_type);
            }

            $plans = $query->paginate($limit);

            return $this->toJsonEnc($plans, trans('api.plans.list_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function details(Request $request)
    {
        try {
            $plan_id = $request->input('plan_id');
            $user_id = $request->input('user_id');

            $plan = Plan::active()->where('id', $plan_id)->first();

            if (!$plan) {
                return $this->toJsonEnc([], trans('api.plans.not_found'), Config::get('constant.NOT_FOUND'));
            }

            return $this->toJsonEnc($plan, trans('api.plans.details_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function addMarkup(Request $request)
    {
        try {
            $plan_id = $request->input('plan_id');
            $user_id = $request->input('user_id');
            $markup_type = $request->input('markup_type');
            $title = $request->input('title');
            $markup_data = $request->input('markup_data');

            $plan = Plan::active()->where('id', $plan_id)->first();

            if (!$plan) {
                return $this->toJsonEnc([], trans('api.plans.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $markupDetails = new PlanMarkup();
            $markupDetails->plan_id = $plan_id;
            $markupDetails->user_id = $user_id;
            $markupDetails->markup_type = $markup_type;
            $markupDetails->markup_data = $markup_data;
            $markupDetails->title = $title;
            $markupDetails->description = $request->description ?? '';
            $markupDetails->status = 'active';
            $markupDetails->is_active = true;
            $markupDetails->save();

            return $this->toJsonEnc($markupDetails, trans('api.plans.markup_added'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function getMarkups(Request $request)
    {
        try {
            $plan_id = $request->input('plan_id');
            $user_id = $request->input('user_id');

            $markups = PlanMarkup::where('plan_id', $plan_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->get();

            return $this->toJsonEnc($markups, trans('api.plans.markups_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function approve(Request $request)
    {
        try {
            $plan_id = $request->input('plan_id');
            $user_id = $request->input('user_id');

            $plan = Plan::active()->where('id', $plan_id)->first();

            if (!$plan) {
                return $this->toJsonEnc([], trans('api.plans.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $plan->status = 'approved';
            $plan->approved_by = $user_id;
            $plan->approved_at = now();
            $plan->save();

            return $this->toJsonEnc($plan, trans('api.plans.approved_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function delete(Request $request)
    {
        try {
            $plan_id = $request->input('plan_id');
            $user_id = $request->input('user_id');

            $plan = Plan::active()->where('id', $plan_id)->first();

            if (!$plan) {
                return $this->toJsonEnc([], trans('api.plans.not_found'), Config::get('constant.NOT_FOUND'));
            }

            // Soft delete by setting is_deleted to true
            $plan->is_deleted = true;
            $plan->save();

            return $this->toJsonEnc([], trans('api.plans.deleted_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function replace(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'plan_id' => 'required|integer',
                'file' => 'required|file|max:25600', // 25MB max
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $plan = Plan::active()->where('id', $request->plan_id)->first();

            if (!$plan) {
                return $this->toJsonEnc([], trans('api.plans.not_found'), Config::get('constant.NOT_FOUND'));
            }

            // Delete old file
            FileHelper::deleteFile($plan->file_path);

            // Upload new file
            $fileData = FileHelper::uploadFile($request->file('file'), 'plans');
            
            // Update plan with new file data
            $plan->file_name = $fileData['filename'];
            $plan->file_path = $fileData['path'];
            $plan->file_size = $fileData['size'];
            $plan->file_type = $fileData['mime_type'];
            $plan->save();

            return $this->toJsonEnc($plan, trans('api.plans.replaced_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }
}