<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the old enum and add new one with all notification types
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM(
            'task_assigned', 
            'task_unassigned',
            'task_created',
            'task_status_changed', 
            'task_comment', 
            'task_progress_updated',
            'task_due_soon', 
            'task_overdue', 
            'task_mention',
            'task_due_date_changed',
            'inspection_created', 
            'inspection_assigned',
            'inspection_unassigned',
            'inspection_started', 
            'inspection_completed', 
            'inspection_approved', 
            'inspection_due',
            'inspection_status_changed',
            'snag_reported', 
            'snag_assigned', 
            'snag_unassigned',
            'snag_comment', 
            'snag_resolved',
            'snag_status_changed',
            'project_created', 
            'project_updated', 
            'project_status_changed',
            'project_manager_assigned',
            'project_manager_changed',
            'technical_engineer_assigned',
            'technical_engineer_changed',
            'project_archived',
            'project_restored',
            'project_deleted',
            'phase_created', 
            'phase_progress_updated', 
            'milestone_extended',
            'team_member_added', 
            'team_member_removed', 
            'team_role_updated',
            'plan_uploaded', 
            'plan_approved', 
            'plan_markup_added',
            'file_uploaded', 
            'file_shared',
            'file_deleted',
            'daily_log_created',
            'account_created', 
            'password_reset', 
            'otp_sent',
            'system',
            'project_update'
        ) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM(
            'task_assigned', 
            'inspection_due', 
            'snag_reported', 
            'project_update', 
            'system'
        ) NOT NULL");
    }
};

