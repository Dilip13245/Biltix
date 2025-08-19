<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Static data for dashboard
        $stats = [
            'total_projects' => 25,
            'active_projects' => 18,
            'completed_projects' => 7,
            'total_users' => 156,
            'total_tasks' => 342,
            'pending_tasks' => 89
        ];

        $recent_projects = [
            ['id' => 1, 'name' => 'Luxury Villa Construction', 'status' => 'active', 'progress' => 75],
            ['id' => 2, 'name' => 'Office Complex Phase 2', 'status' => 'active', 'progress' => 45],
            ['id' => 3, 'name' => 'Residential Tower', 'status' => 'completed', 'progress' => 100],
        ];

        return view('admin.dashboard', compact('stats', 'recent_projects'));
    }
}