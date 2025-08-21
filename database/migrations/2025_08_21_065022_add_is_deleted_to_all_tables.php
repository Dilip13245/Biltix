<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $tables = [
            'users', 'projects', 'tasks', 'inspections', 'inspection_templates', 'snags', 'snag_categories',
            'plans', 'daily_logs', 'team_members', 'files', 'file_categories', 'photos',
            'notifications', 'project_phases', 'task_comments', 'inspection_checklist_items',
            'snag_comments', 'plan_markups', 'daily_log_equipment', 'daily_log_staff'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'is_deleted')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->boolean('is_deleted')->default(false)->after('is_active');
                });
            }
        }
    }

    public function down()
    {
        $tables = [
            'users', 'projects', 'tasks', 'inspections', 'inspection_templates', 'snags', 'snag_categories',
            'plans', 'daily_logs', 'team_members', 'files', 'file_categories', 'photos',
            'notifications', 'project_phases', 'task_comments', 'inspection_checklist_items',
            'snag_comments', 'plan_markups', 'daily_log_equipment', 'daily_log_staff'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'is_deleted')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropColumn('is_deleted');
                });
            }
        }
    }
};