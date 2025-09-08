<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\RoleHelper;

class WebPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $module, string $action): Response
    {
        $user = $request->attributes->get('user');
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Authentication required');
        }

        if (!RoleHelper::hasPermission($user, $module, $action)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'code' => 403,
                    'message' => 'Access denied. Insufficient permissions.',
                    'data' => new \stdClass()
                ], 403);
            }
            
            return redirect()->route('dashboard')->with('error', 'Access denied. You do not have permission to perform this action.');
        }

        return $next($request);
    }
}