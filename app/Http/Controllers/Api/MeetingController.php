<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\MeetingDocument;
use App\Models\MeetingAttendee;
use App\Models\Project;
use App\Models\User;
use App\Helpers\FileHelper;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;

class MeetingController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Meeting::active()->with(['creator', 'attendees', 'phase']);

            if ($request->has('project_id')) {
                $query->where('project_id', $request->project_id);
            }

            if ($request->has('phase_id')) {
                $query->where('phase_id', $request->phase_id);
            }

            if ($request->has('date')) {
                $query->whereDate('date', $request->date);
            }
            
            if ($request->has('status')) {
                if ($request->status === 'upcoming') {
                    $query->whereDate('date', '>=', now()->toDateString())
                          ->where('status', '!=', 'cancelled');
                } elseif ($request->status === 'completed') {
                    $query->whereDate('date', '<', now()->toDateString())
                          ->where('status', '!=', 'cancelled');
                } elseif ($request->status !== 'all') {
                    $query->where('status', $request->status);
                }
            }

            $meetings = $query->orderBy('date', 'asc')->orderBy('start_time', 'asc')->get();

            return $this->toJsonEnc($meetings, trans('api.meetings.list_retrieved') ?? 'Meetings retrieved successfully', Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'project_id' => 'required|exists:projects,id',
                'phase_id' => 'nullable|exists:project_phases,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'date' => 'required|date',
                'start_time' => 'required',
                'end_time' => 'required',
                'location_type' => 'required|in:physical,online',
                'location' => 'required|string',
                'attendees' => 'nullable|array',
                'attendees.*' => 'exists:users,id',
                'documents' => 'nullable|array',
                'documents.*' => 'file|max:10240' // 10MB max
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            DB::beginTransaction();

            $meeting = new Meeting();
            $meeting->project_id = $request->project_id;
            $meeting->phase_id = $request->phase_id;
            $meeting->title = $request->title;
            $meeting->description = $request->description;
            $meeting->date = $request->date;
            $meeting->start_time = $request->start_time;
            $meeting->end_time = $request->end_time;
            $meeting->location_type = $request->location_type;
            $meeting->location = $request->location;
            $meeting->created_by = $request->user_id;
            $meeting->status = 'scheduled';
            $meeting->is_active = true;
            $meeting->save();

            // Attach attendees
            if ($request->has('attendees') && is_array($request->attendees)) {
                $meeting->attendees()->attach($request->attendees);
                
                // Send notification to attendees
                $project = Project::find($request->project_id);
                $creator = User::find($request->user_id);
                
                if ($project && $creator) {
                    NotificationHelper::send(
                        $request->attendees,
                        'meeting_created',
                        'New Meeting Scheduled',
                        "You have been invited to a meeting '{$meeting->title}' by {$creator->name}",
                        [
                            'project_id' => $project->id,
                            'meeting_id' => $meeting->id,
                            'meeting_title' => $meeting->title,
                            'date' => $meeting->date,
                            'time' => $meeting->start_time,
                            'action_url' => "/projects/{$project->id}/meetings"
                        ],
                        'medium'
                    );
                }
            }

            // Handle documents
            if ($request->hasFile('documents')) {
                $files = $request->file('documents');
                if (!is_array($files)) {
                    $files = [$files];
                }
                
                foreach ($files as $file) {
                    $fileData = FileHelper::uploadFile($file, 'meetings/documents');
                    
                    MeetingDocument::create([
                        'meeting_id' => $meeting->id,
                        'file_path' => $fileData['path'], // FileHelper returns relative path from storage/app/public
                        'file_name' => $fileData['original_name'],
                        'file_type' => $fileData['mime_type'], // Or extension if preferred, but helper gives mime
                        'file_size' => $fileData['size'],
                        'uploaded_by' => $request->user_id
                    ]);
                }
            }

            DB::commit();

            return $this->toJsonEnc($meeting->load(['attendees', 'documents']), trans('api.meetings.created_success') ?? 'Meeting created successfully', Config::get('constant.SUCCESS'));

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function show($id)
    {
        try {
            $meeting = Meeting::with(['creator', 'attendees', 'documents.uploader', 'phase'])->find($id);

            if (!$meeting) {
                return $this->toJsonEnc([], trans('api.meetings.not_found') ?? 'Meeting not found', Config::get('constant.NOT_FOUND'));
            }

            return $this->toJsonEnc($meeting, trans('api.meetings.details_retrieved') ?? 'Meeting details retrieved successfully', Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $meeting = Meeting::find($id);

            if (!$meeting) {
                return $this->toJsonEnc([], trans('api.meetings.not_found') ?? 'Meeting not found', Config::get('constant.NOT_FOUND'));
            }

            $validator = Validator::make($request->all(), [
                'phase_id' => 'nullable|exists:project_phases,id',
                'title' => 'sometimes|string|max:255',
                'date' => 'sometimes|date',
                'start_time' => 'sometimes',
                'end_time' => 'sometimes',
                'location_type' => 'sometimes|in:physical,online',
                'location' => 'sometimes|string',
                'status' => 'sometimes|in:scheduled,completed,cancelled'
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $meeting->update($request->except(['documents', 'attendees']));

            if ($request->has('attendees')) {
                $meeting->attendees()->sync($request->attendees);
                
                // Notify updated attendees (simplified logic: notify all current attendees)
                $project = Project::find($meeting->project_id);
                $updater = User::find($request->user_id);
                
                if ($project && $updater) {
                    NotificationHelper::send(
                        $request->attendees,
                        'meeting_updated',
                        'Meeting Updated',
                        "Meeting '{$meeting->title}' has been updated by {$updater->name}",
                        [
                            'project_id' => $project->id,
                            'meeting_id' => $meeting->id,
                            'meeting_title' => $meeting->title,
                            'action_url' => "/projects/{$project->id}/meetings"
                        ],
                        'low'
                    );
                }
            }
            
            // Handle new documents if any (append, not replace)
            if ($request->hasFile('documents')) {
                $files = $request->file('documents');
                if (!is_array($files)) {
                    $files = [$files];
                }
                
                foreach ($files as $file) {
                    $fileData = FileHelper::uploadFile($file, 'meetings/documents');
                    
                    MeetingDocument::create([
                        'meeting_id' => $meeting->id,
                        'file_path' => $fileData['path'],
                        'file_name' => $fileData['original_name'],
                        'file_type' => $fileData['mime_type'],
                        'file_size' => $fileData['size'],
                        'uploaded_by' => auth()->id()
                    ]);
                }
            }

            return $this->toJsonEnc($meeting->fresh(['attendees', 'documents']), trans('api.meetings.updated_success') ?? 'Meeting updated successfully', Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $meeting = Meeting::find($id);

            if (!$meeting) {
                return $this->toJsonEnc([], trans('api.meetings.not_found') ?? 'Meeting not found', Config::get('constant.NOT_FOUND'));
            }

            $meeting->update(['is_deleted' => true, 'is_active' => false]);
            
            // Notify attendees of cancellation
            $project = Project::find($meeting->project_id);
            $deleter = User::find($request->user_id);
            
            if ($project && $deleter) {
                $attendeeIds = $meeting->attendees()->pluck('users.id')->toArray();
                if (!empty($attendeeIds)) {
                    NotificationHelper::send(
                        $attendeeIds,
                        'meeting_cancelled',
                        'Meeting Cancelled',
                        "Meeting '{$meeting->title}' has been cancelled by {$deleter->name}",
                        [
                            'project_id' => $project->id,
                            'meeting_title' => $meeting->title
                        ],
                        'high'
                    );
                }
            }

            return $this->toJsonEnc([], trans('api.meetings.deleted_success') ?? 'Meeting deleted successfully', Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }
}
