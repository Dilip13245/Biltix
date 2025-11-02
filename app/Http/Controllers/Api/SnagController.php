<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Snag;
use App\Models\SnagCategory;
use App\Models\SnagComment;
use App\Helpers\NumberHelper;
use App\Helpers\NotificationHelper;
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
                'phase_id' => 'nullable|integer',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'location' => 'required|string',
                'assigned_to' => 'nullable|integer',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240'
            ], [
                'user_id.required' => trans('api.snags.user_id_required'),
                'project_id.required' => trans('api.snags.project_id_required'),
                'title.required' => trans('api.snags.title_required'),
                'location.required' => trans('api.snags.location_required'),
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $snagDetails = new Snag();
            $snagDetails->snag_number = NumberHelper::generateSnagNumber($request->project_id);
            $snagDetails->project_id = $request->project_id;
            $snagDetails->phase_id = $request->phase_id;
            $snagDetails->title = $request->title;
            $snagDetails->description = $request->description ?? '';
            $snagDetails->location = $request->location;
            $snagDetails->reported_by = $request->user_id;
            $snagDetails->assigned_to = $request->assigned_to;
            $snagDetails->status = 'todo';
            $snagDetails->is_active = true;
            
            // Handle multiple image uploads
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $uploadResult = \App\Helpers\FileHelper::uploadFile($image, 'snags');
                    $imagePaths[] = $uploadResult['path'];
                }
                $snagDetails->image = json_encode($imagePaths);
            }

            if ($snagDetails->save()) {
                // Load relationships for response
                $snagDetails->load(['reporter:id,name', 'assignedUser:id,name']);
                
                $imageUrls = [];
                if ($snagDetails->image) {
                    $images = json_decode($snagDetails->image, true) ?: [$snagDetails->image];
                    foreach ($images as $image) {
                        $imageUrls[] = asset('storage/' . $image);
                    }
                }
                
                $response = [
                    'id' => $snagDetails->id,
                    'snag_number' => $snagDetails->snag_number,
                    'title' => $snagDetails->title,
                    'description' => $snagDetails->description,
                    'location' => $snagDetails->location,
                    'status' => $snagDetails->status,
                    'assigned_to' => $snagDetails->assignedUser ? $snagDetails->assignedUser->name : null,
                    'image_urls' => $imageUrls,
                    'message' => 'Snag created successfully.'
                ];
                
                // Send notifications for snag reporting
                $project = \App\Models\Project::find($request->project_id);
                $reporter = \App\Models\User::find($request->user_id);
                $recipients = [];
                
                if ($snagDetails->assigned_to) {
                    $recipients[] = $snagDetails->assigned_to;
                }

                if ($project && $reporter) {
                    // Notify project managers
                    NotificationHelper::sendToProjectManagers(
                        $project->id,
                        'snag_reported',
                        'New Snag Reported',
                        "Snag '{$snagDetails->title}' reported in project '{$project->project_title}'",
                        [
                            'snag_id' => $snagDetails->id,
                            'snag_number' => $snagDetails->snag_number,
                            'snag_title' => $snagDetails->title,
                            'project_id' => $project->id,
                            'project_title' => $project->project_title,
                            'location' => $snagDetails->location,
                            'reported_by' => $request->user_id,
                            'reported_by_name' => $reporter->name,
                            'assigned_to' => $snagDetails->assigned_to,
                            'status' => $snagDetails->status,
                            'action_url' => "/snags/{$snagDetails->id}"
                        ],
                        'high',
                        [$request->user_id]
                    );

                    // Notify assigned user if different from reporter
                    if ($snagDetails->assigned_to && $snagDetails->assigned_to != $request->user_id) {
                        NotificationHelper::send(
                            $snagDetails->assigned_to,
                            'snag_assigned',
                            'Snag Assigned to You',
                            "Snag '{$snagDetails->title}' has been assigned to you",
                            [
                                'snag_id' => $snagDetails->id,
                                'snag_title' => $snagDetails->title,
                                'project_id' => $project->id,
                                'assigned_by' => $request->user_id,
                                'action_url' => "/snags/{$snagDetails->id}"
                            ],
                            'high'
                        );
                    }
                }
                
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
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 10);

            $query = Snag::with(['reporter:id,name', 'assignedUser:id,name'])
                ->where('is_active', 1)->where('is_deleted', 0);

            if ($project_id) {
                $query->where('project_id', $project_id);
            }

            if ($request->input('phase_id')) {
                $query->where('phase_id', $request->input('phase_id'));
            }

            if ($status) {
                $query->where('status', $status);
            }

            $snags = $query->orderBy('created_at', 'desc')->paginate($limit, ['*'], 'page', $page);

            $snagsData = collect($snags->items())->map(function ($snag) {
                $imageUrls = [];
                if ($snag->image) {
                    $images = json_decode($snag->image, true) ?: [$snag->image];
                    foreach ($images as $image) {
                        $imageUrls[] = asset('storage/' . $image);
                    }
                }
                
                return [
                    'id' => $snag->id,
                    'snag_number' => $snag->snag_number,
                    'title' => $snag->title,
                    'description' => $snag->description,
                    'location' => $snag->location,
                    'status' => ucfirst($snag->status),
                    'reported_by' => $snag->reporter ? $snag->reporter->name : 'Unknown',
                    'assigned_to' => $snag->assignedUser ? $snag->assignedUser->name : null,
                    'date' => $snag->created_at->format('jS M, Y'),
                    'image_urls' => $imageUrls,
                    'comment' => $snag->comment,
                ];
            });

            $data = [
                'data' => $snagsData
            ];

            return $this->toJsonEnc($data, trans('api.snags.list_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function details(Request $request)
    {
        try {
            $snag_id = $request->input('snag_id');
            $user_id = $request->input('user_id');

            $snag = Snag::with(['reporter:id,name', 'assignedUser:id,name'])
                ->where('id', $snag_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$snag) {
                return $this->toJsonEnc([], trans('api.snags.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $imageUrls = [];
            if ($snag->image) {
                $images = json_decode($snag->image, true) ?: [$snag->image];
                foreach ($images as $image) {
                    $imageUrls[] = asset('storage/' . $image);
                }
            }
            
            $formattedSnag = [
                'id' => $snag->id,
                'snag_number' => $snag->snag_number,
                'title' => $snag->title,
                'description' => $snag->description,
                'location' => $snag->location,
                'status' => $snag->status,
                'reported_by' => $snag->reporter ? $snag->reporter->name : 'Unknown',
                'assigned_to' => $snag->assignedUser ? $snag->assignedUser->name : null,
                'assigned_to_id' => $snag->assigned_to,
                'date' => $snag->created_at->format('jS M, Y'),
                'image_urls' => $imageUrls,
                'comment' => $snag->comment,
                'has_comment' => !empty($snag->comment)
            ];

            return $this->toJsonEnc($formattedSnag, trans('api.snags.details_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'snag_id' => 'required|integer',
                'user_id' => 'required|integer',
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'location' => 'nullable|string',
                'assigned_to' => 'nullable|integer',
                'status' => 'nullable|in:todo,in_progress,complete,approve',
                'comment' => 'nullable|string',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240'
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $snag = Snag::where('id', $request->snag_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$snag) {
                return $this->toJsonEnc([], trans('api.snags.not_found'), Config::get('constant.NOT_FOUND'));
            }

            // Check if user is assigned to this snag (only for status changes)
            if ($request->filled('status') && $snag->assigned_to && $snag->assigned_to != $request->user_id) {
                return $this->toJsonEnc([], trans('api.snags.not_assigned_to_user'), Config::get('constant.FORBIDDEN'));
            }

            // Update fields if provided
            if ($request->filled('title')) $snag->title = $request->title;
            if ($request->filled('description')) $snag->description = $request->description;
            if ($request->filled('location')) $snag->location = $request->location;
            if ($request->filled('assigned_to')) $snag->assigned_to = $request->assigned_to;
            if ($request->filled('status')) $snag->status = $request->status;
            if ($request->filled('comment')) $snag->comment = $request->comment;
            
            // Handle image uploads
            if ($request->hasFile('images')) {
                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $uploadResult = \App\Helpers\FileHelper::uploadFile($image, 'snags');
                    $imagePaths[] = $uploadResult['path'];
                }
                $snag->image = json_encode($imagePaths);
            }

            $snag->save();
            
            // Load relationships and return updated snag
            $snag->load(['reporter:id,name', 'assignedUser:id,name']);
            
            $imageUrls = [];
            if ($snag->image) {
                $images = json_decode($snag->image, true) ?: [$snag->image];
                foreach ($images as $image) {
                    $imageUrls[] = asset('storage/' . $image);
                }
            }
            
            $response = [
                'id' => $snag->id,
                'snag_number' => $snag->snag_number,
                'title' => $snag->title,
                'description' => $snag->description,
                'location' => $snag->location,
                'status' => ucfirst($snag->status),
                'reported_by' => $snag->reporter ? $snag->reporter->name : 'Unknown',
                'assigned_to' => $snag->assignedUser ? $snag->assignedUser->name : null,
                'date' => $snag->created_at->format('jS M, Y'),
                'image_urls' => $imageUrls
            ];

            return $this->toJsonEnc($response, trans('api.snags.updated_success'), Config::get('constant.SUCCESS'));
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

            // Check if user is assigned to this snag
            if ($snag->assigned_to && $snag->assigned_to != $user_id) {
                return $this->toJsonEnc([], trans('api.snags.not_assigned_to_user'), Config::get('constant.FORBIDDEN'));
            }

            // Only allow resolve if status is complete
            if ($snag->status !== 'complete') {
                return $this->toJsonEnc([], trans('api.snags.must_be_complete'), Config::get('constant.ERROR'));
            }

            $snag->status = 'approve';
            $snag->resolution_notes = $resolution_notes;
            $snag->resolved_by = $user_id;
            $snag->resolved_at = now();
            $snag->save();

            // Send notification for snag resolution
            $project = \App\Models\Project::find($snag->project_id);
            $resolver = \App\Models\User::find($user_id);
            if ($project && $resolver) {
                $recipients = [$snag->reported_by];
                if ($project->project_manager_id) {
                    $recipients[] = $project->project_manager_id;
                }
                if ($project->technical_engineer_id && !in_array($project->technical_engineer_id, $recipients)) {
                    $recipients[] = $project->technical_engineer_id;
                }

                NotificationHelper::send(
                    array_unique(array_diff($recipients, [$user_id])),
                    'snag_resolved',
                    'Snag Resolved',
                    "Snag '{$snag->title}' has been resolved by {$resolver->name}",
                    [
                        'snag_id' => $snag->id,
                        'snag_title' => $snag->title,
                        'project_id' => $project->id,
                        'resolver_id' => $user_id,
                        'resolver_name' => $resolver->name,
                        'resolved_at' => now()->toDateTimeString(),
                        'action_url' => "/snags/{$snag->id}"
                    ],
                    'medium'
                );
            }

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

            $oldAssignedTo = $snag->assigned_to;
            $snag->assigned_to = $assigned_to;
            $snag->status = 'in_progress';
            $snag->save();

            // Send notification for snag assignment
            $project = \App\Models\Project::find($snag->project_id);
            if ($project && $assigned_to != $snag->reported_by) {
                NotificationHelper::send(
                    $assigned_to,
                    'snag_assigned',
                    'Snag Assigned to You',
                    "Snag '{$snag->title}' has been assigned to you",
                    [
                        'snag_id' => $snag->id,
                        'snag_title' => $snag->title,
                        'project_id' => $project->id,
                        'assigned_by' => $user_id,
                        'action_url' => "/snags/{$snag->id}"
                    ],
                    'high'
                );
            }

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

            // Send notifications for snag comment
            $recipients = [$snag->reported_by];
            if ($snag->assigned_to) {
                $recipients[] = $snag->assigned_to;
            }

            $project = \App\Models\Project::find($snag->project_id);
            if ($project && $project->project_manager_id) {
                $recipients[] = $project->project_manager_id;
            }

            $commenter = \App\Models\User::find($user_id);
            if ($commenter) {
                NotificationHelper::send(
                    array_unique(array_diff($recipients, [$user_id])),
                    'snag_comment',
                    'New Comment on Snag',
                    "{$commenter->name} commented on snag '{$snag->title}'",
                    [
                        'snag_id' => $snag->id,
                        'snag_title' => $snag->title,
                        'comment_id' => $snagComment->id,
                        'commenter_id' => $user_id,
                        'commenter_name' => $commenter->name,
                        'comment_preview' => substr($comment, 0, 100),
                        'project_id' => $snag->project_id,
                        'action_url' => "/snags/{$snag->id}#comment_{$snagComment->id}"
                    ],
                    'medium'
                );
            }

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