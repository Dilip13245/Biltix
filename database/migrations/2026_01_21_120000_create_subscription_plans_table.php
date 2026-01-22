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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // basic, pro, enterprise
            $table->string('display_name'); // Basic Plan, Pro Plan
            $table->decimal('price', 10, 2); // 300.00, 468.00
            $table->string('billing_period')->default('yearly'); // monthly, yearly
            $table->string('currency', 10)->default('USD');
            $table->text('description')->nullable();
            $table->integer('max_projects')->nullable(); // null = unlimited
            $table->integer('max_team_members')->nullable(); // null = unlimited
            $table->boolean('is_default')->default(false); // Default plan for new users
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
