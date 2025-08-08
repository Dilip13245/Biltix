<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Website\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Website Routes
Route::group(['prefix' => 'website'], function () {
    // Projects listing page
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('website.dashboard');
    
    // Project-specific routes
    Route::group(['prefix' => 'project/{project_id}'], function () {
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
    });
});

// Admin Routes
Route::group(['prefix' => 'admin'], function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'loginData'])->name('admin.login.submit');
    Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('admin.dashboard');
        
        Route::group(['prefix' => 'projects', 'as' => 'admin.projects.'], function () {
            Route::get('/', [ProjectController::class, 'index'])->name('index');
            Route::get('/create', [ProjectController::class, 'create'])->name('create');
            Route::post('/', [ProjectController::class, 'store'])->name('store');
            Route::get('/{id}', [ProjectController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [ProjectController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ProjectController::class, 'update'])->name('update');
            Route::delete('/{id}', [ProjectController::class, 'destroy'])->name('destroy');
        });
    });
});
