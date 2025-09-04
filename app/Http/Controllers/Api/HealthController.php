<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class HealthController extends BaseApiController
{
    /**
     * Health check endpoint
     */
    public function health(Request $request)
    {
        try {
            $health = [
                'status' => 'healthy',
                'timestamp' => now()->toISOString(),
                'version' => $this->getApiVersion($request),
                'environment' => config('app.env'),
                'services' => $this->checkServices(),
            ];
            
            return $this->successResponse($health, 'System is healthy');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Health check failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Detailed system status
     */
    public function status(Request $request)
    {
        try {
            $status = [
                'application' => [
                    'name' => config('app.name'),
                    'version' => $this->getApiVersion($request),
                    'environment' => config('app.env'),
                    'debug' => config('app.debug'),
                    'timezone' => config('app.timezone'),
                ],
                'database' => $this->checkDatabase(),
                'cache' => $this->checkCache(),
                'storage' => $this->checkStorage(),
                'memory' => $this->getMemoryUsage(),
                'uptime' => $this->getUptime(),
            ];
            
            return $this->successResponse($status, 'System status retrieved');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Status check failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Check various services
     */
    private function checkServices(): array
    {
        return [
            'database' => $this->checkDatabase()['status'],
            'cache' => $this->checkCache()['status'],
            'storage' => $this->checkStorage()['status'],
        ];
    }
    
    /**
     * Check database connection
     */
    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();
            $queryTime = $this->measureQueryTime(function () {
                DB::select('SELECT 1');
            });
            
            return [
                'status' => 'healthy',
                'connection' => 'connected',
                'query_time_ms' => $queryTime,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'connection' => 'disconnected',
                'error' => $e->getMessage(),
            ];
        }
    }
    
    /**
     * Check cache system
     */
    private function checkCache(): array
    {
        try {
            $key = 'health_check_' . time();
            $value = 'test_value';
            
            Cache::put($key, $value, 60);
            $retrieved = Cache::get($key);
            Cache::forget($key);
            
            if ($retrieved === $value) {
                return [
                    'status' => 'healthy',
                    'driver' => config('cache.default'),
                ];
            } else {
                return [
                    'status' => 'unhealthy',
                    'driver' => config('cache.default'),
                    'error' => 'Cache read/write test failed',
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'driver' => config('cache.default'),
                'error' => $e->getMessage(),
            ];
        }
    }
    
    /**
     * Check storage system
     */
    private function checkStorage(): array
    {
        try {
            $testFile = 'health_check_' . time() . '.txt';
            $testContent = 'health check test';
            
            Storage::put($testFile, $testContent);
            $retrieved = Storage::get($testFile);
            Storage::delete($testFile);
            
            if ($retrieved === $testContent) {
                return [
                    'status' => 'healthy',
                    'driver' => config('filesystems.default'),
                ];
            } else {
                return [
                    'status' => 'unhealthy',
                    'driver' => config('filesystems.default'),
                    'error' => 'Storage read/write test failed',
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'driver' => config('filesystems.default'),
                'error' => $e->getMessage(),
            ];
        }
    }
    
    /**
     * Get memory usage
     */
    private function getMemoryUsage(): array
    {
        $memoryUsage = memory_get_usage(true);
        $memoryPeak = memory_get_peak_usage(true);
        
        return [
            'current_mb' => round($memoryUsage / 1024 / 1024, 2),
            'peak_mb' => round($memoryPeak / 1024 / 1024, 2),
            'limit_mb' => ini_get('memory_limit'),
        ];
    }
    
    /**
     * Get system uptime
     */
    private function getUptime(): array
    {
        return [
            'server_uptime' => $this->getServerUptime(),
            'application_start' => defined('LARAVEL_START') ? date('Y-m-d H:i:s', LARAVEL_START) : 'unknown',
        ];
    }
    
    /**
     * Get server uptime (if available)
     */
    private function getServerUptime(): string
    {
        if (function_exists('sys_getloadavg')) {
            $uptime = shell_exec('uptime');
            return $uptime ? trim($uptime) : 'unknown';
        }
        
        return 'unknown';
    }
    
    /**
     * Measure query execution time
     */
    private function measureQueryTime(callable $callback): float
    {
        $start = microtime(true);
        $callback();
        return round((microtime(true) - $start) * 1000, 2);
    }
}
