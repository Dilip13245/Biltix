<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Project;
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
        $dashboardAccess = $user ? $user->getDashboardAccess() : 'full';
        
        // Role-based stats
        if ($dashboardAccess === 'assigned_only') {
            // Site Engineer - only assigned projects
            $stats = [
                'assigned_projects' => 3,
                'pending_tasks' => 5,
                'inspections_due' => 2,
                'logs_submitted' => 8,
            ];
        } elseif ($dashboardAccess === 'view_only') {
            // Stakeholder - view-only stats
            $stats = [
                'total_projects' => 12,
                'active_projects' => 8,
                'completed_projects' => 4,
                'overall_progress' => 65,
            ];
        } else {
            // Full access - Consultant, Contractor, Project Manager
            $stats = [
                'active_projects' => 12,
                'pending_reviews' => 8,
                'inspections_due' => 5,
                'completed_this_month' => 3,
            ];
        }

        // Role-based project data
        $ongoing_projects = $this->getProjectsByRole($dashboardAccess);

        return view('website.dashboard', compact('stats', 'ongoing_projects', 'dashboardAccess'));
    }
    
    private function getProjectsByRole($dashboardAccess)
    {
        $allProjects = [
            [
                'name' => 'Downtown Office Complex',
                'type' => 'Commercial Building',
                'progress' => 68,
                'due_date' => 'Dec 15, 2024',
                'status' => 'Active',
                'team_count' => 7,
                'role_access' => 'full'
            ],
            [
                'name' => 'Residential Tower A',
                'type' => 'Residential Complex',
                'progress' => 45,
                'due_date' => 'Mar 20, 2025',
                'status' => 'Active',
                'team_count' => 5,
                'role_access' => 'assigned'
            ],
            [
                'name' => 'Shopping Mall Renovation',
                'type' => 'Renovation Project',
                'progress' => 100,
                'due_date' => 'Nov 30, 2024',
                'status' => 'Completed',
                'team_count' => 4,
                'role_access' => 'full'
            ],
            [
                'name' => 'Industrial Warehouse',
                'type' => 'Industrial Building',
                'progress' => 32,
                'due_date' => 'Jun 10, 2025',
                'status' => 'Active',
                'team_count' => 6,
                'role_access' => 'assigned'
            ],
            [
                'name' => 'Hospital Extension',
                'type' => 'Healthcare Facility',
                'progress' => 78,
                'due_date' => 'Jun 10, 2025',
                'status' => 'Active',
                'team_count' => 6,
                'role_access' => 'full'
            ]
        ];
        
        if ($dashboardAccess === 'assigned_only') {
            // Site Engineer - only assigned projects
            return array_filter($allProjects, function($project) {
                return $project['role_access'] === 'assigned';
            });
        }
        
        return $allProjects;
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
    public function phaseInspections()
    {
        return view('website.phase-inspections');
    }
    
    public function phaseTasks()
    {
        return view('website.phase-tasks');
    }
    
    public function phaseSnags()
    {
        return view('website.phase-snags');
    }
    
    public function phaseTimeline()
    {
        return view('website.phase-timeline');
    }
}
