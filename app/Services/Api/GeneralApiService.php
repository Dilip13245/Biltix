<?php

namespace App\Services\Api;

use App\Services\ApiService;

class GeneralApiService extends ApiService
{
    public function projectTypes()
    {
        return $this->get('v1/general/project_types');
    }

    public function userRoles()
    {
        return $this->get('v1/general/user_roles');
    }

    public function getStaticContent($data)
    {
        return $this->post('v1/general/static_content', $data);
    }

    public function submitHelpSupport($data)
    {
        return $this->post('v1/general/help_support', $data);
    }

    public function changeLanguage($data)
    {
        return $this->post('v1/general/change_language', $data);
    }
}