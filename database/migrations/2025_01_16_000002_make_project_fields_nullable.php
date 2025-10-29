<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('contractor_name')->nullable()->change();
            $table->unsignedBigInteger('project_manager_id')->nullable()->change();
            $table->unsignedBigInteger('technical_engineer_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('contractor_name')->nullable(false)->change();
            $table->unsignedBigInteger('project_manager_id')->nullable(false)->change();
            $table->unsignedBigInteger('technical_engineer_id')->nullable(false)->change();
        });
    }
};