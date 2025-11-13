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
        // Create task_library table
        Schema::create('task_library', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // Create task_library_descriptions table
        Schema::create('task_library_descriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_library_id')->constrained('task_library')->onDelete('cascade');
            $table->text('description');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_library_descriptions');
        Schema::dropIfExists('task_library');
    }
};
