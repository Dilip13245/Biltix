<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add missing fields identified from Figma analysis
        
        // 1. Add snag_number to snags table (visible in Figma snag screens)
        Schema::table('snags', function (Blueprint $table) {
            $table->string('snag_number')->unique()->after('id');
        });
        
        // 2. Add task_number to tasks table (for better tracking)
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('task_number')->unique()->after('id');
        });
        
        // 3. Add inspection_number to inspections table
        Schema::table('inspections', function (Blueprint $table) {
            $table->string('inspection_number')->unique()->after('id');
        });
        
        // 4. Add weather_temperature to daily_logs (seen in Figma daily logs)
        Schema::table('daily_logs', function (Blueprint $table) {
            $table->decimal('temperature', 4, 1)->nullable()->after('weather_conditions');
        });
        
        // 5. Add project_code to projects table (for better identification)
        Schema::table('projects', function (Blueprint $table) {
            $table->string('project_code')->unique()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('snags', function (Blueprint $table) {
            $table->dropColumn('snag_number');
        });
        
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('task_number');
        });
        
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropColumn('inspection_number');
        });
        
        Schema::table('daily_logs', function (Blueprint $table) {
            $table->dropColumn('temperature');
        });
        
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('project_code');
        });
    }
};