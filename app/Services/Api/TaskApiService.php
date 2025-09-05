<?php

namespace App\Services\Api;

use App\Services\ApiService;

class TaskApiService extends ApiService
{
    public function create($data)
    {
        return $this->post('v1/tasks/create', $data);
    }

    public function list($data = [])
    {
        return $this->post('v1/tasks/list', $data);
    }

    public function details($data)
    {
        return $this->post('v1/tasks/details', $data);
    }

    public function update($data)
    {
        return $this->post('v1/tasks/update', $data);
    }

    public function delete($data)
    {
        return $this->post('v1/tasks/delete', $data);
    }

    public function changeStatus($data)
    {
        return $this->post('v1/tasks/change_status', $data);
    }

    public function addComment($data)
    {
        return $this->post('v1/tasks/add_comment', $data);
    }

    public function getComments($data)
    {
        return $this->post('v1/tasks/get_comments', $data);
    }

    public function updateProgress($data)
    {
        return $this->post('v1/tasks/update_progress', $data);
    }

    public function assignBulk($data)
    {
        return $this->post('v1/tasks/assign_bulk', $data);
    }

    public function uploadAttachment($data)
    {
        return $this->post('v1/tasks/upload_attachment', $data);
    }
}