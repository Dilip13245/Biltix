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
        Schema::create('company_teams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id'); // Main user ID (company owner)
            $table->unsignedBigInteger('member_user_id'); // Team member user ID
            $table->unsignedBigInteger('added_by'); // Who added this member (can be main user or sub user)
            $table->string('role'); // Role of the team member
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index('company_id');
            $table->index('member_user_id');
            $table->index('added_by');
            
            // Prevent duplicate entries
            $table->unique(['company_id', 'member_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_teams');
    }
};
