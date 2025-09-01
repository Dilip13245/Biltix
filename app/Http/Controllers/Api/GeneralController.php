<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class GeneralController extends Controller
{
    public function projectTypes()
    {
        try {
            $types = [
                ['id' => 'residential', 'name' => 'Residential'],
                ['id' => 'commercial', 'name' => 'Commercial'],
                ['id' => 'industrial', 'name' => 'Industrial'],
                ['id' => 'renovation', 'name' => 'Renovation'],
            ];

            return $this->toJsonEnc($types, 'Project types retrieved successfully', Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function userRoles()
    {
        try {
            $roles = [
                ['id' => 'contractor', 'name' => 'Contractor'],
                ['id' => 'consultant', 'name' => 'Consultant'],
                ['id' => 'site_engineer', 'name' => 'Site Engineer'],
                ['id' => 'project_manager', 'name' => 'Project Manager'],
                ['id' => 'stakeholder', 'name' => 'Stakeholder'],
            ];

            return $this->toJsonEnc($roles, 'User roles retrieved successfully', Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function getStaticContent(Request $request)
    {
        try {
            $type = $request->input('type', 'terms');
            
            $content = [
                'terms' => 'Terms and Conditions content...',
                'privacy' => 'Privacy Policy content...',
                'about' => 'About Us content...',
            ];

            $data = [
                'type' => $type,
                'content' => $content[$type] ?? 'Content not found'
            ];

            return $this->toJsonEnc($data, 'Static content retrieved successfully', Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function submitHelpSupport(Request $request)
    {
        try {
            // This would typically save to database
            $data = [
                'user_id' => $request->input('user_id'),
                'subject' => $request->input('subject'),
                'message' => $request->input('message'),
                'status' => 'submitted'
            ];

            return $this->toJsonEnc($data, 'Help support request submitted successfully', Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function changeLanguage(Request $request)
    {
        try {
            $language = $request->input('language', 'en');
            
            // This would typically update user's language preference in database
            $data = [
                'language' => $language,
                'message' => trans('api.general.language_changed')
            ];

            return $this->toJsonEnc($data, trans('api.general.language_changed'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }
}