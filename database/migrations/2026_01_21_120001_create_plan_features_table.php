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
        Schema::create('plan_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('subscription_plans')->onDelete('cascade');
            $table->string('feature_key'); // e.g., 'team.add_member', 'plans.markup', 'chat.access'
            $table->string('feature_name'); // Display name: 'Add Team Member', 'Drawing & Markup'
            $table->string('module')->nullable(); // e.g., 'team', 'plans', 'chat' (for grouping)
            $table->string('action')->nullable(); // e.g., 'add_member', 'markup', 'access'
            $table->integer('limit_value')->nullable(); // For numeric limits (e.g., max 5 projects)
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Prevent duplicate features per plan
            $table->unique(['plan_id', 'feature_key']);
            $table->index(['feature_key']);
            $table->index(['module', 'action']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_features');
    }
};
