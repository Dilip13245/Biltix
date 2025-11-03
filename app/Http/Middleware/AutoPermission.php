<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\DynamicRoleHelper;

class AutoPermission
{
    public function handle(Request $request, Closure $next)
    {
        $user_id = $request->input('user_id');
        
        if (!$user_id) {
            return response()->json(['code' => 403, 'message' => 'User ID required'], 403);
        }

        // Auto-detect module and action from route
        [$module, $action] = $this->detectPermission($request);
        
        if (!$module || !$action) {
            return $next($request); // Skip if can't detect
        }

        if (!DynamicRoleHelper::hasPermission($user_id, $module, $action)) {
            return response()->json([
                'code' => 403, 
                'message' => "Access denied. Need {$module}.{$action} permission",
                'required_permission' => "{$module}.{$action}"
            ], 403);
        }

        return $next($request);
    }

    private function detectPermission(Request $request): array
    {
        $path = $request->path();
        
        // Extract module from URL path
        if (preg_match('/api\/v1\/(\w+)\/(\w+)/', $path, $matches)) {
            $module = $matches[1]; // projects, tasks, etc.
            $endpoint = $matches[2]; // create, list, etc.
            
            // Override module for phase operations
            if (in_array($endpoint, ['create_phase', 'list_phases', 'update_phase', 'delete_phase', 'update_phase_progress'])) {
                $module = 'phases';
            }
            
            // Map endpoint to action
            $action = $this->mapToAction($endpoint);
            
            return [$module, $action];
        }
        
        return [null, null];
    }

    private function mapToAction(string $endpoint): ?string
    {
        $mapping = [
            'create' => 'create',
            'list' => 'view',
            'details' => 'view',
            'update' => 'edit',
            'delete' => 'delete',
            'upload' => 'upload',
            'approve' => 'approve',
            'assign' => 'assign',
            'resolve' => 'resolve',
            'complete' => 'complete',
            'start_inspection' => 'conduct',
            'add_comment' => 'comment',
            'add_markup' => 'markup',
            'get_user_profile' => 'view',
            'update_profile' => 'edit',
            'logout' => 'view',
            'delete_account' => 'delete',
            'dashboard_stats' => 'view',
            'progress_report' => 'view',
            'create_phase' => 'create',
            'list_phases' => 'view',
            'update_phase' => 'edit',
            'delete_phase' => 'delete',
            'timeline' => 'view',
            'change_status' => 'edit',
            'get_comments' => 'view',
            'update_progress' => 'edit',
            'assign_bulk' => 'assign',
            'upload_attachment' => 'upload',
            'templates' => 'view',
            'save_checklist_item' => 'edit',
            'results' => 'view',
            'generate_report' => 'view',
            'categories' => 'view',
            'replace' => 'edit',
            'get_markups' => 'view',
            'stats' => 'view',
            'equipment_logs' => 'view',
            'staff_logs' => 'view',
            'list_members' => 'view',
            'add_member' => 'create',
            'remove_member' => 'delete',
            'assign_project' => 'assign',
            'member_details' => 'view',
            'update_role' => 'edit',
            'download' => 'download',
            'share' => 'view',
            'search' => 'view',
            'add_tags' => 'edit',
            'gallery' => 'view',
            'mark_read' => 'edit',
            'mark_all_read' => 'edit',
            'delete_all' => 'delete',
            'get_count' => 'view',
            'settings' => 'view',
        ];

        return $mapping[$endpoint] ?? 'view';
    }
}