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
        ];

        return $mapping[$endpoint] ?? 'view';
    }
}