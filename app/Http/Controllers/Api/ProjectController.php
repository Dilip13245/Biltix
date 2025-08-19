<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\DailyLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $query = Project::with(['projectManager', 'teamMembers']);

        // Filter based on user role
        if ($user->role === 'manager') {
            $query->where('project_manager_id', $user->id);
        } else {
            $query->whereHas('teamMembers', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $projects = $query->latest()->paginate(10);

        $projectData = $projects->map(function($project) {
            return [
                'id' => $project->id,
                'name' => $project->name,
                'description' => $project->description,
                'type' => $project->type,
                'status' => $project->status,
                'progress_percentage' => $project->progress_percentage,
                'start_date' => $project->start_date->format('Y-m-d'),
                'end_date' => $project->end_date->format('Y-m-d'),
                'budget' => $project->budget,
                'location' => $project->location,
                'client_name' => $project->client_name,
                'project_manager' => $project->projectManager ? [
                    'id' => $project->projectManager->id,
                    'name' => $project->projectManager->name,
                    'designation' => $project->projectManager->designation,
                ] : null,
                'team_count' => $project->teamMembers->count(),
                'images' => $project->images ? array_map(function($image) {
                    return asset('storage/projects/' . $image);
                }, $project->images) : [],
            ];
        });

        return $this->toJson([
            'projects' => $projectData,
            'pagination' => [
                'current_page' => $projects->currentPage(),
                'last_page' => $projects->lastPage(),
                'per_page' => $projects->perPage(),
                'total' => $projects->total(),
            ]
        ], 'Projects retrieved successfully', 200);
    }

    public function show(Request $request, $id)
    {
        $project = Project::with([
            'projectManager',
            'teamMembers',
            'tasks' => function($query) {
                $query->latest()->take(10);
            }
        ])->findOrFail($id);

        $projectData = [
            'id' => $project->id,
            'name' => $project->name,
            'description' => $project->description,
            'type' => $project->type,
            'status' => $project->status,
            'progress_percentage' => $project->progress_percentage,
            'start_date' => $project->start_date->format('Y-m-d'),
            'end_date' => $project->end_date->format('Y-m-d'),
            'budget' => $project->budget,
            'location' => $project->location,
            'client_name' => $project->client_name,
            'client_contact' => $project->client_contact,
            'project_manager' => $project->projectManager ? [
                'id' => $project->projectManager->id,
                'name' => $project->projectManager->name,
                'designation' => $project->projectManager->designation,
                'phone' => $project->projectManager->phone,
            ] : null,
            'team_members' => $project->teamMembers->map(function($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'designation' => $member->designation,
                    'role' => $member->role,
                    'profile_image' => $member->profile_image ? asset('storage/users/' . $member->profile_image) : null,
                ];
            }),
            'recent_tasks' => $project->tasks->map(function($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'status' => $task->status,
                    'priority' => $task->priority,
                    'progress_percentage' => $task->progress_percentage,
                    'due_date' => $task->due_date ? $task->due_date->format('Y-m-d') : null,
                    'assigned_to' => $task->assignedUser ? [
                        'id' => $task->assignedUser->id,
                        'name' => $task->assignedUser->name,
                    ] : null,
                ];
            }),
            'images' => $project->images ? array_map(function($image) {
                return asset('storage/projects/' . $image);
            }, $project->images) : [],
        ];

        return $this->toJson($projectData, 'Project details retrieved successfully', 200);
    }

    public function dashboard(Request $request)
    {
        $user = $request->user();

        $stats = [
            'my_projects' => Project::whereHas('teamMembers', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
            'pending_tasks' => Task::where('assigned_to', $user->id)
                ->where('status', 'pending')
                ->count(),
            'completed_tasks' => Task::where('assigned_to', $user->id)
                ->where('status', 'completed')
                ->count(),
            'daily_logs_today' => DailyLog::where('user_id', $user->id)
                ->whereDate('log_date', today())
                ->count(),
        ];

        $recent_tasks = Task::with(['project'])
            ->where('assigned_to', $user->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'status' => $task->status,
                    'priority' => $task->priority,
                    'progress_percentage' => $task->progress_percentage,
                    'due_date' => $task->due_date ? $task->due_date->format('Y-m-d') : null,
                    'project' => [
                        'id' => $task->project->id,
                        'name' => $task->project->name,
                    ],
                ];
            });

        return $this->toJson([
            'stats' => $stats,
            'recent_tasks' => $recent_tasks,
        ], 'Dashboard data retrieved successfully', 200);
    }
}