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
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\PaymentController;
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

    Route::prefix('auth')->middleware('throttle:6,1')->group(function () {
        Route::post('signup', [AuthController::class, 'signup']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('send_otp', [AuthController::class, 'sendOtp']);
        Route::post('reset_password', [AuthController::class, 'resetPassword']);
        Route::post('verify_otp', [AuthController::class, 'verifyOtp']);
        Route::post('validate_signup_step', [AuthController::class, 'validateSignupStep']);
    });

    Route::prefix('general')->group(function () {
        Route::get('project_types', [GeneralController::class, 'projectTypes']);
        Route::get('user_roles', [GeneralController::class, 'userRoles']);
        Route::get('static_content', [GeneralController::class, 'getStaticContent'])->middleware('throttle:60,1');
        Route::post('static_content', [GeneralController::class, 'getStaticContent'])->middleware('throttle:60,1');
        Route::post('help_support', [GeneralController::class, 'submitHelpSupport'])->middleware(['tokencheck', 'throttle:5,1']);
        Route::post('change_language', [GeneralController::class, 'changeLanguage'])->middleware('tokencheck');
    });
    
    Route::prefix('roles')->group(function () {
        Route::post('permissions', [RoleController::class, 'getRolePermissions']);
        Route::post('user_permissions', [RoleController::class, 'getUserPermissions']);
    });
    
    // Subscription Plans (Public - to show during registration)
    Route::prefix('subscriptions')->group(function () {
        Route::get('plans', [SubscriptionController::class, 'getPlans']);
        Route::post('plans', [SubscriptionController::class, 'getPlans']);
    });
    
    // Payment (Public - for subscription purchase)
    Route::prefix('payment')->group(function () {
        Route::get('config', [PaymentController::class, 'getConfig']);
        Route::post('init', [PaymentController::class, 'initPayment']);
        Route::get('callback', [PaymentController::class, 'callback'])->name('api.payment.callback');
        Route::post('verify', [PaymentController::class, 'verifyPayment']);
        
        // Registration with payment (Payment First flow)
        Route::post('init_registration', [PaymentController::class, 'initRegistrationPayment']);
        Route::post('complete_registration', [PaymentController::class, 'completeRegistration']);
        
        // Webhook for async payment notifications (from Moyasar)
        Route::post('webhook', [PaymentController::class, 'webhook'])->name('api.payment.webhook');
        
        // Status check (for frontend polling)
        Route::get('status/{token}', [PaymentController::class, 'checkStatus']);
    });
});

// Protected routes (token required)
Route::prefix('v1')->middleware(['decrypt', 'verifyApiKey', 'language', 'tokencheck', 'auto.permission'])->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('get_user_profile', [AuthController::class, 'getUserProfile']);
        Route::post('update_profile', [AuthController::class, 'updateProfile']);
        Route::post('register_device', [AuthController::class, 'registerDevice']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('delete_account', [AuthController::class, 'deleteAccount']);
    });
    
    // Subscription Management (Protected)
    Route::prefix('subscriptions')->group(function () {
        Route::post('current', [SubscriptionController::class, 'getCurrentSubscription']);
        Route::post('features', [SubscriptionController::class, 'getSubscriptionFeatures']);
        Route::post('check_feature', [SubscriptionController::class, 'checkFeatureAccess']);
        Route::post('check_expiry', [SubscriptionController::class, 'checkExpiry']);
    });
    
    // Team Management Routes (with permission check)
    Route::prefix('team_management')->group(function () {
        Route::post('add_member', [AuthController::class, 'addTeamMember']);
        Route::post('list_members', [AuthController::class, 'listTeamMembers']);
    });

    Route::prefix('projects')->group(function () {
        Route::post('create', [ProjectController::class, 'create']);
        Route::post('list', [ProjectController::class, 'list']);
        Route::post('details', [ProjectController::class, 'details']);
        Route::post('update', [ProjectController::class, 'update']);
        Route::post('delete', [ProjectController::class, 'delete']);
        Route::post('dashboard_stats', [ProjectController::class, 'dashboardStats']);
        Route::post('progress_report', [ProjectController::class, 'progressReport']);
        Route::post('create_phase', [ProjectController::class, 'createPhase']);
        Route::post('list_phases', [ProjectController::class, 'listPhases']);
        Route::post('update_phase', [ProjectController::class, 'updatePhase']);
        Route::post('delete_phase', [ProjectController::class, 'deletePhase']);
        Route::post('timeline', [ProjectController::class, 'timeline']);
        Route::post('update_phase_progress', [ProjectController::class, 'updatePhaseProgress']);
        Route::post('extend_milestone', [ProjectController::class, 'updateMilestoneDueDate']);
    });

    Route::prefix('tasks')->group(function () {
        Route::post('create', [TaskController::class, 'create']);
        Route::post('list', [TaskController::class, 'list']);
        Route::post('details', [TaskController::class, 'details']);
        Route::post('update', [TaskController::class, 'update']);
        Route::post('delete', [TaskController::class, 'delete']);
        Route::post('change_status', [TaskController::class, 'changeStatus']);
        Route::post('approve', [TaskController::class, 'approve']);
        Route::post('add_comment', [TaskController::class, 'addComment']);
        Route::post('get_comments', [TaskController::class, 'getComments']);
        Route::post('update_progress', [TaskController::class, 'updateProgress']);
        Route::post('assign_bulk', [TaskController::class, 'assignBulk']);
        Route::post('upload_attachment', [TaskController::class, 'uploadAttachment']);
        Route::get('library', [TaskController::class, 'getTaskLibrary']);
    });

    Route::prefix('inspections')->group(function () {
        Route::post('create', [InspectionController::class, 'create']);
        Route::post('list', [InspectionController::class, 'list']);
        Route::post('details', [InspectionController::class, 'details']);
        Route::post('update', [InspectionController::class, 'update']);
        Route::post('templates', [InspectionController::class, 'templates']);
        Route::post('phases', [InspectionController::class, 'phases']);
        Route::post('start_inspection', [InspectionController::class, 'startInspection']);
        Route::post('save_checklist_item', [InspectionController::class, 'saveChecklistItem']);
        Route::post('complete', [InspectionController::class, 'complete']);
        Route::post('approve', [InspectionController::class, 'approve']);
        Route::post('results', [InspectionController::class, 'results']);
        Route::post('generate_report', [InspectionController::class, 'generateReport']);
    });

    Route::prefix('snags')->group(function () {
        Route::post('create', [SnagController::class, 'create']);
        Route::post('list', [SnagController::class, 'list']);
        Route::post('details', [SnagController::class, 'details']);
        Route::post('update', [SnagController::class, 'update']);
        Route::post('resolve', [SnagController::class, 'resolve']);
        Route::post('approve', [SnagController::class, 'approve']);
        Route::post('assign', [SnagController::class, 'assign']);
        Route::post('add_comment', [SnagController::class, 'addComment']);
        Route::post('categories', [SnagController::class, 'categories']);
    });

    Route::prefix('plans')->group(function () {
        Route::post('upload', [PlanController::class, 'upload']);
        Route::post('list', [PlanController::class, 'list']);
        Route::post('details', [PlanController::class, 'details']);
        Route::post('delete', [PlanController::class, 'delete']);
        Route::post('replace', [PlanController::class, 'replace']);
        Route::post('add_markup', [PlanController::class, 'addMarkup']);
        Route::post('get_markups', [PlanController::class, 'getMarkups']);
        Route::post('approve', [PlanController::class, 'approve']);
    });

    Route::prefix('daily_logs')->group(function () {
        Route::post('create', [DailyLogController::class, 'create']);
        // Route::post('list', [DailyLogController::class, 'list']);
        Route::post('details', [DailyLogController::class, 'details']);
        // Route::post('update', [DailyLogController::class, 'update']);
        Route::post('stats', [DailyLogController::class, 'stats']);
        Route::post('equipment_logs', [DailyLogController::class, 'equipmentLogs']);
        Route::post('staff_logs', [DailyLogController::class, 'staffLogs']);
        
        // Role-wise descriptions APIs
        Route::post('add', [DailyLogController::class, 'addRoleDescription']);
        Route::post('update', [DailyLogController::class, 'updateRoleDescription']);
        Route::post('list', [DailyLogController::class, 'listRoleDescriptions']);
    });

    Route::prefix('team')->group(function () {
        Route::post('list_members', [TeamController::class, 'listMembers']);
        Route::post('add_member', [TeamController::class, 'addMember']);
        Route::post('remove_member', [TeamController::class, 'removeMember']);
        Route::post('assign_project', [TeamController::class, 'assignProject']);
        Route::post('member_details', [TeamController::class, 'memberDetails']);
        Route::post('update_role', [TeamController::class, 'updateRole']);
    });

    Route::prefix('files')->group(function () {
        Route::post('upload', [FileController::class, 'upload']);
        Route::post('list', [FileController::class, 'list']);
        Route::post('delete', [FileController::class, 'delete']);
        Route::post('replace', [FileController::class, 'replace']);
        Route::post('download', [FileController::class, 'download']);
        Route::post('share', [FileController::class, 'share']);
        Route::post('search', [FileController::class, 'search']);
        Route::post('categories', [FileController::class, 'categories']);
        Route::post('create_folder', [FileController::class, 'createFolder']);
        Route::post('get_folders', [FileController::class, 'getFolders']);
        Route::post('delete_folder', [FileController::class, 'deleteFolder']);
    });

    Route::prefix('photos')->group(function () {
        Route::post('upload', [PhotoController::class, 'upload']);
        Route::post('list', [PhotoController::class, 'list']);
        Route::post('delete', [PhotoController::class, 'delete']);
        Route::post('add_tags', [PhotoController::class, 'addTags']);
        Route::post('gallery', [PhotoController::class, 'gallery']);
    });

    Route::prefix('notifications')->group(function () {
        Route::post('list', [NotificationController::class, 'list']);
        Route::post('mark_read', [NotificationController::class, 'markRead']);
        Route::post('mark_all_read', [NotificationController::class, 'markAllRead']);
        Route::post('delete', [NotificationController::class, 'delete']);
        Route::post('delete_all', [NotificationController::class, 'deleteAll']);
        Route::post('get_count', [NotificationController::class, 'getCount']);
        Route::post('settings', [NotificationController::class, 'settings']);
    });
    
    Route::prefix('users')->group(function () {
        Route::get('project-managers', [\App\Http\Controllers\Api\UserController::class, 'getProjectManagers']);
        Route::get('technical-engineers', [\App\Http\Controllers\Api\UserController::class, 'getTechnicalEngineers']);
        Route::get('by-role', [\App\Http\Controllers\Api\UserController::class, 'getUsersByRole']);
        Route::post('get_project_team_members', [\App\Http\Controllers\Api\UserController::class, 'getProjectTeamMembers']);
    });
    
    Route::prefix('project-progress')->group(function () {
        Route::post('list_activities', [\App\Http\Controllers\Api\ProjectProgressController::class, 'listActivities']);
        Route::post('add_activity', [\App\Http\Controllers\Api\ProjectProgressController::class, 'addActivity']);
        Route::post('update_activity', [\App\Http\Controllers\Api\ProjectProgressController::class, 'updateActivity']);
        Route::post('delete_activity', [\App\Http\Controllers\Api\ProjectProgressController::class, 'deleteActivity']);
        Route::post('list_manpower_equipment', [\App\Http\Controllers\Api\ProjectProgressController::class, 'listManpowerEquipment']);
        Route::post('add_manpower_equipment', [\App\Http\Controllers\Api\ProjectProgressController::class, 'addManpowerEquipment']);
        Route::post('update_manpower_equipment', [\App\Http\Controllers\Api\ProjectProgressController::class, 'updateManpowerEquipment']);
        Route::post('delete_manpower_equipment', [\App\Http\Controllers\Api\ProjectProgressController::class, 'deleteManpowerEquipment']);
        Route::post('list_safety_items', [\App\Http\Controllers\Api\ProjectProgressController::class, 'listSafetyItems']);
        Route::post('add_safety_item', [\App\Http\Controllers\Api\ProjectProgressController::class, 'addSafetyItem']);
        Route::post('update_safety_item', [\App\Http\Controllers\Api\ProjectProgressController::class, 'updateSafetyItem']);
        Route::post('delete_safety_item', [\App\Http\Controllers\Api\ProjectProgressController::class, 'deleteSafetyItem']);
        Route::post('list_quality_work', [\App\Http\Controllers\Api\ProjectProgressController::class, 'listQualityWork']);
        Route::post('add_quality_work', [\App\Http\Controllers\Api\ProjectProgressController::class, 'addQualityWork']);
        Route::post('update_quality_work', [\App\Http\Controllers\Api\ProjectProgressController::class, 'updateQualityWork']);
        Route::post('delete_quality_work', [\App\Http\Controllers\Api\ProjectProgressController::class, 'deleteQualityWork']);
        Route::post('list_material_adequacy', [\App\Http\Controllers\Api\ProjectProgressController::class, 'listMaterialAdequacy']);
        Route::post('add_material_adequacy', [\App\Http\Controllers\Api\ProjectProgressController::class, 'addMaterialAdequacy']);
        Route::post('update_material_adequacy', [\App\Http\Controllers\Api\ProjectProgressController::class, 'updateMaterialAdequacy']);
        Route::post('delete_material_adequacy', [\App\Http\Controllers\Api\ProjectProgressController::class, 'deleteMaterialAdequacy']);
    });
    
    Route::prefix('raw-materials')->group(function () {
        Route::post('create', [\App\Http\Controllers\Api\RawMaterialController::class, 'create']);
        Route::post('list', [\App\Http\Controllers\Api\RawMaterialController::class, 'list']);
        Route::post('details', [\App\Http\Controllers\Api\RawMaterialController::class, 'details']);
        Route::post('approve', [\App\Http\Controllers\Api\RawMaterialController::class, 'approve']);
        Route::post('reject', [\App\Http\Controllers\Api\RawMaterialController::class, 'reject']);
    });

    Route::prefix('meetings')->group(function () {
        Route::post('create', [\App\Http\Controllers\Api\MeetingController::class, 'store']);
        Route::post('list', [\App\Http\Controllers\Api\MeetingController::class, 'index']);
        Route::get('details/{id}', [\App\Http\Controllers\Api\MeetingController::class, 'show']);
        Route::post('update/{id}', [\App\Http\Controllers\Api\MeetingController::class, 'update']);
        Route::post('delete/{id}', [\App\Http\Controllers\Api\MeetingController::class, 'destroy']);
    });

    Route::prefix('reports')->group(function () {
        Route::post('generate', [\App\Http\Controllers\Api\ReportController::class, 'generate']);
        Route::post('save', [\App\Http\Controllers\Api\ReportController::class, 'save']);
        Route::post('history', [\App\Http\Controllers\Api\ReportController::class, 'history']);
        Route::post('share', [\App\Http\Controllers\Api\ReportController::class, 'share']);
    });
});