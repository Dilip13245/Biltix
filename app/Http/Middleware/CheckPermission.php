<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use App\Helpers\EncryptDecrypt;

class CheckPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $module, string $action): Response
    {
        try {
            $user_id = $request->input('user_id');
            
            if (!$user_id) {
                return $this->unauthorizedResponse('User ID required');
            }

            $user = User::find($user_id);
            
            if (!$user || !$user->is_active) {
                return $this->unauthorizedResponse('User not found or inactive');
            }

            // Get user's role permissions
            $permissions = $this->getUserPermissions($user->role, $module);
            
            // Check if user has required permission
            if (!in_array($action, $permissions)) {
                return $this->unauthorizedResponse('Access denied. Insufficient permissions.');
            }
            
            return $next($request);
        } catch (\Exception $e) {
            return $this->unauthorizedResponse('Permission check failed: ' . $e->getMessage());
        }
    }
    
    private function getUserPermissions(string $role, string $module): array
    {
        $rolePermissions = [
            'contractor' => [
                'projects' => ['create', 'edit', 'delete', 'view'],
                'tasks' => ['create', 'update', 'complete', 'comment', 'assign', 'delete', 'view'],
                'inspections' => ['create', 'conduct', 'complete', 'approve', 'view'],
                'snags' => ['create', 'update', 'resolve', 'assign', 'review', 'approve', 'view'],
                'plans' => ['upload', 'markup', 'approve', 'view'],
                'team' => ['add', 'remove', 'coordinate', 'view'],
                'daily_logs' => ['create', 'edit', 'view'],
                'files' => ['upload', 'delete', 'view'],
                'photos' => ['upload', 'delete', 'view'],
                'reports' => ['generate', 'view']
            ],
            'site_engineer' => [
                'projects' => ['view'],
                'tasks' => ['update', 'complete', 'view'],
                'inspections' => ['conduct', 'complete', 'view'],
                'snags' => ['create', 'update', 'view'],
                'plans' => ['view', 'markup'],
                'team' => ['view'],
                'daily_logs' => ['create', 'edit', 'view'],
                'files' => ['upload', 'view'],
                'photos' => ['upload', 'view'],
                'reports' => ['view']
            ],
            'consultant' => [
                'projects' => ['view', 'comment'],
                'tasks' => ['view', 'comment'],
                'inspections' => ['create', 'approve', 'view'],
                'snags' => ['review', 'approve', 'view'],
                'plans' => ['markup', 'approve', 'view'],
                'team' => ['view'],
                'daily_logs' => ['view'],
                'files' => ['view'],
                'photos' => ['view'],
                'reports' => ['view']
            ],
            'project_manager' => [
                'projects' => ['view', 'edit'],
                'tasks' => ['assign', 'track', 'view'],
                'inspections' => ['schedule', 'review', 'view'],
                'snags' => ['assign', 'track', 'view'],
                'plans' => ['view', 'approve'],
                'team' => ['view', 'coordinate'],
                'daily_logs' => ['view'],
                'files' => ['view'],
                'photos' => ['view'],
                'reports' => ['generate', 'view']
            ],
            'stakeholder' => [
                'projects' => ['view'],
                'tasks' => ['view'],
                'inspections' => ['view'],
                'snags' => ['view'],
                'plans' => ['view'],
                'team' => ['view'],
                'daily_logs' => ['view'],
                'files' => ['view'],
                'photos' => ['view'],
                'reports' => ['view']
            ]
        ];
        
        return $rolePermissions[$role][$module] ?? [];
    }

    private function unauthorizedResponse(string $message): Response
    {
        if (Config::get('constant.ENCRYPTION_ENABLED') == 1) {
            return response()->json(EncryptDecrypt::bodyEncrypt(json_encode([
                'code' => 403,
                'message' => $message,
                'data' => new \stdClass(),
            ])), 403);
        } else {
            return response()->json([
                'code' => 403,
                'message' => $message,
                'data' => new \stdClass(),
            ], 403);
        }
    }
}