<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GeneralController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\InspectionController;
use App\Http\Controllers\Api\SnagController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\DailyLogController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\PhotoController;
use App\Http\Controllers\Api\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public routes (no token required)
Route::prefix('v1')->middleware(['decrypt', 'verifyApiKey', 'language'])->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('signup', [AuthController::class, 'signup']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('send_otp', [AuthController::class, 'sendOtp']);
        Route::post('reset_password', [AuthController::class, 'resetPassword']);
        Route::post('verify_otp', [AuthController::class, 'verifyOtp']);
    });

    Route::prefix('general')->group(function () {
        Route::get('project_types', [GeneralController::class, 'projectTypes']);
        Route::get('user_roles', [GeneralController::class, 'userRoles']);
        Route::post('static_content', [GeneralController::class, 'getStaticContent']);
        Route::post('help_support', [GeneralController::class, 'submitHelpSupport'])->middleware('tokencheck');
        Route::post('change_language', [GeneralController::class, 'changeLanguage'])->middleware('tokencheck');
    });
});

// Protected routes (token required)
Route::prefix('v1')->middleware(['decrypt', 'verifyApiKey', 'language', 'tokencheck'])->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('get_user_profile', [AuthController::class, 'getUserProfile']);
        Route::post('update_profile', [AuthController::class, 'updateProfile']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('delete_account', [AuthController::class, 'deleteAccount']);
    });

    Route::prefix('projects')->group(function () {
        Route::post('create', [ProjectController::class, 'create'])->middleware('permission:projects,create');
        Route::post('list', [ProjectController::class, 'list'])->middleware('permission:projects,view');
        Route::post('details', [ProjectController::class, 'details'])->middleware('permission:projects,view');
        Route::post('update', [ProjectController::class, 'update'])->middleware('permission:projects,edit');
        Route::post('delete', [ProjectController::class, 'delete'])->middleware('permission:projects,delete');
        Route::post('dashboard_stats', [ProjectController::class, 'dashboardStats'])->middleware('permission:projects,view');
        Route::post('progress_report', [ProjectController::class, 'progressReport'])->middleware('permission:reports,view');
        Route::post('create_phase', [ProjectController::class, 'createPhase'])->middleware('permission:projects,create');
        Route::post('list_phases', [ProjectController::class, 'listPhases'])->middleware('permission:projects,view');
        Route::post('update_phase', [ProjectController::class, 'updatePhase'])->middleware('permission:projects,edit');
        Route::post('delete_phase', [ProjectController::class, 'deletePhase'])->middleware('permission:projects,delete');
        Route::post('timeline', [ProjectController::class, 'timeline'])->middleware('permission:projects,view');
    });

    Route::prefix('tasks')->group(function () {
        Route::post('create', [TaskController::class, 'create'])->middleware('permission:tasks,create');
        Route::post('list', [TaskController::class, 'list'])->middleware('permission:tasks,view');
        Route::post('details', [TaskController::class, 'details'])->middleware('permission:tasks,view');
        Route::post('update', [TaskController::class, 'update'])->middleware('permission:tasks,update');
        Route::post('delete', [TaskController::class, 'delete'])->middleware('permission:tasks,delete');
        Route::post('change_status', [TaskController::class, 'changeStatus'])->middleware('permission:tasks,complete');
        Route::post('add_comment', [TaskController::class, 'addComment'])->middleware('permission:tasks,comment');
        Route::post('get_comments', [TaskController::class, 'getComments'])->middleware('permission:tasks,view');
        Route::post('update_progress', [TaskController::class, 'updateProgress'])->middleware('permission:tasks,update');
        Route::post('assign_bulk', [TaskController::class, 'assignBulk'])->middleware('permission:tasks,assign');
        Route::post('upload_attachment', [TaskController::class, 'uploadAttachment'])->middleware('permission:tasks,update');
    });

    Route::prefix('inspections')->group(function () {
        Route::post('create', [InspectionController::class, 'create'])->middleware('permission:inspections,create');
        Route::post('list', [InspectionController::class, 'list'])->middleware('permission:inspections,view');
        Route::post('details', [InspectionController::class, 'details'])->middleware('permission:inspections,view');
        Route::post('templates', [InspectionController::class, 'templates'])->middleware('permission:inspections,view');
        Route::post('start_inspection', [InspectionController::class, 'startInspection'])->middleware('permission:inspections,conduct');
        Route::post('save_checklist_item', [InspectionController::class, 'saveChecklistItem'])->middleware('permission:inspections,conduct');
        Route::post('complete', [InspectionController::class, 'complete'])->middleware('permission:inspections,complete');
        Route::post('approve', [InspectionController::class, 'approve'])->middleware('permission:inspections,approve');
        Route::post('results', [InspectionController::class, 'results'])->middleware('permission:inspections,view');
        Route::post('generate_report', [InspectionController::class, 'generateReport'])->middleware('permission:reports,view');
    });

    Route::prefix('snags')->group(function () {
        Route::post('create', [SnagController::class, 'create'])->middleware('permission:snags,create');
        Route::post('list', [SnagController::class, 'list'])->middleware('permission:snags,view');
        Route::post('details', [SnagController::class, 'details'])->middleware('permission:snags,view');
        Route::post('update', [SnagController::class, 'update'])->middleware('permission:snags,update');
        Route::post('resolve', [SnagController::class, 'resolve'])->middleware('permission:snags,resolve');
        Route::post('assign', [SnagController::class, 'assign'])->middleware('permission:snags,assign');
        Route::post('add_comment', [SnagController::class, 'addComment'])->middleware('permission:snags,review');
        Route::post('categories', [SnagController::class, 'categories'])->middleware('permission:snags,view');
    });

    Route::prefix('plans')->group(function () {
        Route::post('upload', [PlanController::class, 'upload'])->middleware('permission:plans,upload');
        Route::post('list', [PlanController::class, 'list'])->middleware('permission:plans,view');
        Route::post('details', [PlanController::class, 'details'])->middleware('permission:plans,view');
        Route::post('delete', [PlanController::class, 'delete'])->middleware('permission:plans,delete');
        Route::post('add_markup', [PlanController::class, 'addMarkup'])->middleware('permission:plans,markup');
        Route::post('get_markups', [PlanController::class, 'getMarkups'])->middleware('permission:plans,view');
        Route::post('approve', [PlanController::class, 'approve'])->middleware('permission:plans,approve');
    });

    Route::prefix('daily_logs')->group(function () {
        Route::post('create', [DailyLogController::class, 'create'])->middleware('permission:daily_logs,create');
        Route::post('list', [DailyLogController::class, 'list'])->middleware('permission:daily_logs,view');
        Route::post('details', [DailyLogController::class, 'details'])->middleware('permission:daily_logs,view');
        Route::post('update', [DailyLogController::class, 'update'])->middleware('permission:daily_logs,edit');
        Route::post('stats', [DailyLogController::class, 'stats'])->middleware('permission:daily_logs,view');
        Route::post('equipment_logs', [DailyLogController::class, 'equipmentLogs'])->middleware('permission:daily_logs,create');
        Route::post('staff_logs', [DailyLogController::class, 'staffLogs'])->middleware('permission:daily_logs,create');
    });

    Route::prefix('team')->group(function () {
        Route::post('list_members', [TeamController::class, 'listMembers'])->middleware('permission:team,view');
        Route::post('add_member', [TeamController::class, 'addMember'])->middleware('permission:team,add');
        Route::post('remove_member', [TeamController::class, 'removeMember'])->middleware('permission:team,remove');
        Route::post('assign_project', [TeamController::class, 'assignProject'])->middleware('permission:team,coordinate');
        Route::post('member_details', [TeamController::class, 'memberDetails'])->middleware('permission:team,view');
        Route::post('update_role', [TeamController::class, 'updateRole'])->middleware('permission:team,coordinate');
    });

    Route::prefix('files')->group(function () {
        Route::post('upload', [FileController::class, 'upload'])->middleware('permission:files,upload');
        Route::post('list', [FileController::class, 'list'])->middleware('permission:files,view');
        Route::post('delete', [FileController::class, 'delete'])->middleware('permission:files,delete');
        Route::post('download', [FileController::class, 'download'])->middleware('permission:files,view');
        Route::post('share', [FileController::class, 'share'])->middleware('permission:files,upload');
        Route::post('search', [FileController::class, 'search'])->middleware('permission:files,view');
        Route::post('categories', [FileController::class, 'categories'])->middleware('permission:files,view');
    });

    Route::prefix('photos')->group(function () {
        Route::post('upload', [PhotoController::class, 'upload'])->middleware('permission:photos,upload');
        Route::post('list', [PhotoController::class, 'list'])->middleware('permission:photos,view');
        Route::post('delete', [PhotoController::class, 'delete'])->middleware('permission:photos,delete');
        Route::post('add_tags', [PhotoController::class, 'addTags'])->middleware('permission:photos,upload');
        Route::post('gallery', [PhotoController::class, 'gallery'])->middleware('permission:photos,view');
    });

    Route::prefix('notifications')->group(function () {
        Route::post('list', [NotificationController::class, 'list'])->middleware('permission:notifications,view');
        Route::post('mark_read', [NotificationController::class, 'markRead'])->middleware('permission:notifications,update');
        Route::post('mark_all_read', [NotificationController::class, 'markAllRead'])->middleware('permission:notifications,update');
        Route::post('delete', [NotificationController::class, 'delete'])->middleware('permission:notifications,delete');
        Route::post('get_count', [NotificationController::class, 'getCount'])->middleware('permission:notifications,view');
        Route::post('settings', [NotificationController::class, 'settings'])->middleware('permission:notifications,view');
    });
});