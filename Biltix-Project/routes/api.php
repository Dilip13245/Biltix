<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\LocalizationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    // Localization Routes (Public)
    Route::get('locales', [LocalizationController::class, 'getLocales']);
    Route::get('translations/{namespace?}', [LocalizationController::class, 'getTranslations']);
    Route::get('translations-all', [LocalizationController::class, 'getAllTranslations']);
    Route::get('routes', [LocalizationController::class, 'getLocalizedRoutes']);
    Route::post('lang/switch/{locale}', [LocalizationController::class, 'switchLanguage']);
    
    // Auth Routes
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    
    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('profile', [AuthController::class, 'profile']);
        Route::post('profile', [AuthController::class, 'updateProfile']);
        Route::post('logout', [AuthController::class, 'logout']);
        
        // Project Routes
        Route::get('dashboard', [ProjectController::class, 'dashboard']);
        Route::get('projects', [ProjectController::class, 'index']);
        Route::get('projects/{id}', [ProjectController::class, 'show']);
    });
});
