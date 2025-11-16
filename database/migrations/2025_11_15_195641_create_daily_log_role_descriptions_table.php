<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daily_log_role_descriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('created_by');
            $table->string('role'); // e.g., 'engineer', 'foreman', 'supervisor' or any 3 roles
            $table->text('description');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            
            // Index for better performance (using shorter names to avoid MySQL 64 char limit)
            $table->index(['project_id', 'role'], 'dlrd_project_role_idx');
            $table->index(['project_id', 'is_active', 'is_deleted'], 'dlrd_project_status_idx');
            $table->index('created_by', 'dlrd_created_by_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_log_role_descriptions');
    }
};
