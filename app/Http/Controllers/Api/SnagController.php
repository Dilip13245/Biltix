<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Snag;
use App\Models\SnagCategory;
use App\Models\SnagComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class SnagController extends Controller
{
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'project_id' => 'required|integer',
                'category_id' => 'required|integer',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'location' => 'required|string',
                'priority' => 'required|in:low,medium,high,critical',
            ], [
                'user_id.required' => trans('api.snags.user_id_required'),
                'project_id.required' => trans('api.snags.project_id_required'),
                'category_id.required' => trans('api.snags.category_id_required'),
                'title.required' => trans('api.snags.title_required'),
                'description.required' => trans('api.snags.description_required'),
                'location.required' => trans('api.snags.location_required'),
                'priority.required' => trans('api.snags.priority_required'),
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $snagDetails = new Snag();
            $snagDetails->project_id = $request->project_id;
            $snagDetails->category_id = $request->category_id;
            $snagDetails->title = $request->title;
            $snagDetails->description = $request->description;
            $snagDetails->location = $request->location;
            $snagDetails->priority = $request->priority;
            $snagDetails->reported_by = $request->user_id;
            $snagDetails->status = 'open';
            $snagDetails->images_before = $request->images_before ?? [];
            $snagDetails->is_active = true;

            if ($snagDetails->save()) {
                return $this->toJsonEnc($snagDetails, trans('api.snags.created_success'), Config::get('constant.SUCCESS'));
            } else {
                return $this->toJsonEnc([], trans('api.snags.creation_failed'), Config::get('constant.ERROR'));
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
            $priority = $request->input('priority');
            $limit = $request->input('limit', 10);

            $query = Snag::where('is_active', 1)->where('is_deleted', 0);

            if ($project_id) {
                $query->where('project_id', $project_id);
            }

            if ($status) {
                $query->where('status', $status);
            }

            if ($priority) {
                $query->where('priority', $priority);
            }

            $snags = $query->paginate($limit);

            return $this->toJsonEnc($snags, trans('api.snags.list_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function details(Request $request)
    {
        try {
            $snag_id = $request->input('snag_id');
            $user_id = $request->input('user_id');

            $snag = Snag::where('id', $snag_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$snag) {
                return $this->toJsonEnc([], trans('api.snags.not_found'), Config::get('constant.NOT_FOUND'));
            }

            return $this->toJsonEnc($snag, trans('api.snags.details_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function update(Request $request)
    {
        try {
            $snag_id = $request->input('snag_id');
            $user_id = $request->input('user_id');

            $snag = Snag::where('id', $snag_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$snag) {
                return $this->toJsonEnc([], trans('api.snags.not_found'), Config::get('constant.NOT_FOUND'));
            }

            if ($request->filled('title')) $snag->title = $request->title;
            if ($request->filled('description')) $snag->description = $request->description;
            if ($request->filled('status')) $snag->status = $request->status;
            if ($request->filled('priority')) $snag->priority = $request->priority;
            if ($request->filled('assigned_to')) $snag->assigned_to = $request->assigned_to;

            $snag->save();

            return $this->toJsonEnc($snag, trans('api.snags.updated_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function resolve(Request $request)
    {
        try {
            $snag_id = $request->input('snag_id');
            $resolution_notes = $request->input('resolution_notes');
            $user_id = $request->input('user_id');

            $snag = Snag::where('id', $snag_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$snag) {
                return $this->toJsonEnc([], trans('api.snags.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $snag->status = 'resolved';
            $snag->resolution_notes = $resolution_notes;
            $snag->resolved_by = $user_id;
            $snag->resolved_at = now();
            $snag->images_after = $request->images_after ?? [];
            $snag->save();

            return $this->toJsonEnc($snag, trans('api.snags.resolved_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function assign(Request $request)
    {
        try {
            $snag_id = $request->input('snag_id');
            $assigned_to = $request->input('assigned_to');
            $user_id = $request->input('user_id');

            $snag = Snag::where('id', $snag_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$snag) {
                return $this->toJsonEnc([], trans('api.snags.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $snag->assigned_to = $assigned_to;
            $snag->status = 'assigned';
            $snag->save();

            return $this->toJsonEnc($snag, trans('api.snags.assigned_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function addComment(Request $request)
    {
        try {
            $snag_id = $request->input('snag_id');
            $comment = $request->input('comment');
            $user_id = $request->input('user_id');

            $snag = Snag::where('id', $snag_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$snag) {
                return $this->toJsonEnc([], trans('api.snags.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $snagComment = new SnagComment();
            $snagComment->snag_id = $snag_id;
            $snagComment->user_id = $user_id;
            $snagComment->comment = $comment;
            $snagComment->status_changed_to = $request->status_changed_to;
            $snagComment->is_active = true;
            $snagComment->save();

            return $this->toJsonEnc($snagComment, trans('api.snags.comment_added'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function categories(Request $request)
    {
        try {
            $user_id = $request->input('user_id');

            $categories = SnagCategory::where('is_active', 1)
                ->where('is_deleted', 0)
                ->get();

            return $this->toJsonEnc($categories, trans('api.snags.categories_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }
}