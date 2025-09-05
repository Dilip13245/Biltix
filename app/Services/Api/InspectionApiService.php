<?php

namespace App\Services\Api;

use App\Services\ApiService;

class InspectionApiService extends ApiService
{
    public function create($data)
    {
        return $this->post('v1/inspections/create', $data);
    }

    public function list($data = [])
    {
        return $this->post('v1/inspections/list', $data);
    }

    public function details($data)
    {
        return $this->post('v1/inspections/details', $data);
    }

    public function templates($data = [])
    {
        return $this->post('v1/inspections/templates', $data);
    }

    public function startInspection($data)
    {
        return $this->post('v1/inspections/start_inspection', $data);
    }

    public function saveChecklistItem($data)
    {
        return $this->post('v1/inspections/save_checklist_item', $data);
    }

    public function complete($data)
    {
        return $this->post('v1/inspections/complete', $data);
    }

    public function approve($data)
    {
        return $this->post('v1/inspections/approve', $data);
    }

    public function results($data)
    {
        return $this->post('v1/inspections/results', $data);
    }

    public function generateReport($data)
    {
        return $this->post('v1/inspections/generate_report', $data);
    }
}