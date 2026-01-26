<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\GanttActivity;
use App\Models\Project;
use Illuminate\Http\Request;

class GanttChartController extends Controller
{
    public function index($projectId)
    {
        $project = Project::findOrFail($projectId);
        // Activities now loaded via API
        return view('website.project.gantt.index', compact('project'));
    }
}
