<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IncreaseUploadLimits
{
    public function handle(Request $request, Closure $next)
    {
        // Increase limits for file upload requests
        if ($request->hasFile('photos') || 
            $request->hasFile('construction_plans') || 
            $request->hasFile('gantt_chart') ||
            $request->hasFile('images') ||
            $request->hasFile('attachments')) {
            
            @ini_set('max_execution_time', '300');
            @ini_set('max_input_time', '300');
            @ini_set('memory_limit', '256M');
            @ini_set('post_max_size', '50M');
            @ini_set('upload_max_filesize', '50M');
        }

        return $next($request);
    }
}
