<?php

namespace App\Http\Controllers\Api;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\TaskImage;
use App\Helpers\NumberHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
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
                'priority' => 'nullable|in:low,medium,high,critical',
                'assigned_to' => 'required|integer',
                'due_date' => 'required|date',
                'location' => 'nullable|string',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            ], [
                'user_id.required' => trans('api.tasks.user_id_required'),
                'project_id.required' => trans('api.tasks.project_id_required'),
                'phase_id.required' => trans('api.tasks.phase_id_required'),
                'title.required' => trans('api.tasks.title_required'),
                'assigned_to.required' => trans('api.tasks.assigned_to_required'),
                'due_date.required' => trans('api.tasks.due_date_required'),
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            // Check if assigned user is a team member of the project
            $isTeamMember = \App\Models\TeamMember::where('user_id', $request->assigned_to)
                ->where('project_id', $request->project_id)
                ->where('is_active', true)
                ->where('is_deleted', false)
                ->exists();

            if (!$isTeamMember) {
                return $this->toJsonEnc([], trans('api.tasks.user_not_in_project'), \Illuminate\Support\Facades\Config::get('constant.ERROR'));
            }

            $taskDetails = new Task();
            $taskDetails->task_number = NumberHelper::generateTaskNumber($request->project_id);
            $taskDetails->project_id = $request->project_id;
            $taskDetails->phase_id = $request->phase_id;
            $taskDetails->title = $request->title;
            $taskDetails->description = $request->description ?? '';
            $taskDetails->priority = $request->priority ?? 'medium';
            $taskDetails->assigned_to = $request->assigned_to;
            $taskDetails->created_by = $request->user_id;
            $taskDetails->due_date = $request->due_date;
            // $taskDetails->estimated_hours = $request->estimated_hours;
            $taskDetails->location = $request->location ?? '';
            $taskDetails->status = 'todo';
            $taskDetails->is_active = true;

            if ($taskDetails->save()) {
                // Handle image uploads
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $image) {
                        $uploadResult = FileHelper::uploadFile($image, 'tasks');
                        
                        TaskImage::create([
                            'task_id' => $taskDetails->id,
                            'image_path' => $uploadResult['path'],
                            'original_name' => $uploadResult['original_name'],
                            'file_size' => $uploadResult['size'],
                            'is_active' => true,
                            'is_deleted' => false
                        ]);
                    }
                }
                
                $taskDetails->load('images');
                return $this->toJsonEnc($taskDetails, trans('api.tasks.created_success'), Config::get('constant.SUCCESS'));
            } else {
                return $this->toJsonEnc([], trans('api.tasks.creation_failed'), Config::get('constant.ERROR'));
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
            $assigned_to = $request->input('assigned_to');
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 10);

            $query = Task::where('is_active', 1)->where('is_deleted', 0);

            if ($project_id) {
                $query->where('project_id', $project_id);
            }

            if ($phase_id) {
                $query->where('phase_id', $phase_id);
            }

            if ($status) {
                $query->where('status', $status);
            }

            if ($assigned_to) {
                $query->where('assigned_to', $assigned_to);
            }

            $tasks = $query->with(['images', 'assignedUser'])->orderBy('created_at', 'desc')->paginate($limit, ['*'], 'page', $page);

            $tasksData = collect($tasks->items())->map(function ($task) {
                $task->images = $task->images->map(function ($image) {
                    $image->image_url = asset('storage/' . $image->image_path);
                    return $image;
                });
                $task->assigned_user_name = $task->assignedUser ? $task->assignedUser->name : null;
                return $task;
            });

            $data = [
                'data' => $tasksData
            ];

            return $this->toJsonEnc($data, trans('api.tasks.list_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function details(Request $request)
    {
        try {
            $task_id = $request->input('task_id');
            $user_id = $request->input('user_id');

            $task = Task::with(['images', 'assignedUser'])
                ->where('id', $task_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$task) {
                return $this->toJsonEnc([], trans('api.tasks.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $task->images = $task->images->map(function ($image) {
                $image->image_url = asset('storage/' . $image->image_path);
                return $image;
            });
            
            $task->assigned_user_name = $task->assignedUser ? $task->assignedUser->name : null;

            return $this->toJsonEnc($task, trans('api.tasks.details_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function update(Request $request)
    {
        try {
            $task_id = $request->input('task_id');
            $user_id = $request->input('user_id');

            $task = Task::where('id', $task_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$task) {
                return $this->toJsonEnc([], trans('api.tasks.not_found'), Config::get('constant.NOT_FOUND'));
            }

            // Check if user is assigned to this task
            if ($task->assigned_to != $user_id) {
                return $this->toJsonEnc([], trans('api.tasks.not_assigned_to_user'), Config::get('constant.FORBIDDEN'));
            }

            // Check if images are provided
            if ($request->hasFile('images')) {
                // Handle image uploads
                foreach ($request->file('images') as $image) {
                    $uploadResult = FileHelper::uploadFile($image, 'tasks');
                    
                    TaskImage::create([
                        'task_id' => $task->id,
                        'image_path' => $uploadResult['path'],
                        'original_name' => $uploadResult['original_name'],
                        'file_size' => $uploadResult['size'],
                        'is_active' => true,
                        'is_deleted' => false
                    ]);
                }
                $task->load('images');
            } else {
                // No images, just mark as completed
                $task->status = 'complete';
            }

            $task->save();

            return $this->toJsonEnc($task, trans('api.tasks.updated_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function delete(Request $request)
    {
        try {
            $task_id = $request->input('task_id');
            $user_id = $request->input('user_id');

            $task = Task::where('id', $task_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$task) {
                return $this->toJsonEnc([], trans('api.tasks.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $task->is_deleted = true;
            $task->save();

            return $this->toJsonEnc([], trans('api.tasks.deleted_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function changeStatus(Request $request)
    {
        try {
            $task_id = $request->input('task_id');
            $status = $request->input('status');
            $user_id = $request->input('user_id');

            $task = Task::where('id', $task_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$task) {
                return $this->toJsonEnc([], trans('api.tasks.not_found'), Config::get('constant.NOT_FOUND'));
            }

            // Check if user is assigned to this task
            if ($task->assigned_to != $user_id) {
                return $this->toJsonEnc([], trans('api.tasks.not_assigned_to_user'), Config::get('constant.FORBIDDEN'));
            }

            $task->status = $status;
            if ($status === 'complete') {
                $task->completed_at = now();
                $task->progress_percentage = 100;
            }
            $task->save();

            return $this->toJsonEnc($task, trans('api.tasks.status_changed'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function addComment(Request $request)
    {
        try {
            $task_id = $request->input('task_id');
            $comment = $request->input('comment');
            $user_id = $request->input('user_id');

            $task = Task::where('id', $task_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$task) {
                return $this->toJsonEnc([], trans('api.tasks.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $taskComment = new TaskComment();
            $taskComment->task_id = $task_id;
            $taskComment->user_id = $user_id;
            $taskComment->comment = $comment;
            $taskComment->attachments = $request->attachments ?? [];
            $taskComment->is_active = true;
            $taskComment->save();

            return $this->toJsonEnc($taskComment, trans('api.tasks.comment_added'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function updateProgress(Request $request)
    {
        try {
            $task_id = $request->input('task_id');
            $progress_percentage = $request->input('progress_percentage');
            $user_id = $request->input('user_id');

            $task = Task::where('id', $task_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$task) {
                return $this->toJsonEnc([], trans('api.tasks.not_found'), Config::get('constant.NOT_FOUND'));
            }

            // Check if user is assigned to this task
            if ($task->assigned_to != $user_id) {
                return $this->toJsonEnc([], trans('api.tasks.not_assigned_to_user'), Config::get('constant.FORBIDDEN'));
            }

            $task->progress_percentage = $progress_percentage;
            if ($progress_percentage >= 100) {
                $task->status = 'complete';
                $task->completed_at = now();
            } elseif ($progress_percentage > 0) {
                $task->status = 'in_progress';
            }
            $task->save();

            return $this->toJsonEnc($task, trans('api.tasks.progress_updated'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function assignBulk(Request $request)
    {
        try {
            $task_ids = $request->input('task_ids', []);
            $assigned_to = $request->input('assigned_to');
            $user_id = $request->input('user_id');

            $updatedTasks = [];
            foreach ($task_ids as $task_id) {
                $task = Task::where('id', $task_id)
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->first();

                if ($task) {
                    $task->assigned_to = $assigned_to;
                    $task->save();
                    $updatedTasks[] = $task;
                }
            }

            return $this->toJsonEnc($updatedTasks, trans('api.tasks.bulk_assigned'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function getComments(Request $request)
    {
        try {
            $task_id = $request->input('task_id');
            $user_id = $request->input('user_id');

            $comments = TaskComment::where('task_id', $task_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->orderBy('created_at', 'desc')
                ->get();

            return $this->toJsonEnc($comments, trans('api.tasks.comments_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function uploadAttachment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'task_id' => 'required|integer',
                'user_id' => 'required|integer',
                'attachment' => 'required|file|max:10240',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $task = Task::where('id', $request->task_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$task) {
                return $this->toJsonEnc([], trans('api.tasks.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $fileData = FileHelper::uploadFile($request->file('attachment'), 'task_attachments');
            
            $currentAttachments = $task->attachments ?? [];
            $currentAttachments[] = [
                'filename' => $fileData['filename'],
                'original_name' => $fileData['original_name'],
                'path' => $fileData['path'],
                'size' => $fileData['size'],
                'uploaded_by' => $request->user_id,
                'uploaded_at' => now()->toDateTimeString()
            ];
            
            $task->attachments = $currentAttachments;
            $task->save();

            return $this->toJsonEnc($task, trans('api.tasks.attachment_uploaded'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }
}