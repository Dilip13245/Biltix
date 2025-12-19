<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Project;

class ReportsController extends Controller
{
    public function index($project_id)
    {
        $project = Project::findOrFail($project_id);

        // Static data for report history
        $reportHistory = [
            [
                'id' => 1,
                'name' => 'Report - 1',
                'generated_by' => 'Shyam Solanki',
                'date' => '05/12/2025',
            ],
            [
                'id' => 2,
                'name' => 'Report - 2',
                'generated_by' => 'Shyam Solanki',
                'date' => '05/12/2025',
            ],
        ];

        return view('website.project_reports', compact('reportHistory', 'project', 'project_id'));
    }
}
