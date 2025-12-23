<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_quality_work', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->text('description');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->index('project_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_quality_work');
    }
};
