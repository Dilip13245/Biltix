<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\ApiController;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root route
Route::get('/', function () {
    return redirect('/login');
})->name('home');

// Language switching
Route::get('/lang/{locale}', function ($locale, Request $request) {
    if (in_array($locale, config('app.supported_locales', ['en']))) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

/*
|--------------------------------------------------------------------------
| Authentication Routes (Public)
|--------------------------------------------------------------------------
*/
Route::middleware('web.guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\Website\AuthController::class, 'showLogin'])->name('login');
    Route::get('/register', [App\Http\Controllers\Website\AuthController::class, 'showRegister'])->name('register');
    Route::get('/forgot-password', [App\Http\Controllers\Website\AuthController::class, 'showForgotPassword'])->name('forgot-password');
    
    // Auth API endpoints - With Rate Limiting
    Route::middleware('throttle:3,1')->post('/auth/register', [App\Http\Controllers\Website\AuthController::class, 'register'])->name('auth.register');
    Route::middleware('throttle:5,1')->post('/auth/send-otp', [App\Http\Controllers\Website\AuthController::class, 'sendOtp'])->name('auth.send-otp');
    Route::middleware('throttle:5,1')->post('/auth/verify-otp', [App\Http\Controllers\Website\AuthController::class, 'verifyOtp'])->name('auth.verify-otp');
    Route::middleware('throttle:3,1')->post('/auth/reset-password', [App\Http\Controllers\Website\AuthController::class, 'resetPassword'])->name('auth.reset-password');
});

// Session management routes (accessible to all)
Route::post('/auth/set-session', [App\Http\Controllers\Website\AuthController::class, 'setSession'])->name('auth.set-session');
Route::get('/auth/check-session', [App\Http\Controllers\Website\AuthController::class, 'checkSession'])->name('auth.check-session');

/*
|--------------------------------------------------------------------------
| Protected Website Routes (Require Authentication)
|--------------------------------------------------------------------------
*/
Route::middleware('web.auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', function () {
        return view('website.profile');
    })->name('website.profile');
    

    // Logout route
    Route::post('/auth/logout', [App\Http\Controllers\Website\AuthController::class, 'logout'])->name('logout');
    Route::post('/api/auth/verify-session', [App\Http\Controllers\Website\AuthController::class, 'verifySession'])->name('auth.verify-session');
});

/*
|--------------------------------------------------------------------------
| Website Project Routes (Protected with Permissions)
|--------------------------------------------------------------------------
*/
Route::middleware('web.auth')->prefix('website')->group(function () {
    // Dashboard route removed - using main dashboard route instead
    
    // Phase-specific pages (require view permissions)
    
    // General pages
    Route::middleware('web.permission:inspections,view')->get('/safety-checklist', [HomeController::class, 'safetyChecklist'])->name('website.safety-checklist');
    
    // Project-specific routes with permissions
    Route::prefix('project/{project_id}')->group(function () {
        Route::middleware('web.permission:plans,view')->get('/plans', [HomeController::class, 'plans'])->name('website.project.plans');
        Route::middleware('web.permission:tasks,view')->get('/tasks', [HomeController::class, 'tasks'])->name('website.project.tasks');
        Route::middleware('web.permission:inspections,view')->get('/inspections', [HomeController::class, 'inspections'])->name('website.project.inspections');
        Route::middleware('web.permission:daily_logs,view')->get('/daily-logs', [HomeController::class, 'dailyLogs'])->name('website.project.daily-logs');
        Route::middleware('web.permission:progress,view')->get('/project-progress', [HomeController::class, 'projectProgress'])->name('website.project.progress');
        Route::middleware('web.permission:files,view')->get('/project-files', [HomeController::class, 'projectFiles'])->name('website.project.files');
        Route::middleware('web.permission:files,view')->get('/project-gallery', [HomeController::class, 'projectGallery'])->name('website.project.gallery');
        Route::middleware('web.permission:team,view')->get('/team-members', [HomeController::class, 'teamMembers'])->name('website.project.team-members');
        Route::middleware('web.permission:snags,view')->get('/snag-list', [HomeController::class, 'snagList'])->name('website.project.snag-list');
        Route::middleware('web.permission:inspections,view')->get('/safety-checklist', [HomeController::class, 'safetyChecklist'])->name('website.project.safety-checklist');
        Route::middleware('web.permission:inspections,view')->get('/phase-inspections', [HomeController::class, 'phaseInspections'])->name('website.phase.inspections');
        Route::middleware('web.permission:tasks,view')->get('/phase-tasks', [HomeController::class, 'phaseTasks'])->name('website.phase.tasks');
        Route::middleware('web.permission:snags,view')->get('/phase-snags', [HomeController::class, 'phaseSnags'])->name('website.phase.snags');
        Route::middleware('web.permission:progress,view')->get('/phase-timeline', [HomeController::class, 'phaseTimeline'])->name('website.phase.timeline');
        Route::get('/notifications', [HomeController::class, 'notifications'])->name('website.project.notifications');
        Route::get('/help-support', [HomeController::class, 'helpSupport'])->name('website.project.help-support');
    });
});

/*
|--------------------------------------------------------------------------
| API Routes for Website (AJAX calls with API responses)
|--------------------------------------------------------------------------
*/
Route::prefix('api')->group(function () {
    // Public API routes (Authentication) - With Rate Limiting
    Route::middleware('throttle:5,1')->post('/login', [ApiController::class, 'login'])->name('api.login');
    Route::middleware('throttle:3,1')->post('/signup', [ApiController::class, 'signup'])->name('api.signup');
    
    // Protected API routes
    Route::middleware('web.api.auth')->group(function () {
        Route::post('/logout', [ApiController::class, 'logout'])->name('api.logout');
        Route::get('/profile', [ApiController::class, 'getUserProfile'])->name('api.profile');
        
        // Projects
        Route::get('/projects', [ApiController::class, 'getProjects'])->name('api.projects.index');
        Route::post('/projects', [ApiController::class, 'createProject'])->name('api.projects.create');
        Route::get('/projects/{id}', [ApiController::class, 'getProjectDetails'])->name('api.projects.show');
        
        // Tasks
        Route::get('/tasks', [ApiController::class, 'getTasks'])->name('api.tasks.index');
        Route::post('/tasks', [ApiController::class, 'createTask'])->name('api.tasks.create');
        Route::put('/tasks/{id}/status', [ApiController::class, 'updateTaskStatus'])->name('api.tasks.status');
    });
});



/*
|--------------------------------------------------------------------------
| Utility Routes
|--------------------------------------------------------------------------
*/
Route::get('/enc-dec', 'App\Http\Controllers\Controller@encryptIndex')->name('encryptpage');
Route::post('/enc-dec', 'App\Http\Controllers\Controller@changeEncDecData')->name('web.enc-dec-data');
Route::get('/test-lang', function () {
    return view('test-lang');
})->name('test.lang');

Route::get('/role-tester', function () {
    return view('website.role-tester');
})->name('role.tester');