<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Snag;
use App\Models\SnagCategory;
use App\Models\SnagComment;
use App\Helpers\NumberHelper;
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
                'category_id' => 'nullable|integer', // Made optional to match Figma
                'title' => 'required|string|max:255', // This is "Type of Issue" in Figma
                'description' => 'nullable|string', // Optional in Figma
                'location' => 'required|string',
                'priority' => 'nullable|in:low,medium,high,critical',
                'severity' => 'nullable|in:minor,major,critical',
                'images_before' => 'nullable|array'
            ], [
                'user_id.required' => trans('api.snags.user_id_required'),
                'project_id.required' => trans('api.snags.project_id_required'),
                'title.required' => trans('api.snags.issue_type_required'),
                'location.required' => trans('api.snags.location_required'),
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $snagDetails = new Snag();
            $snagDetails->snag_number = NumberHelper::generateSnagNumber($request->project_id);
            $snagDetails->project_id = $request->project_id;
            $snagDetails->category_id = $request->category_id ?? 1; // Default category
            $snagDetails->title = $request->title; // "Type of Issue" from Figma
            $snagDetails->description = $request->description ?? '';
            $snagDetails->location = $request->location;
            $snagDetails->priority = $request->priority ?? 'medium';
            $snagDetails->severity = $request->severity ?? 'minor';
            $snagDetails->reported_by = $request->user_id;
            $snagDetails->status = 'open';
            $snagDetails->images_before = $request->images_before ?? [];
            $snagDetails->is_active = true;

            if ($snagDetails->save()) {
                // Load relationships for response
                $snagDetails->load(['reporter:id,name,role', 'category:id,name']);
                
                $response = [
                    'id' => $snagDetails->id,
                    'snag_number' => $snagDetails->snag_number,
                    'title' => $snagDetails->title,
                    'description' => $snagDetails->description,
                    'location' => $snagDetails->location,
                    'status' => 'In Progress', // Match Figma status
                    'message' => 'Snag report created successfully. You can now add images and additional details.'
                ];
                
                return $this->toJsonEnc($response, trans('api.snags.created_success'), Config::get('constant.SUCCESS'));
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

            $query = Snag::with(['reporter:id,name,role', 'assignedUser:id,name,role', 'category:id,name'])
                ->where('is_active', 1)->where('is_deleted', 0);

            if ($project_id) {
                $query->where('project_id', $project_id);
            }

            if ($status) {
                $query->where('status', $status);
            }

            if ($priority) {
                $query->where('priority', $priority);
            }

            $snags = $query->orderBy('created_at', 'desc')->paginate($limit);

            // Format response to match Figma design
            $snags->getCollection()->transform(function ($snag) {
                return [
                    'id' => $snag->id,
                    'snag_number' => $snag->snag_number,
                    'title' => $snag->title,
                    'description' => $snag->description,
                    'location' => $snag->location,
                    'status' => $snag->status === 'open' ? 'In Progress' : ucfirst($snag->status),
                    'priority' => ucfirst($snag->priority),
                    'reported_by' => $snag->reporter ? $snag->reporter->name . ' (' . ucfirst($snag->reporter->role) . ')' : 'Unknown',
                    'assigned_to' => $snag->assignedUser ? $snag->assignedUser->name . ' Team' : null,
                    'date' => $snag->created_at->format('jS M, Y'),
                    'images_before' => $snag->images_before,
                    'category' => $snag->category ? $snag->category->name : 'General'
                ];
            });

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

            $snag = Snag::with(['reporter:id,name,role', 'assignedUser:id,name,role', 'category:id,name', 'comments.user:id,name'])
                ->where('id', $snag_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$snag) {
                return $this->toJsonEnc([], trans('api.snags.not_found'), Config::get('constant.NOT_FOUND'));
            }

            // Format response to match Figma design
            $formattedSnag = [
                'id' => $snag->id,
                'snag_number' => $snag->snag_number,
                'title' => $snag->title,
                'description' => $snag->description,
                'location' => $snag->location,
                'status' => ucfirst($snag->status),
                'priority' => ucfirst($snag->priority),
                'reported_by' => $snag->reporter ? $snag->reporter->name . ' (' . ucfirst($snag->reporter->role) . ')' : 'Unknown',
                'assigned_to' => $snag->assignedUser ? $snag->assignedUser->name : null,
                'date' => $snag->created_at->format('jS M, Y'),
                'images_before' => $snag->images_before,
                'images_after' => $snag->images_after,
                'category' => $snag->category ? $snag->category->name : 'General',
                'comments' => $snag->comments->map(function($comment) {
                    return [
                        'id' => $comment->id,
                        'comment' => $comment->comment,
                        'user' => $comment->user ? $comment->user->name : 'Unknown',
                        'date' => $comment->created_at->format('jS M, Y')
                    ];
                })
            ];

            return $this->toJsonEnc($formattedSnag, trans('api.snags.details_retrieved'), Config::get('constant.SUCCESS'));
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
            if ($request->filled('severity')) $snag->severity = $request->severity;
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