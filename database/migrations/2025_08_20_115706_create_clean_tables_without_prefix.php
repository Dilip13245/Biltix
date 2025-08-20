<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Users Table (Complete)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('password');
            $table->enum('role', ['contractor', 'consultant', 'site_engineer', 'project_manager', 'stakeholder']);
            $table->string('company_name');
            $table->string('designation')->nullable();
            $table->integer('employee_count')->nullable();
            $table->string('member_number')->nullable();
            $table->string('member_name')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('language', 2)->default('en');
            $table->string('timezone')->nullable();
            $table->string('otp', 6)->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 2. User Devices Table
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('token')->nullable();
            $table->enum('device_type', ['A', 'I']); // Android, iOS
            $table->string('ip_address')->nullable();
            $table->string('uuid')->nullable();
            $table->string('os_version')->nullable();
            $table->string('device_model')->nullable();
            $table->string('app_version')->default('v1');
            $table->string('device_token')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 3. Projects Table
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['residential', 'commercial', 'industrial', 'renovation']);
            $table->enum('status', ['planning', 'active', 'on_hold', 'completed', 'cancelled'])->default('planning');
            $table->string('location');
            $table->text('address')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('budget', 15, 2)->default(0);
            $table->decimal('actual_cost', 15, 2)->default(0);
            $table->string('client_name')->nullable();
            $table->string('client_email')->nullable();
            $table->string('client_phone')->nullable();
            $table->text('client_address')->nullable();
            $table->bigInteger('project_manager_id');
            $table->bigInteger('created_by');
            $table->integer('progress_percentage')->default(0);
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 4. Project Phases Table
        Schema::create('project_phases', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('project_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('phase_order')->default(1);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['pending', 'active', 'completed', 'on_hold'])->default('pending');
            $table->decimal('budget_allocated', 15, 2)->default(0);
            $table->decimal('actual_cost', 15, 2)->default(0);
            $table->integer('progress_percentage')->default(0);
            $table->bigInteger('created_by');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 5. Tasks Table
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('project_id');
            $table->bigInteger('phase_id')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->bigInteger('assigned_to');
            $table->bigInteger('created_by');
            $table->date('start_date')->nullable();
            $table->date('due_date');
            $table->timestamp('completed_at')->nullable();
            $table->integer('progress_percentage')->default(0);
            $table->string('location')->nullable();
            $table->json('attachments')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 6. Task Comments Table
        Schema::create('task_comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('task_id');
            $table->bigInteger('user_id');
            $table->text('comment');
            $table->json('attachments')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 7. Inspection Templates Table
        Schema::create('inspection_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['safety', 'quality', 'structural', 'electrical', 'plumbing']);
            $table->json('checklist_items');
            $table->bigInteger('created_by');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 8. Inspections Table
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('project_id');
            $table->bigInteger('phase_id')->nullable();
            $table->bigInteger('template_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'failed'])->default('scheduled');
            $table->date('scheduled_date');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->bigInteger('inspector_id');
            $table->string('location')->nullable();
            $table->enum('overall_result', ['pass', 'fail', 'conditional_pass'])->nullable();
            $table->decimal('score_percentage', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->json('images')->nullable();
            $table->bigInteger('created_by');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 9. Inspection Results Table
        Schema::create('inspection_results', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('inspection_id');
            $table->string('item_name');
            $table->enum('result', ['pass', 'fail', 'na']);
            $table->text('notes')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 10. Snag Categories Table
        Schema::create('snag_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color_code', 7)->default('#ff0000');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 11. Snags Table
        Schema::create('snags', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('project_id');
            $table->bigInteger('category_id');
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['open', 'assigned', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->bigInteger('reported_by');
            $table->bigInteger('assigned_to')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->bigInteger('resolved_by')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->json('images_before')->nullable();
            $table->json('images_after')->nullable();
            $table->decimal('cost_impact', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 12. Snag Comments Table
        Schema::create('snag_comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('snag_id');
            $table->bigInteger('user_id');
            $table->text('comment');
            $table->string('status_changed_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 13. Plans Table
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('project_id');
            $table->string('title');
            $table->enum('plan_type', ['architectural', 'structural', 'electrical', 'plumbing']);
            $table->string('file_name');
            $table->string('file_path');
            $table->integer('file_size');
            $table->string('file_type');
            $table->string('version')->default('1.0');
            $table->enum('status', ['draft', 'approved'])->default('draft');
            $table->string('thumbnail_path')->nullable();
            $table->bigInteger('uploaded_by');
            $table->bigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 14. Plan Markups Table
        Schema::create('plan_markups', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('plan_id');
            $table->bigInteger('user_id');
            $table->enum('markup_type', ['inspection', 'snag', 'task', 'general']);
            $table->json('markup_data');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'resolved'])->default('active');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 15. Files Table
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('project_id');
            $table->bigInteger('category_id');
            $table->string('name');
            $table->string('original_name');
            $table->string('file_path');
            $table->integer('file_size');
            $table->string('file_type');
            $table->bigInteger('uploaded_by');
            $table->boolean('is_public')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 16. File Categories Table
        Schema::create('file_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 17. Photos Table
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('project_id');
            $table->bigInteger('phase_id')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('thumbnail_path')->nullable();
            $table->integer('file_size');
            $table->timestamp('taken_at');
            $table->bigInteger('taken_by');
            $table->string('location')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 18. Daily Logs Table
        Schema::create('daily_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('project_id');
            $table->date('log_date');
            $table->bigInteger('logged_by');
            $table->string('weather_conditions')->nullable();
            $table->text('work_performed');
            $table->text('issues_encountered')->nullable();
            $table->text('notes')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 19. Equipment Logs Table
        Schema::create('equipment_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('daily_log_id');
            $table->string('equipment_id');
            $table->string('equipment_type');
            $table->string('operator_name')->nullable();
            $table->enum('status', ['active', 'maintenance', 'idle'])->default('active');
            $table->decimal('hours_used', 4, 2);
            $table->string('location')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 20. Staff Logs Table
        Schema::create('staff_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('daily_log_id');
            $table->string('staff_type');
            $table->integer('count');
            $table->decimal('hours_worked', 4, 2);
            $table->text('tasks_performed')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 21. Team Members Table
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('project_id');
            $table->string('role_in_project');
            $table->timestamp('assigned_at');
            $table->bigInteger('assigned_by');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 22. Notifications Table
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->enum('type', ['task_assigned', 'inspection_due', 'snag_reported', 'project_update']);
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('staff_logs');
        Schema::dropIfExists('equipment_logs');
        Schema::dropIfExists('daily_logs');
        Schema::dropIfExists('photos');
        Schema::dropIfExists('file_categories');
        Schema::dropIfExists('files');
        Schema::dropIfExists('plan_markups');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('snag_comments');
        Schema::dropIfExists('snags');
        Schema::dropIfExists('snag_categories');
        Schema::dropIfExists('inspection_results');
        Schema::dropIfExists('inspections');
        Schema::dropIfExists('inspection_templates');
        Schema::dropIfExists('task_comments');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('project_phases');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('user_devices');
        Schema::dropIfExists('users');
    }
};