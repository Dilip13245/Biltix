<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectPhase;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'total_projects' => Project::count(),
            'active_projects' => Project::where('status', 'active')->count(),
            'completed_projects' => Project::where('status', 'completed')->count(),
        ];

        $featured_projects = Project::where('is_active', true)
            ->where('status', 'active')
            ->latest()
            ->take(6)
            ->get();

        return view('website.home', compact('stats', 'featured_projects'));
    }

    public function dashboard()
    {
        $user = request()->attributes->get('user');
        
        // Safety check: if user is null, redirect to login
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to continue');
        }
        
        // Get project IDs created by user
        $createdProjectIds = Project::where('created_by', $user->id)
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->pluck('id');

        // Get project IDs assigned to user via team_members (only active projects)
        $assignedProjectIds = \App\Models\TeamMember::where('user_id', $user->id)
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->whereHas('project', function($q) {
                $q->where('is_active', 1)->where('is_deleted', 0);
            })
            ->pluck('project_id');

        // Merge both project IDs
        $allProjectIds = $createdProjectIds->merge($assignedProjectIds)->unique();

        // Count total projects
        $totalProjects = $allProjectIds->count();

        // Count tasks from these projects
        $totalTasks = 0;
        if ($allProjectIds->isNotEmpty()) {
            $totalTasks = \App\Models\Task::whereIn('project_id', $allProjectIds)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->count();
        }

        // Count pending and todo tasks from these projects
        $totalPendingTasks = 0;
        if ($allProjectIds->isNotEmpty()) {
            $totalPendingTasks = \App\Models\Task::whereIn('project_id', $allProjectIds)
                ->whereIn('status', ['in_progress', 'todo'])
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->count();
        }

        // Prepare stats
        $stats = [
            'total_projects' => $totalProjects,
            'total_tasks' => $totalTasks,
            'total_pending_tasks' => $totalPendingTasks,
        ];

        return view('website.dashboard', compact('stats'));
    }

    public function plans($project_id)
    {
        $project = (object) ['id' => $project_id, 'name' => 'Sample Project'];
        return view('website.plans', compact('project'));
    }

    public function tasks($project_id)
    {
        $project = (object) ['id' => $project_id, 'name' => 'Sample Project'];
        return view('website.tasks', compact('project'));
    }

    public function inspections($project_id)
    {
        $project = (object) ['id' => $project_id, 'name' => 'Sample Project'];
        return view('website.inspections', compact('project'));
    }

    public function dailyLogs($project_id)
    {
        $project = (object) ['id' => $project_id, 'name' => 'Sample Project'];
        return view('website.daily-logs', compact('project'));
    }

    public function dailyLogRoleDescriptions($project_id)
    {
        $project = (object) ['id' => $project_id, 'name' => 'Sample Project'];
        return view('website.daily-log-role-descriptions', compact('project'));
    }

    public function projectProgress($project_id)
    {
        $project = (object) ['id' => $project_id, 'name' => 'Sample Project'];
        return view('website.project-progress', compact('project'));
    }

    public function projectFiles($project_id)
    {
        $project = (object) ['id' => $project_id, 'name' => 'Sample Project'];
        return view('website.project-files', compact('project'));
    }

    public function projectGallery($project_id)
    {
        $project = (object) ['id' => $project_id, 'name' => 'Sample Project'];
        return view('website.project-gallery', compact('project'));
    }

    public function teamMembers($project_id)
    {
        $project = (object) ['id' => $project_id, 'name' => 'Sample Project'];
        return view('website.team-members', compact('project'));
    }

    public function snagList($project_id)
    {
        $project = (object) ['id' => $project_id, 'name' => 'Sample Project'];
        return view('website.snag-list', compact('project'));
    }

    public function safetyChecklist($project_id = null)
    {
        // Always pass a project object so sidebar shows all navigation links
        $project = $project_id ? (object) ['id' => $project_id, 'name' => 'Sample Project'] : (object) ['id' => 1, 'name' => 'Current Project'];
        return view('website.safety-checklist', compact('project'));
    }

    public function notifications($project_id)
    {
        $project = (object) ['id' => $project_id, 'name' => 'Sample Project'];
        return view('website.notifications', compact('project'));
    }

    public function helpSupport($project_id)
    {
        $project = (object) ['id' => $project_id, 'name' => 'Sample Project'];
        return view('website.help-support', compact('project'));
    }
    
    // Phase-specific cloned pages
    public function phaseInspections($project_id)
    {
        $project = (object) ['id' => $project_id, 'name' => 'Sample Project'];
        return view('website.phase-inspections', compact('project'));
    }
    
    public function phaseTasks($project_id)
    {
        return view('website.phase-tasks');
    }
    
    public function phaseSnags($project_id)
    {
        return view('website.phase-snags');
    }
    
    public function phaseTimeline($project_id)
    {
        // Get phases with their progress for the project (based on approved status)
        $phases = ProjectPhase::with(['milestones', 'tasks', 'inspections', 'snags'])
            ->where('project_id', $project_id)
            ->where('is_active', true)
            ->where('is_deleted', false)
            ->orderBy('id')
            ->get();
            
        // Add calculated progress for each phase (based on approved status - same as API)
        $phases->each(function ($phase) {
            $phase->time_progress = $phase->time_progress;
            $phase->has_extensions = $phase->has_extensions;
            $phase->total_extension_days = $phase->total_extension_days;
        });
            
        return view('website.phase-timeline', compact('phases'));
    }

    public function chat($project_id)
    {
        $project = (object) ['id' => $project_id, 'name' => 'Sample Project'];
        return view('website.project-chat', compact('project'));
    }
}
