<?php

namespace App\Services\Api;

use App\Services\ApiService;

class ProjectApiService extends ApiService
{
    public function create($data)
    {
        return $this->post('v1/projects/create', $data);
    }

    public function list($data = [])
    {
        return $this->post('v1/projects/list', $data);
    }

    public function details($data)
    {
        return $this->post('v1/projects/details', $data);
    }

    public function update($data)
    {
        return $this->post('v1/projects/update', $data);
    }

    public function deleteProject($data)
    {
        return $this->post('v1/projects/delete', $data);
    }

    public function dashboardStats($data = [])
    {
        return $this->post('v1/projects/dashboard_stats', $data);
    }

    public function progressReport($data)
    {
        return $this->post('v1/projects/progress_report', $data);
    }

    public function createPhase($data)
    {
        return $this->post('v1/projects/create_phase', $data);
    }

    public function listPhases($data)
    {
        return $this->post('v1/projects/list_phases', $data);
    }

    public function updatePhase($data)
    {
        return $this->post('v1/projects/update_phase', $data);
    }

    public function deletePhase($data)
    {
        return $this->post('v1/projects/delete_phase', $data);
    }

    public function timeline($data)
    {
        return $this->post('v1/projects/timeline', $data);
    }
}