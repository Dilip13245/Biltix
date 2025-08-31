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
            $table->string('member_number')->nullable();
            $table->string('member_name')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('language', 2)->default('en');
            $table->string('timezone')->nullable();
            $table->string('otp', 6)->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // 2. Admins Table
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('profile_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // 3. User Devices Table
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('token')->nullable();
            $table->enum('device_type', ['A', 'I']); // A = Android, I = iOS
            $table->string('ip_address')->nullable();
            $table->string('uuid')->nullable();
            $table->string('os_version')->nullable();
            $table->string('device_model')->nullable();
            $table->string('app_version')->default('v1');
            $table->string('device_token')->nullable();
            $table->timestamps();
        });

        // 4. Projects Table
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_number')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('project_type', ['residential', 'commercial', 'industrial', 'renovation']);
            $table->enum('status', ['planning', 'active', 'on_hold', 'completed', 'cancelled'])->default('planning');
            $table->string('location');
            $table->text('address')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('budget', 15, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->decimal('actual_cost', 15, 2)->default(0);
            $table->string('client_name')->nullable();
            $table->string('client_email')->nullable();
            $table->string('client_phone')->nullable();
            $table->text('client_address')->nullable();
            $table->foreignId('project_manager_id')->nullable();
            $table->foreignId('created_by');
            $table->integer('progress_percentage')->default(0);
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // 5. Project Phases Table
        Schema::create('project_phases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('phase_order')->default(1);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['pending', 'active', 'completed', 'on_hold'])->default('pending');
            $table->decimal('budget_allocated', 15, 2)->default(0);
            $table->decimal('actual_cost', 15, 2)->default(0);
            $table->integer('progress_percentage')->default(0);
            $table->foreignId('created_by');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // 6. Tasks Table
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_number')->unique();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('phase_id')->nullable()->constrained('project_phases')->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->foreignId('assigned_to')->nullable();
            $table->foreignId('created_by');
            $table->date('start_date')->nullable();
            $table->date('due_date');
            $table->integer('estimated_hours')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('progress_percentage')->default(0);
            $table->string('location')->nullable();
            $table->json('attachments')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // 7. Task Comments Table
        Schema::create('task_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('comment');
            $table->json('attachments')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // 8. Inspection Templates Table
        Schema::create('inspection_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('category', ['safety', 'quality', 'structural', 'electrical', 'plumbing']);
            $table->json('checklist_items');
            $table->foreignId('created_by');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // 9. Inspections Table
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->string('inspection_number')->unique();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('phase_id')->nullable()->constrained('project_phases')->onDelete('set null');
            $table->foreignId('template_id')->nullable()->constrained('inspection_templates')->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('inspection_type', ['quality', 'safety', 'progress', 'final'])->default('quality');
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'failed', 'cancelled'])->default('scheduled');
            $table->date('scheduled_date');
            $table->time('scheduled_time')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('inspector_id');
            $table->string('location')->nullable();
            $table->enum('overall_result', ['pass', 'fail', 'conditional_pass'])->nullable();
            $table->decimal('score_percentage', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->json('images')->nullable();
            $table->foreignId('created_by');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // 10. Inspection Results Table
        Schema::create('inspection_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained()->onDelete('cascade');
            $table->string('item_name');
            $table->enum('result', ['pass', 'fail', 'na'])->default('pass');
            $table->text('notes')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // 11. Snag Categories Table
        Schema::create('snag_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('color_code', 7)->default('#ff0000');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // 12. Snags Table
        Schema::create('snags', function (Blueprint $table) {
            $table->id();
            $table->string('snag_number')->unique();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('snag_categories');
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('severity', ['minor', 'major', 'critical'])->default('minor');
            $table->enum('status', ['open', 'assigned', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->foreignId('reported_by');
            $table->foreignId('assigned_to')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->json('images_before')->nullable();
            $table->json('images_after')->nullable();
            $table->decimal('cost_impact', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // 13. Snag Comments Table
        Schema::create('snag_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('snag_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('comment');
            $table->string('status_changed_to')->nullable();
            $table->json('attachments')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // 14. Plans Table
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
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
            $table->foreignId('uploaded_by');
            $table->foreignId('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // 15. Plan Markups Table
        Schema::create('plan_markups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('markup_type', ['inspection', 'snag', 'task', 'general', 'note']);
            $table->json('markup_data');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'resolved', 'archived'])->default('active');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // 16. File Categories Table
        Schema::create('file_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // 17. Files Table
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('file_categories');
            $table->string('name');
            $table->string('original_name');
            $table->string('file_path');
            $table->integer('file_size');
            $table->string('file_type');
            $table->text('description')->nullable();
            $table->foreignId('uploaded_by');
            $table->boolean('is_public')->default(false);
            $table->json('shared_with')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // 18. Photos Table
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('phase_id')->nullable()->constrained('project_phases')->onDelete('set null');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('thumbnail_path')->nullable();
            $table->integer('file_size');
            $table->timestamp('taken_at');
            $table->foreignId('taken_by');
            $table->string('location')->nullable();
            $table->json('tags')->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // 19. Daily Logs Table
        Schema::create('daily_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->date('log_date');
            $table->foreignId('logged_by');
            $table->string('weather_conditions')->nullable();
            $table->string('temperature')->nullable();
            $table->text('work_performed');
            $table->text('issues_encountered')->nullable();
            $table->text('materials_used')->nullable();
            $table->text('notes')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // 20. Equipment Logs Table
        Schema::create('equipment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_log_id')->constrained()->onDelete('cascade');
            $table->string('equipment_id');
            $table->string('equipment_name');
            $table->string('equipment_type');
            $table->string('operator_name')->nullable();
            $table->enum('status', ['active', 'maintenance', 'idle', 'breakdown'])->default('active');
            $table->decimal('hours_used', 4, 2);
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // 21. Staff Logs Table
        Schema::create('staff_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_log_id')->constrained()->onDelete('cascade');
            $table->string('staff_type');
            $table->integer('count');
            $table->decimal('hours_worked', 4, 2);
            $table->text('tasks_performed')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // 22. Team Members Table
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('role_in_project');
            $table->text('responsibilities')->nullable();
            $table->timestamp('assigned_at');
            $table->foreignId('assigned_by');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // 23. Notifications Table
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['task_assigned', 'inspection_due', 'snag_reported', 'project_update', 'system']);
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // Laravel Default Tables - Skip if exist
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
        Schema::dropIfExists('inspections');
        Schema::dropIfExists('inspection_templates');
        Schema::dropIfExists('task_comments');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('project_phases');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('user_devices');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('users');
    }
};