<?php

namespace App\Services\Api;

use App\Services\ApiService;

class SnagApiService extends ApiService
{
    public function create($data)
    {
        return $this->post('v1/snags/create', $data);
    }

    public function list($data = [])
    {
        return $this->post('v1/snags/list', $data);
    }

    public function details($data)
    {
        return $this->post('v1/snags/details', $data);
    }

    public function update($data)
    {
        return $this->post('v1/snags/update', $data);
    }

    public function resolve($data)
    {
        return $this->post('v1/snags/resolve', $data);
    }

    public function assign($data)
    {
        return $this->post('v1/snags/assign', $data);
    }

    public function addComment($data)
    {
        return $this->post('v1/snags/add_comment', $data);
    }

    public function categories($data = [])
    {
        return $this->post('v1/snags/categories', $data);
    }
}