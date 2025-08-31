<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Users Table
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
            $table->string('profile_image')->nullable();
            $table->string('language', 2)->default('en');
            $table->string('timezone')->nullable();
            $table->string('otp', 6)->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 2. User Members Table
        Schema::create('user_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('member_name');
            $table->string('member_phone');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Admins Table
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('profile_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 4. User Devices Table
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('token')->nullable();
            $table->enum('device_type', ['A', 'I']);
            $table->string('ip_address')->nullable();
            $table->string('uuid')->nullable();
            $table->string('os_version')->nullable();
            $table->string('device_model')->nullable();
            $table->string('app_version')->default('v1');
            $table->string('device_token')->nullable();
            $table->timestamps();
        });

        // 5. Projects Table (Updated with Figma fields)
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_code')->unique();
            $table->string('project_title');
            $table->string('contractor_name');
            $table->unsignedBigInteger('project_manager_id')->nullable();
            $table->unsignedBigInteger('technical_engineer_id')->nullable();
            $table->string('type');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->string('project_location');
            $table->date('project_start_date')->nullable();
            $table->date('project_due_date')->nullable();
            $table->enum('status', ['planning', 'active', 'on_hold', 'completed', 'cancelled'])->default('planning');
            $table->unsignedBigInteger('created_by');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 6. Project Phases Table (Simplified)
        Schema::create('project_phases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('title');
            $table->unsignedBigInteger('created_by');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 7. Phase Milestones Table
        Schema::create('phase_milestones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('phase_id');
            $table->string('milestone_name');
            $table->integer('days');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 8. Tasks Table
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_number')->unique();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('phase_id')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->date('start_date')->nullable();
            $table->date('due_date');
            $table->integer('estimated_hours')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('progress_percentage')->default(0);
            $table->string('location')->nullable();
            $table->json('attachments')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 9. Task Comments Table
        Schema::create('task_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('user_id');
            $table->text('comment');
            $table->json('attachments')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 10. Inspection Templates Table
        Schema::create('inspection_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('category', ['safety', 'quality', 'structural', 'electrical', 'plumbing']);
            $table->json('checklist_items');
            $table->unsignedBigInteger('created_by');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 11. Inspections Table (Simplified)
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('category');
            $table->text('description')->nullable();
            $table->text('comment')->nullable();
            $table->enum('status', ['open', 'in_progress', 'completed', 'failed'])->default('open');
            $table->unsignedBigInteger('inspected_by')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 12. Inspection Checklists Table
        Schema::create('inspection_checklists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inspection_id');
            $table->string('checklist_item');
            $table->boolean('is_checked')->default(false);
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('checked_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 13. Inspection Images Table
        Schema::create('inspection_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inspection_id');
            $table->string('image_path');
            $table->string('original_name');
            $table->string('file_size')->nullable();
            $table->unsignedBigInteger('uploaded_by');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 14. Inspection Results Table
        Schema::create('inspection_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inspection_id');
            $table->string('item_name');
            $table->enum('result', ['pass', 'fail', 'na'])->default('pass');
            $table->text('notes')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 15. Snag Categories Table
        Schema::create('snag_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('color_code', 7)->default('#ff0000');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 16. Snags Table
        Schema::create('snags', function (Blueprint $table) {
            $table->id();
            $table->string('snag_number')->unique();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('category_id');
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('severity', ['minor', 'major', 'critical'])->default('minor');
            $table->enum('status', ['open', 'assigned', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->unsignedBigInteger('reported_by');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->unsignedBigInteger('resolved_by')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->json('images_before')->nullable();
            $table->json('images_after')->nullable();
            $table->decimal('cost_impact', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 17. Snag Comments Table
        Schema::create('snag_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('snag_id');
            $table->unsignedBigInteger('user_id');
            $table->text('comment');
            $table->string('status_changed_to')->nullable();
            $table->json('attachments')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 18. Plans Table
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('title');
            $table->string('drawing_number')->nullable();
            $table->enum('plan_type', ['architectural', 'structural', 'electrical', 'plumbing', 'mechanical']);
            $table->string('file_name');
            $table->string('file_path');
            $table->integer('file_size');
            $table->string('file_type');
            $table->string('version')->default('1.0');
            $table->enum('status', ['draft', 'approved', 'rejected'])->default('draft');
            $table->string('thumbnail_path')->nullable();
            $table->unsignedBigInteger('uploaded_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 19. Plan Markups Table
        Schema::create('plan_markups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('markup_type', ['inspection', 'snag', 'task', 'general', 'note']);
            $table->json('markup_data');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'resolved', 'archived'])->default('active');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 20. File Categories Table
        Schema::create('file_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 21. Files Table
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->string('original_name');
            $table->string('file_path');
            $table->integer('file_size');
            $table->string('file_type');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('uploaded_by');
            $table->boolean('is_public')->default(false);
            $table->json('shared_with')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 22. Photos Table
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('phase_id')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('thumbnail_path')->nullable();
            $table->integer('file_size');
            $table->timestamp('taken_at');
            $table->unsignedBigInteger('taken_by');
            $table->string('location')->nullable();
            $table->json('tags')->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 23. Daily Logs Table
        Schema::create('daily_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->date('log_date');
            $table->unsignedBigInteger('logged_by');
            $table->string('weather_conditions')->nullable();
            $table->string('temperature')->nullable();
            $table->text('work_performed');
            $table->text('issues_encountered')->nullable();
            $table->text('materials_used')->nullable();
            $table->text('notes')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 24. Equipment Logs Table
        Schema::create('equipment_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daily_log_id');
            $table->string('equipment_id');
            $table->string('equipment_name');
            $table->string('equipment_type');
            $table->string('operator_name')->nullable();
            $table->enum('status', ['active', 'maintenance', 'idle', 'breakdown'])->default('active');
            $table->decimal('hours_used', 4, 2);
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 25. Staff Logs Table
        Schema::create('staff_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daily_log_id');
            $table->string('staff_type');
            $table->integer('count');
            $table->decimal('hours_worked', 4, 2);
            $table->text('tasks_performed')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 26. Team Members Table
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('project_id');
            $table->string('role_in_project');
            $table->text('responsibilities')->nullable();
            $table->timestamp('assigned_at');
            $table->unsignedBigInteger('assigned_by');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // 27. Notifications Table
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('type', ['task_assigned', 'inspection_due', 'snag_reported', 'project_update', 'system']);
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

        // Laravel Default Tables
        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        if (!Schema::hasTable('failed_jobs')) {
            Schema::create('failed_jobs', function (Blueprint $table) {
                $table->id();
                $table->string('uuid')->unique();
                $table->text('connection');
                $table->text('queue');
                $table->longText('payload');
                $table->longText('exception');
                $table->timestamp('failed_at')->useCurrent();
            });
        }

        if (!Schema::hasTable('personal_access_tokens')) {
            Schema::create('personal_access_tokens', function (Blueprint $table) {
                $table->id();
                $table->morphs('tokenable');
                $table->string('name');
                $table->string('token', 64)->unique();
                $table->text('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('staff_logs');
        Schema::dropIfExists('equipment_logs');
        Schema::dropIfExists('daily_logs');
        Schema::dropIfExists('photos');
        Schema::dropIfExists('files');
        Schema::dropIfExists('file_categories');
        Schema::dropIfExists('plan_markups');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('snag_comments');
        Schema::dropIfExists('snags');
        Schema::dropIfExists('snag_categories');
        Schema::dropIfExists('inspection_results');
        Schema::dropIfExists('inspection_images');
        Schema::dropIfExists('inspection_checklists');
        Schema::dropIfExists('inspections');
        Schema::dropIfExists('inspection_templates');
        Schema::dropIfExists('task_comments');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('phase_milestones');
        Schema::dropIfExists('project_phases');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('user_devices');
        Schema::dropIfExists('user_members');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('users');
    }
};