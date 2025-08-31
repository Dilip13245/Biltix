<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Website\HomeController;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

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

// Root route
Route::get('/', [HomeController::class, 'dashboard'])->name('home');

// Language switching route
Route::get('/lang/{locale}', function ($locale, Request $request) {
    if (in_array($locale, config('app.supported_locales', ['en']))) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

// Test language route
Route::get('/test-lang', function () {
    return view('test-lang');
})->name('test.lang');

// Website Routes
Route::group(['prefix' => 'website'], function () {
    // Projects listing page
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('website.dashboard');
    
    // Phase-specific pages (cloned from original pages)
    Route::get('/phase-inspections', [HomeController::class, 'phaseInspections'])->name('website.phase.inspections');
    Route::get('/phase-tasks', [HomeController::class, 'phaseTasks'])->name('website.phase.tasks');
    Route::get('/phase-snags', [HomeController::class, 'phaseSnags'])->name('website.phase.snags');
    Route::get('/phase-timeline', [HomeController::class, 'phaseTimeline'])->name('website.phase.timeline');
    
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
        Route::get('/help-support', [HomeController::class, 'helpSupport'])->name('website.project.help-support');
    });
});

// Encryption/Decryption Tool Routes
Route::get('/enc-dec', 'App\Http\Controllers\Controller@encryptIndex')->name('encryptpage')->middleware('basic.auth');
Route::post('/enc-dec', 'App\Http\Controllers\Controller@changeEncDecData')->name('web.enc-dec-data');

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
