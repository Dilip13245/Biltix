<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskComment;
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
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'priority' => 'required|in:low,medium,high,critical',
                'assigned_to' => 'required|integer',
                'due_date' => 'required|date',
                'location' => 'nullable|string',
            ], [
                'user_id.required' => trans('api.tasks.user_id_required'),
                'project_id.required' => trans('api.tasks.project_id_required'),
                'title.required' => trans('api.tasks.title_required'),
                'priority.required' => trans('api.tasks.priority_required'),
                'assigned_to.required' => trans('api.tasks.assigned_to_required'),
                'due_date.required' => trans('api.tasks.due_date_required'),
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $taskDetails = new Task();
            $taskDetails->task_number = NumberHelper::generateTaskNumber($request->project_id);
            $taskDetails->project_id = $request->project_id;
            $taskDetails->phase_id = $request->phase_id;
            $taskDetails->title = $request->title;
            $taskDetails->description = $request->description ?? '';
            $taskDetails->priority = $request->priority;
            $taskDetails->assigned_to = $request->assigned_to;
            $taskDetails->created_by = $request->user_id;
            $taskDetails->due_date = $request->due_date;
            $taskDetails->location = $request->location ?? '';
            $taskDetails->attachments = $request->attachments ?? [];
            $taskDetails->status = 'pending';
            $taskDetails->is_active = true;

            if ($taskDetails->save()) {
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
            $status = $request->input('status');
            $assigned_to = $request->input('assigned_to');
            $limit = $request->input('limit', 10);

            $query = Task::where('is_active', 1)->where('is_deleted', 0);

            if ($project_id) {
                $query->where('project_id', $project_id);
            }

            if ($status) {
                $query->where('status', $status);
            }

            if ($assigned_to) {
                $query->where('assigned_to', $assigned_to);
            }

            $tasks = $query->paginate($limit);

            return $this->toJsonEnc($tasks, trans('api.tasks.list_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function details(Request $request)
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

            if ($request->filled('title')) $task->title = $request->title;
            if ($request->filled('description')) $task->description = $request->description;
            if ($request->filled('status')) $task->status = $request->status;
            if ($request->filled('priority')) $task->priority = $request->priority;
            if ($request->filled('progress_percentage')) $task->progress_percentage = $request->progress_percentage;

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

            $task->status = $status;
            if ($status === 'completed') {
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

            $task->progress_percentage = $progress_percentage;
            if ($progress_percentage >= 100) {
                $task->status = 'completed';
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