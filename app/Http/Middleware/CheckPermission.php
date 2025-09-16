<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use App\Helpers\EncryptDecrypt;
use App\Helpers\DynamicRoleHelper;

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

            // Check if user has required permission using database
            if (!DynamicRoleHelper::hasPermission($user, $module, $action)) {
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
                'tasks' => ['update', 'view'], // Limited: update status only
                'inspections' => ['create', 'conduct', 'complete', 'approve', 'view'],
                'snags' => ['create', 'update', 'resolve', 'assign', 'review', 'approve', 'view'],
                'plans' => ['upload', 'markup', 'approve', 'view', 'delete'],
                'team' => ['add', 'remove', 'coordinate', 'view'],
                'daily_logs' => ['create', 'edit', 'view'],
                'files' => ['upload', 'delete', 'view'],
                'photos' => ['upload', 'delete', 'view'],
                'reports' => ['generate', 'view'],
                'notifications' => ['view', 'update', 'delete']
            ],
            'site_engineer' => [
                'projects' => [], // No Access
                'tasks' => ['view'], // View-only assigned tasks
                'inspections' => ['create', 'view'], // Limited: submit only
                'snags' => ['create', 'view'], // Limited: submit snags
                'plans' => ['markup', 'view'], // Limited: annotate only
                'team' => ['view'],
                'daily_logs' => ['create', 'edit', 'view'], // Full: submit logs
                'files' => ['upload', 'view'], // Limited: upload logs/snags only
                'photos' => ['upload', 'view'], // Limited: upload logs/snags only
                'reports' => ['create', 'view'],
                'notifications' => ['view', 'update', 'delete']
            ],
            'consultant' => [
                'projects' => [], // No Access
                'tasks' => ['view'], // View-only
                'inspections' => ['conduct', 'view'], // Limited: assist/add observations
                'snags' => ['review', 'resolve', 'view'], // Limited: review/mark resolved
                'plans' => ['markup', 'view'], // Limited: markup only
                'team' => ['view'],
                'daily_logs' => ['comment', 'view'], // Limited: comment only
                'files' => ['view'], // View-only
                'photos' => ['view'], // View-only
                'reports' => ['view'],
                'notifications' => ['view', 'update', 'delete']
            ],
            'project_manager' => [
                'projects' => ['create', 'view', 'edit', 'delete'],
                'tasks' => ['create', 'assign', 'track', 'complete', 'update', 'view'],
                'inspections' => ['create', 'conduct', 'complete', 'approve', 'view'],
                'snags' => ['create', 'assign', 'track', 'resolve', 'view'],
                'plans' => ['upload', 'view', 'approve', 'markup', 'delete'],
                'team' => ['add', 'remove', 'view', 'coordinate'],
                'daily_logs' => ['create', 'edit', 'view'],
                'files' => ['upload', 'delete', 'view'],
                'photos' => ['upload', 'view'],
                'reports' => ['create', 'generate', 'view'],
                'notifications' => ['view', 'update', 'delete']
            ],
            'stakeholder' => [
                'projects' => [], // No Access
                'tasks' => ['view'], // View-only
                'inspections' => ['view'], // View-only
                'snags' => ['view'], // View-only
                'plans' => ['view'], // View-only
                'team' => ['view'],
                'daily_logs' => ['view'], // View-only
                'files' => ['view'], // View-only
                'photos' => ['view'], // View-only
                'reports' => ['view'],
                'notifications' => ['view', 'update', 'delete']
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