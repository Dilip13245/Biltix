<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Models\HelpSupport;
use App\Models\StaticContent;

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

            return $this->toJsonEnc($types, trans('api.general.project_types_retrieved'), Config::get('constant.SUCCESS'));
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

            return $this->toJsonEnc($roles, trans('api.general.user_roles_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function getStaticContent(Request $request)
    {
        try {
            $type = $request->input('type', 'terms');
            $language = $request->header('Accept-Language', 'en');
            $language = in_array($language, ['en', 'ar']) ? $language : 'en';
            
            $validator = Validator::make(['type' => $type], [
                'type' => 'required|in:privacy,terms',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $staticContent = StaticContent::byType($type)->byLanguage($language)->active()->first();

            if (!$staticContent) {
                return $this->toJsonEnc([], trans('api.general.content_not_found'), Config::get('constant.NOT_FOUND'));
            }

            $data = [
                'type' => $staticContent->type,
                'language' => $staticContent->language,
                'title' => $staticContent->title,
                'content' => $staticContent->content
            ];

            return $this->toJsonEnc($data, trans('api.general.static_content_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function submitHelpSupport(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'nullable|integer',
                'full_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'message' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $helpSupport = HelpSupport::create([
                'user_id' => $request->user_id,
                'full_name' => $request->full_name,
                'email' => $request->email,
                'message' => $request->message,
                'status' => 'pending',
                'is_active' => true,
                'is_deleted' => false
            ]);

            return $this->toJsonEnc([
                'id' => $helpSupport->id,
                'status' => $helpSupport->status
            ], trans('api.general.help_support_submitted'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function changeLanguage(Request $request)
    {
        try {
            $language = $request->input('language', 'en');
            
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
