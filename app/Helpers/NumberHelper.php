<?php

namespace App\Helpers;

use App\Models\Project;
use App\Models\Task;
use App\Models\Snag;
use App\Models\Inspection;

class NumberHelper
{
    public static function generateProjectCode()
    {
        $year = date('Y');
        $lastProject = Project::where('project_code', 'like', "PRJ-{$year}-%")
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastProject) {
            $lastNumber = (int) substr($lastProject->project_code, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return "PRJ-{$year}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
    
    public static function generateTaskNumber($projectId)
    {
        $project = Project::find($projectId);
        $projectCode = $project ? substr($project->project_code, -4) : '0000';
        
        $lastTask = Task::where('project_id', $projectId)
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastTask) {
            $lastNumber = (int) substr($lastTask->task_number, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return "TSK-{$projectCode}-" . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
    
    public static function generateSnagNumber($projectId)
    {
        $project = Project::find($projectId);
        $projectCode = $project ? substr($project->project_code, -4) : '0000';
        
        $lastSnag = Snag::where('project_id', $projectId)
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastSnag) {
            $lastNumber = (int) substr($lastSnag->snag_number, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return "SNG-{$projectCode}-" . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
    
    public static function generateInspectionNumber($projectId)
    {
        $project = Project::find($projectId);
        $projectCode = $project ? substr($project->project_code, -4) : '0000';
        
        $lastInspection = Inspection::where('project_id', $projectId)
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastInspection) {
            $lastNumber = (int) substr($lastInspection->inspection_number, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return "INS-{$projectCode}-" . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}