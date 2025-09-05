<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProjectController;
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
    
    // Auth API endpoints
    Route::post('/auth/set-session', [App\Http\Controllers\Website\AuthController::class, 'setSession'])->name('auth.set-session');
    Route::post('/auth/register', [App\Http\Controllers\Website\AuthController::class, 'register'])->name('auth.register');
    Route::post('/auth/send-otp', [App\Http\Controllers\Website\AuthController::class, 'sendOtp'])->name('auth.send-otp');
    Route::post('/auth/verify-otp', [App\Http\Controllers\Website\AuthController::class, 'verifyOtp'])->name('auth.verify-otp');
    Route::post('/auth/reset-password', [App\Http\Controllers\Website\AuthController::class, 'resetPassword'])->name('auth.reset-password');
});

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
| Website Project Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware('web.auth')->prefix('website')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('website.dashboard');
    
    // Phase-specific pages
    Route::get('/phase-inspections', [HomeController::class, 'phaseInspections'])->name('website.phase.inspections');
    Route::get('/phase-tasks', [HomeController::class, 'phaseTasks'])->name('website.phase.tasks');
    Route::get('/phase-snags', [HomeController::class, 'phaseSnags'])->name('website.phase.snags');
    Route::get('/phase-timeline', [HomeController::class, 'phaseTimeline'])->name('website.phase.timeline');
    
    // General pages (not project-specific)
    Route::get('/safety-checklist', [HomeController::class, 'safetyChecklist'])->name('website.safety-checklist');
    
    // Project-specific routes
    Route::prefix('project/{project_id}')->group(function () {
        Route::get('/plans', [HomeController::class, 'plans'])->name('website.project.plans');
        Route::get('/tasks', [HomeController::class, 'tasks'])->name('website.project.tasks');
        Route::get('/inspections', [HomeController::class, 'inspections'])->name('website.project.inspections');
        Route::get('/daily-logs', [HomeController::class, 'dailyLogs'])->name('website.project.daily-logs');
        Route::get('/project-progress', [HomeController::class, 'projectProgress'])->name('website.project.progress');
        Route::get('/project-files', [HomeController::class, 'projectFiles'])->name('website.project.files');
        Route::get('/project-gallery', [HomeController::class, 'projectGallery'])->name('website.project.gallery');
        Route::get('/team-members', [HomeController::class, 'teamMembers'])->name('website.project.team-members');
        Route::get('/snag-list', [HomeController::class, 'snagList'])->name('website.project.snag-list');
        Route::get('/safety-checklist', [HomeController::class, 'safetyChecklist'])->name('website.project.safety-checklist');
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
    // Public API routes (Authentication)
    Route::post('/login', [ApiController::class, 'login'])->name('api.login');
    Route::post('/signup', [ApiController::class, 'signup'])->name('api.signup');
    
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
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.post');
    Route::get('logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
    
    Route::middleware('admin.auth')->group(function () {
        Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('profile', [App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('profile.show');
        Route::get('profile/edit', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
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