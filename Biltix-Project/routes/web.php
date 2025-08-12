<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\LocalizationController;

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

// Language Routes
Route::group(['prefix' => 'lang'], function () {
    Route::get('/switch/{locale}', [LocalizationController::class, 'switchLanguage'])->name('lang.switch');
    Route::get('/detect', [LocalizationController::class, 'detectLanguage'])->name('lang.detect');
});

// Home Route (with language detection)
Route::get('/', function () {
    return view('welcome');
})->name('home');

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
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.post');
    Route::get('logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
    
    Route::middleware('admin.auth')->group(function () {
        Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // Profile Routes
        Route::get('profile', [App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('profile.show');
        Route::get('profile/edit', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    });
});
