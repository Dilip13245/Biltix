<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Models\Task;
use App\Models\Inspection;
use App\Helpers\NumberHelper;
use App\Helpers\FileHelper;
use App\Models\ProjectPhase;
use App\Models\PhaseMilestone;
use App\Models\File;
use App\Models\FileCategory;
use App\Models\FileFolder;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'project_title' => 'required|string|max:255',
                'contractor_name' => 'nullable|string|max:255',
                'project_manager_id' => 'nullable|integer|exists:users,id',
                'technical_engineer_id' => 'nullable|integer|exists:users,id',
                'type' => 'required|string|max:255',
                'project_location' => 'required|string|max:255',
                'project_start_date' => 'required|date',
                'project_due_date' => 'required|date|after:project_start_date',
                'priority' => 'nullable|in:low,medium,high,critical',
                'construction_plans' => 'nullable|array',
                'construction_plans.*' => 'nullable|file|max:25600',
                'gantt_chart' => 'nullable|array', 
                'gantt_chart.*' => 'nullable|file|max:25600',
                'file_notes' => 'nullable|string',
            ], [
                'project_title.required' => 'Project Title is required',
                'type.required' => 'Project Type is mandatory',
                'project_location.required' => 'Project Location is mandatory',
                'project_start_date.required' => 'Project Start Date is required',
                'project_due_date.required' => 'Project Due Date is required',
                'project_due_date.after' => 'Project Due Date must be after Start Date',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $projectDetails = new Project();
            $projectDetails->project_code = NumberHelper::generateProjectCode();
            $projectDetails->project_title = $request->project_title;
            $projectDetails->contractor_name = $request->contractor_name;
            $projectDetails->project_manager_id = $request->project_manager_id;
            $projectDetails->technical_engineer_id = $request->technical_engineer_id;
            $projectDetails->type = $request->type;
            $projectDetails->project_location = $request->project_location;
            $projectDetails->latitude = $request->latitude;
            $projectDetails->longitude = $request->longitude;
            $projectDetails->project_start_date = $request->project_start_date;
            $projectDetails->project_due_date = $request->project_due_date;
            $projectDetails->priority = $request->priority ?? 'medium';
            $projectDetails->created_by = $request->user_id;
            $projectDetails->status = 'planning';
            $projectDetails->is_active = true;

            if ($projectDetails->save()) {
                // Get or create file categories
                $constructionPlansCategory = FileCategory::firstOrCreate(['name' => 'Construction Plans']);
                $ganttChartCategory = FileCategory::firstOrCreate(['name' => 'Gantt Charts']);
                
                // Create folders for project files
                $constructionPlansFolder = \App\Models\FileFolder::create([
                    'project_id' => $projectDetails->id,
                    'name' => 'Construction Plans',
                    'description' => 'Project construction plans and documents',
                    'created_by' => $request->user_id
                ]);
                
                $ganttChartFolder = \App\Models\FileFolder::create([
                    'project_id' => $projectDetails->id,
                    'name' => 'Gantt Chart',
                    'description' => 'Project timeline and Gantt charts',
                    'created_by' => $request->user_id
                ]);

                // Parse file notes if provided
                $fileNotes = [];
                if ($request->has('file_notes') && $request->file_notes) {
                    $fileNotes = json_decode($request->file_notes, true) ?? [];
                }

                // Handle construction plans upload
                if ($request->hasFile('construction_plans')) {
                    $constructionFiles = $request->file('construction_plans');
                    // Handle both single file and array of files
                    if (!is_array($constructionFiles)) {
                        $constructionFiles = [$constructionFiles];
                    }
                    foreach ($constructionFiles as $index => $file) {
                        $fileData = FileHelper::uploadFile($file, 'projects/documents');
                        
                        // Get note for this file
                        $noteKey = "file_notes_construction-files_{$index}";
                        $description = $fileNotes[$noteKey] ?? null;
                        
                        File::create([
                            'project_id' => $projectDetails->id,
                            'category_id' => $constructionPlansCategory->id,
                            'folder_id' => $constructionPlansFolder->id,
                            'name' => $fileData['filename'],
                            'original_name' => $fileData['original_name'],
                            'file_path' => $fileData['path'],
                            'file_size' => $fileData['size'],
                            'file_type' => $fileData['mime_type'],
                            'description' => $description,
                            'uploaded_by' => $request->user_id
                        ]);
                    }
                }

                // Handle gantt chart upload - separate images and documents
                if ($request->hasFile('gantt_chart')) {
                    $ganttFiles = $request->file('gantt_chart');
                    if (!is_array($ganttFiles)) {
                        $ganttFiles = [$ganttFiles];
                    }
                    foreach ($ganttFiles as $index => $file) {
                        $isImage = strpos($file->getMimeType(), 'image/') === 0;
                        $storagePath = $isImage ? 'projects/gantt_images' : 'projects/gantt_documents';
                        $fileData = FileHelper::uploadFile($file, $storagePath);
                        
                        // Get note for this file
                        $noteKey = "file_notes_gantt-files_{$index}";
                        $description = $fileNotes[$noteKey] ?? null;
                        
                        File::create([
                            'project_id' => $projectDetails->id,
                            'category_id' => $ganttChartCategory->id,
                            'folder_id' => $ganttChartFolder->id,
                            'name' => $fileData['filename'],
                            'original_name' => $fileData['original_name'],
                            'file_path' => $fileData['path'],
                            'file_size' => $fileData['size'],
                            'file_type' => $fileData['mime_type'],
                            'description' => $description,
                            'uploaded_by' => $request->user_id,
                            'is_drawing' => $isImage
                        ]);
                    }
                }

                // Send notifications
                $recipients = [];
                $creator = \App\Models\User::find($request->user_id);
                if ($projectDetails->project_manager_id) {
                    $recipients[] = $projectDetails->project_manager_id;
                }
                if ($projectDetails->technical_engineer_id && !in_array($projectDetails->technical_engineer_id, $recipients)) {
                    $recipients[] = $projectDetails->technical_engineer_id;
                }
                if (!empty($recipients)) {
                    NotificationHelper::send(
                        $recipients,
                        'project_created',
                        'New Project Created',
                        "Project '{$projectDetails->project_title}' has been created by {$creator->name}",
                        [
                            'project_id' => $projectDetails->id,
                            'project_code' => $projectDetails->project_code,
                            'project_title' => $projectDetails->project_title,
                            'creator_id' => $request->user_id,
                            'creator_name' => $creator->name,
                            'action_url' => "/projects/{$projectDetails->id}"
                        ],
                        'high'
                    );
                }

                return $this->toJsonEnc($projectDetails, trans('api.projects.created_success'), Config::get('constant.SUCCESS'));
            } else {
                return $this->toJsonEnc([], trans('api.projects.creation_failed'), Config::get('constant.ERROR'));
            }
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function list(Request $request)
    {
        try {
            $search = $request->input('search');
            $type = $request->input('type'); // ongoing or completed
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 10);
            $user_id = $request->input('user_id');

            $query = Project::where('is_active', 1)->where('is_deleted', 0);

            // Get project IDs created by user
            $createdProjectIds = Project::where('created_by', $user_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->pluck('id');

            // Get project IDs assigned to user via team_members (only active projects)
            $assignedProjectIds = \App\Models\TeamMember::where('user_id', $user_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->whereHas('project', function($q) {
                    $q->where('is_active', 1)->where('is_deleted', 0);
                })
                ->pluck('project_id');

            // Merge both project IDs
            $allProjectIds = $createdProjectIds->merge($assignedProjectIds)->unique();

            // Filter projects based on user access
            $query->whereIn('id', $allProjectIds);

            if ($type) {
                if ($type === 'ongoing') {
                    $query->whereIn('status', ['planning', 'active', 'on_hold', ]);
                } elseif ($type === 'completed') {
                    $query->where('status', 'completed');
                }
            }

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('project_title', 'like', "%{$search}%")
                        ->orWhere('project_location', 'like', "%{$search}%")
                        ->orWhere('contractor_name', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%");
                });
            }

            $projects = $query->orderBy('created_at', 'desc')->paginate($limit, ['*'], 'page', $page);

            $data = [
                'data' => $projects->items()
            ];

            return $this->toJsonEnc($data, 'Projects retrieved successfully', Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function details(Request $request)
    {
        try {
            $project_id = $request->input('project_id');
            $user_id = $request->input('user_id');

            $project = Project::where('id', $project_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$project) {
                return $this->toJsonEnc([], trans('api.projects.not_found'), Config::get('constant.NOT_FOUND'));
            }

            // Add user names for project manager and technical engineer
            $projectManager = $project->project_manager_id ? User::find($project->project_manager_id) : null;
            $technicalEngineer = $project->technical_engineer_id ? User::find($project->technical_engineer_id) : null;
            
            $project->project_manager_name = $projectManager ? $projectManager->name : null;
            $project->technical_engineer_name = $technicalEngineer ? $technicalEngineer->name : null;

            return $this->toJsonEnc($project, trans('api.projects.details_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function update(Request $request)
    {
        try {
            $project_id = $request->input('project_id');
            $user_id = $request->input('user_id');

            $project = Project::where('id', $project_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$project) {
                return $this->toJsonEnc([], trans('api.projects.not_found'), Config::get('constant.NOT_FOUND'));
            }

            if ($request->filled('project_title')) $project->project_title = $request->project_title;
            if ($request->filled('contractor_name')) $project->contractor_name = $request->contractor_name;
            if ($request->filled('project_manager_id')) $project->project_manager_id = $request->project_manager_id;
            if ($request->filled('technical_engineer_id')) $project->technical_engineer_id = $request->technical_engineer_id;
            if ($request->filled('type')) $project->type = $request->type;
            if ($request->filled('project_location')) $project->project_location = $request->project_location;
            if ($request->filled('latitude')) $project->latitude = $request->latitude;
            if ($request->filled('longitude')) $project->longitude = $request->longitude;
            if ($request->filled('project_start_date')) $project->project_start_date = $request->project_start_date;
            if ($request->filled('project_due_date')) $project->project_due_date = $request->project_due_date;
            if ($request->filled('priority')) $project->priority = $request->priority;
            
            $oldStatus = $project->status;
            if ($request->filled('status')) $project->status = $request->status;

            $project->save();

            // Send notifications for project updates
            $updater = \App\Models\User::find($user_id);
            if ($updater) {
                // Direct notification to updater
                NotificationHelper::send(
                    $user_id,
                    'project_updated',
                    'Project Updated Successfully',
                    "Project '{$project->project_title}' has been updated successfully",
                    [
                        'project_id' => $project->id,
                        'project_title' => $project->project_title,
                        'action_url' => "/projects/{$project->id}"
                    ],
                    'low'
                );
                
                // Team notification (excluding updater)
                NotificationHelper::sendToProjectTeam(
                    $project_id,
                    'project_updated',
                    'Project Updated',
                    "{$updater->name} updated project '{$project->project_title}'",
                    [
                        'project_id' => $project->id,
                        'project_title' => $project->project_title,
                        'updated_by' => $user_id,
                        'updated_by_name' => $updater->name,
                        'action_url' => "/projects/{$project->id}"
                    ],
                    'medium',
                    [$user_id]
                );
            }

            // Send notification if status changed
            if ($request->filled('status') && $oldStatus !== $project->status) {
                $changer = \App\Models\User::find($user_id);
                if ($changer) {
                    // Team notification (excluding changer)
                    NotificationHelper::sendToProjectTeam(
                        $project_id,
                        'project_status_changed',
                        'Project Status Changed',
                        "{$changer->name} changed project '{$project->project_title}' status to {$project->status}",
                        [
                            'project_id' => $project->id,
                            'project_title' => $project->project_title,
                            'old_status' => $oldStatus,
                            'new_status' => $project->status,
                            'changed_by' => $user_id,
                            'changed_by_name' => $changer->name,
                            'action_url' => "/projects/{$project->id}"
                        ],
                        'high',
                        [$user_id]
                    );
                }
            }

            return $this->toJsonEnc($project, trans('api.projects.updated_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function delete(Request $request)
    {
        try {
            $project_id = $request->input('project_id');
            $user_id = $request->input('user_id');

            $project = Project::where('id', $project_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$project) {
                return $this->toJsonEnc([], trans('api.projects.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $project->is_deleted = true;
            $project->save();

            return $this->toJsonEnc([], trans('api.projects.deleted_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function dashboardStats(Request $request)
    {
        try {
            $user_id = $request->input('user_id');

            $activeProjects = Project::where('is_active', 1)
                ->where('is_deleted', 0)
                ->count();

            $pendingTasks = Task::where('status', 'pending')
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->count();

            $inspectionsDue = Inspection::where('status', 'scheduled')
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->count();

            $completedThisMonth = Project::where('status', 'completed')
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->whereMonth('updated_at', now()->month)
                ->count();

            $stats = [
                'active_projects' => $activeProjects,
                'pending_tasks' => $pendingTasks,
                'inspections_due' => $inspectionsDue,
                'completed_this_month' => $completedThisMonth,
            ];

            return $this->toJsonEnc($stats, trans('api.projects.stats_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function progressReport(Request $request)
    {
        try {
            $project_id = $request->input('project_id');
            $user_id = $request->input('user_id');

            $project = Project::where('id', $project_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$project) {
                return $this->toJsonEnc([], trans('api.projects.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $progressData = [
                'project_name' => $project->name,
                'overall_progress' => $project->progress_percentage,
                'start_date' => $project->start_date,
                'end_date' => $project->end_date,
                'status' => $project->status,
                'budget' => $project->budget,
                'actual_cost' => $project->actual_cost,
            ];

            return $this->toJsonEnc($progressData, trans('api.projects.progress_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function createPhase(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'project_id' => 'required|integer',
                'title' => 'required|string|max:255',
                'milestones' => 'nullable|array',
                'milestones.*.milestone_name' => 'required_with:milestones|string|max:255',
                'milestones.*.days' => 'nullable|integer|min:1',

            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $phaseDetails = new ProjectPhase();
            $phaseDetails->project_id = $request->project_id;
            $phaseDetails->title = $request->title;
            $phaseDetails->created_by = $request->user_id;
            $phaseDetails->is_active = true;

            if ($phaseDetails->save()) {
                // Create milestones if provided
                if ($request->has('milestones') && is_array($request->milestones)) {
                    foreach ($request->milestones as $milestone) {
                        PhaseMilestone::create([
                            'phase_id' => $phaseDetails->id,
                            'milestone_name' => $milestone['milestone_name'],
                            'days' => $milestone['days'] ?? null,

                            'is_active' => true,
                            'is_deleted' => false
                        ]);
                    }
                }

                // Send notification for phase creation
                $project = Project::find($request->project_id);
                $creator = \App\Models\User::find($request->user_id);
                if ($project && $creator) {
                    // Direct notification to creator
                    NotificationHelper::send(
                        $request->user_id,
                        'phase_created',
                        'Phase Created Successfully',
                        "Phase '{$phaseDetails->title}' has been created successfully",
                        [
                            'project_id' => $project->id,
                            'phase_id' => $phaseDetails->id,
                            'phase_title' => $phaseDetails->title,
                            'action_url' => "/projects/{$project->id}/phases/{$phaseDetails->id}"
                        ],
                        'low'
                    );
                    
                    // Team notification (excluding creator)
                    NotificationHelper::sendToProjectTeam(
                        $project->id,
                        'phase_created',
                        'New Phase Created',
                        "{$creator->name} added phase '{$phaseDetails->title}' to project",
                        [
                            'project_id' => $project->id,
                            'project_title' => $project->project_title,
                            'phase_id' => $phaseDetails->id,
                            'phase_title' => $phaseDetails->title,
                            'created_by' => $request->user_id,
                            'created_by_name' => $creator->name,
                            'action_url' => "/projects/{$project->id}/phases/{$phaseDetails->id}"
                        ],
                        'medium',
                        [$request->user_id]
                    );
                }

                $phaseDetails->load('milestones');
                return $this->toJsonEnc($phaseDetails, trans('api.projects.phase_created'), Config::get('constant.SUCCESS'));
            } else {
                return $this->toJsonEnc([], trans('api.projects.phase_creation_failed'), Config::get('constant.ERROR'));
            }
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function listPhases(Request $request)
    {
        try {
            $project_id = $request->input('project_id');
            $user_id = $request->input('user_id');

            $phases = ProjectPhase::with('milestones')
                ->where('project_id', $project_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->orderBy('created_at', 'desc')
                ->get();

            // Add calculated time progress for each phase
            $phases->each(function ($phase) {
                $phase->time_progress = $phase->time_progress;
                $phase->has_extensions = $phase->has_extensions;
                $phase->total_extension_days = $phase->total_extension_days;
                
                $phase->milestones->each(function ($milestone) {
                    $milestone->time_progress = $milestone->time_progress;
                    $milestone->is_overdue = $milestone->is_overdue;
                    $milestone->extension_days = $milestone->extension_days;
                });
            });

            return $this->toJsonEnc($phases, trans('api.projects.phases_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function updatePhase(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phase_id' => 'required|integer',
                'user_id' => 'required|integer',
                'title' => 'required|string|max:255',
                'milestones' => 'required|array|min:1',
                'milestones.*.milestone_name' => 'required|string|max:255',
                'milestones.*.days' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $phase_id = $request->input('phase_id');
            $user_id = $request->input('user_id');

            $phase = ProjectPhase::where('id', $phase_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$phase) {
                return $this->toJsonEnc([], trans('api.projects.phase_not_found'), Config::get('constant.NOT_FOUND'));
            }

            $phase->title = $request->title;
            $phase->save();

            // Delete existing milestones
            PhaseMilestone::where('phase_id', $phase_id)->update(['is_deleted' => true]);

            // Create new milestones
            foreach ($request->milestones as $milestone) {
                PhaseMilestone::create([
                    'phase_id' => $phase_id,
                    'milestone_name' => $milestone['milestone_name'],
                    'days' => $milestone['days'],
                    'is_active' => true,
                    'is_deleted' => false
                ]);
            }
            
            // Send notification for phase update
            $project = Project::find($phase->project_id);
            $updater = \App\Models\User::find($user_id);
            if ($project && $updater) {
                NotificationHelper::send(
                    $user_id,
                    'phase_updated',
                    'Phase Updated Successfully',
                    "Phase '{$phase->title}' has been updated",
                    [
                        'project_id' => $project->id,
                        'phase_id' => $phase->id,
                        'action_url' => "/projects/{$project->id}/phases"
                    ],
                    'low'
                );
                
                NotificationHelper::sendToProjectTeam(
                    $project->id,
                    'phase_updated',
                    'Phase Updated',
                    "{$updater->name} updated phase '{$phase->title}'",
                    [
                        'project_id' => $project->id,
                        'phase_id' => $phase->id,
                        'action_url' => "/projects/{$project->id}/phases"
                    ],
                    'low',
                    [$user_id]
                );
            }

            $phase->load('milestones');
            return $this->toJsonEnc($phase, trans('api.projects.phase_updated'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function deletePhase(Request $request)
    {
        try {
            $phase_id = $request->input('phase_id');
            $user_id = $request->input('user_id');

            $phase = ProjectPhase::where('id', $phase_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$phase) {
                return $this->toJsonEnc([], trans('api.projects.phase_not_found'), Config::get('constant.NOT_FOUND'));
            }

            $phaseTitle = $phase->title;
            $projectId = $phase->project_id;
            $phase->is_deleted = true;
            $phase->save();
            
            // Send notification for phase deletion
            $project = Project::find($projectId);
            $deleter = \App\Models\User::find($user_id);
            if ($project && $deleter) {
                NotificationHelper::sendToProjectTeam(
                    $project->id,
                    'phase_deleted',
                    'Phase Deleted',
                    "{$deleter->name} deleted phase '{$phaseTitle}'",
                    [
                        'project_id' => $project->id,
                        'phase_title' => $phaseTitle,
                        'action_url' => "/projects/{$project->id}/phases"
                    ],
                    'medium',
                    [$user_id]
                );
            }

            return $this->toJsonEnc([], trans('api.projects.phase_deleted'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function timeline(Request $request)
    {
        try {
            $project_id = $request->input('project_id');
            $user_id = $request->input('user_id');

            $project = Project::where('id', $project_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$project) {
                return $this->toJsonEnc([], trans('api.projects.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $phases = ProjectPhase::with('milestones')
                ->where('project_id', $project_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->orderBy('created_at')
                ->get();

            $timelineData = [
                'project' => $project,
                'phases' => $phases,
                'total_duration' => $project->start_date->diffInDays($project->end_date),
                'days_elapsed' => $project->start_date->diffInDays(now()),
                'days_remaining' => now()->diffInDays($project->end_date),
            ];

            return $this->toJsonEnc($timelineData, trans('api.projects.timeline_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function updatePhaseProgress(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phase_id' => 'required|integer',
                'user_id' => 'required|integer',
                'progress_percentage' => 'required|numeric|min:0|max:100',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $phase = ProjectPhase::where('id', $request->phase_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$phase) {
                return $this->toJsonEnc([], trans('api.projects.phase_not_found'), Config::get('constant.NOT_FOUND'));
            }

            $oldProgress = $phase->progress_percentage ?? 0;
            $phase->progress_percentage = $request->progress_percentage;
            $phase->save();

            // Send notification for phase progress update
            $project = Project::find($phase->project_id);
            $updater = \App\Models\User::find($request->user_id);
            if ($project && $updater) {
                // Direct notification to updater
                NotificationHelper::send(
                    $request->user_id,
                    'phase_progress_updated',
                    'Phase Progress Updated Successfully',
                    "Phase '{$phase->title}' progress updated to {$request->progress_percentage}%",
                    [
                        'project_id' => $project->id,
                        'phase_id' => $phase->id,
                        'phase_title' => $phase->title,
                        'new_progress' => $request->progress_percentage,
                        'action_url' => "/projects/{$project->id}/phases/{$phase->id}"
                    ],
                    'low'
                );
                
                // Team notification (excluding updater)
                NotificationHelper::sendToProjectTeam(
                    $project->id,
                    'phase_progress_updated',
                    'Phase Progress Updated',
                    "{$updater->name} updated phase '{$phase->title}' progress to {$request->progress_percentage}%",
                    [
                        'project_id' => $project->id,
                        'phase_id' => $phase->id,
                        'phase_title' => $phase->title,
                        'old_progress' => $oldProgress,
                        'new_progress' => $request->progress_percentage,
                        'updated_by' => $request->user_id,
                        'updated_by_name' => $updater->name,
                        'action_url' => "/projects/{$project->id}/phases/{$phase->id}"
                    ],
                    'low',
                    [$request->user_id]
                );
            }

            return $this->toJsonEnc($phase, trans('api.projects.phase_progress_updated'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function updateMilestoneDueDate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'milestone_id' => 'required|integer',
                'user_id' => 'required|integer',
                'extension_days' => 'required|integer|min:0|max:3650',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $milestone = PhaseMilestone::with('phase.project')->where('id', $request->milestone_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$milestone) {
                return $this->toJsonEnc([], 'Milestone not found', Config::get('constant.NOT_FOUND'));
            }

            $extensionDays = $request->extension_days;
            $isExtension = $extensionDays > 0;

            $oldExtensionDays = $milestone->extension_days ?? 0;
            $milestone->extension_days = $extensionDays;
            $milestone->is_extended = $isExtension;
            $milestone->save();

            // Send notification if milestone extended
            if ($isExtension) {
                $project = $milestone->phase->project ?? null;
                $extender = \App\Models\User::find($request->user_id);
                if ($project && $extender) {
                    // Direct notification to extender
                    NotificationHelper::send(
                        $request->user_id,
                        'milestone_extended',
                        'Milestone Extended Successfully',
                        "Milestone '{$milestone->milestone_name}' extended by {$extensionDays} days",
                        [
                            'project_id' => $project->id,
                            'phase_id' => $milestone->phase_id,
                            'milestone_id' => $milestone->id,
                            'milestone_name' => $milestone->milestone_name,
                            'extension_days' => $extensionDays,
                            'action_url' => "/projects/{$project->id}/phases/{$milestone->phase_id}"
                        ],
                        'low'
                    );
                    
                    // Team notification (excluding extender)
                    NotificationHelper::sendToProjectTeam(
                        $project->id,
                        'milestone_extended',
                        'Milestone Extended',
                        "{$extender->name} extended milestone '{$milestone->milestone_name}' by {$extensionDays} days",
                        [
                            'project_id' => $project->id,
                            'phase_id' => $milestone->phase_id,
                            'milestone_id' => $milestone->id,
                            'milestone_name' => $milestone->milestone_name,
                            'old_extension_days' => $oldExtensionDays,
                            'new_extension_days' => $extensionDays,
                            'extended_by' => $request->user_id,
                            'extended_by_name' => $extender->name,
                            'action_url' => "/projects/{$project->id}/phases/{$milestone->phase_id}"
                        ],
                        'medium',
                        [$request->user_id]
                    );
                }
            }

            // Load the milestone with calculated attributes
            $milestone->load('phase.project');
            $milestoneData = [
                'id' => $milestone->id,
                'milestone_name' => $milestone->milestone_name,
                'days' => $milestone->days,
                'extension_days' => $milestone->extension_days,
                'is_extended' => $milestone->is_extended,
                'start_date' => $milestone->start_date ? $milestone->start_date->format('Y-m-d') : null,
                'due_date' => $milestone->due_date ? $milestone->due_date->format('Y-m-d') : null,
                'original_due_date' => $milestone->original_due_date ? $milestone->original_due_date->format('Y-m-d') : null,
                'time_progress' => $milestone->time_progress,
                'is_overdue' => $milestone->is_overdue,
            ];

            return $this->toJsonEnc($milestoneData, 'Milestone due date updated successfully', Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }
}
