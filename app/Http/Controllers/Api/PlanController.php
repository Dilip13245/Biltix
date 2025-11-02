<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\PlanMarkup;
use App\Helpers\FileHelper;
use App\Helpers\NotificationHelper;
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
                'title' => 'nullable|string|max:255',
                'drawing_number' => 'nullable|string|max:50',
                'files' => 'required|array',
                'files.*' => 'file|max:25600', // 25MB max per file
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $uploadedPlans = [];
            $files = $request->file('files');
            
            if (!$files || count($files) === 0) {
                return $this->toJsonEnc([], 'No files provided', Config::get('constant.ERROR'));
            }
            
            foreach ($files as $index => $file) {
                try {
                    $fileData = FileHelper::uploadFile($file, 'plans');
                
                $planDetails = new Plan();
                $planDetails->project_id = $request->project_id;
                $planDetails->title = $request->title ? ($request->title . ($index > 0 ? ' - ' . ($index + 1) : '')) : ($fileData['original_name'] ?? $file->getClientOriginalName());
                $planDetails->drawing_number = $request->drawing_number ?: null;
                $planDetails->file_name = $fileData['filename'];
                $planDetails->file_path = $fileData['path'];
                $planDetails->file_size = $fileData['size'];
                $planDetails->file_type = $fileData['mime_type'];
                $planDetails->uploaded_by = $request->user_id;
                $planDetails->is_active = true;

                    if ($planDetails->save()) {
                        $uploadedPlans[] = $planDetails;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }

            if (count($uploadedPlans) > 0) {
                // Send notification for plan upload
                foreach ($uploadedPlans as $plan) {
                    $project = \App\Models\Project::find($plan->project_id);
                    if ($project) {
                        NotificationHelper::sendToProjectTeam(
                            $project->id,
                            'plan_uploaded',
                            'New Plan Uploaded',
                            "New plan '{$plan->title}' uploaded to project '{$project->project_title}'",
                            [
                                'plan_id' => $plan->id,
                                'plan_title' => $plan->title,
                                'project_id' => $project->id,
                                'project_title' => $project->project_title,
                                'uploaded_by' => $request->user_id,
                                'action_url' => "/projects/{$project->id}/plans/{$plan->id}"
                            ],
                            'medium',
                            [$request->user_id]
                        );
                    }
                }
                
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
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 10);

            $query = Plan::active();

            if ($project_id) {
                $query->where('project_id', $project_id);
            }

            if ($plan_type) {
                $query->where('plan_type', $plan_type);
            }

            $plans = $query->orderBy('created_at', 'desc')->paginate($limit, ['*'], 'page', $page);
            
            // Add full URLs to file paths
            $plans->getCollection()->transform(function ($plan) {
                $plan->file_url = asset('storage/' . $plan->file_path);
                return $plan;
            });

            $data = [
                'data' => $plans->items()
            ];

            return $this->toJsonEnc($data, trans('api.plans.list_retrieved'), Config::get('constant.SUCCESS'));
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
            
            // Add full URL to file path
            $plan->file_url = asset('storage/' . $plan->file_path);

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

            // Send notification for plan markup
            $project = \App\Models\Project::find($plan->project_id);
            $markupAuthor = \App\Models\User::find($user_id);
            if ($project && $markupAuthor && $plan->uploaded_by) {
                NotificationHelper::send(
                    [$plan->uploaded_by],
                    'plan_markup_added',
                    'Plan Markup Added',
                    "{$markupAuthor->name} added a markup to plan '{$plan->title}'",
                    [
                        'plan_id' => $plan->id,
                        'plan_title' => $plan->title,
                        'markup_id' => $markupDetails->id,
                        'markup_author_id' => $user_id,
                        'markup_author' => $markupAuthor->name,
                        'project_id' => $project->id,
                        'action_url' => "/projects/{$project->id}/plans/{$plan->id}#markup_{$markupDetails->id}"
                    ],
                    'low',
                    [$user_id]
                );
            }

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

            // Send notification for plan approval
            $project = \App\Models\Project::find($plan->project_id);
            $approver = \App\Models\User::find($user_id);
            if ($project && $approver && $plan->uploaded_by) {
                NotificationHelper::send(
                    [$plan->uploaded_by],
                    'plan_approved',
                    'Plan Approved',
                    "Plan '{$plan->title}' has been approved by {$approver->name}",
                    [
                        'plan_id' => $plan->id,
                        'plan_title' => $plan->title,
                        'project_id' => $project->id,
                        'approver_id' => $user_id,
                        'approver_name' => $approver->name,
                        'action_url' => "/projects/{$project->id}/plans/{$plan->id}"
                    ],
                    'medium'
                );
            }

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
            
            // Get original name without extension for title
            $originalName = $fileData['original_name'] ?? $request->file('file')->getClientOriginalName();
            $titleWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);
            
            // Update plan with new file data - keep existing title if it's marked_plan (from drawing modal)
            if ($titleWithoutExtension !== 'marked_plan') {
                $plan->title = $titleWithoutExtension;
            }
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