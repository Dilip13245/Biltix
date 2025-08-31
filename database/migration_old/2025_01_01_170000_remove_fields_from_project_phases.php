<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('project_phases', function (Blueprint $table) {
            $table->dropColumn(['budget_allocated', 'actual_cost', 'progress_percentage', 'description', 'phase_order']);
        });
    }

    public function down()
    {
        Schema::table('project_phases', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->integer('phase_order')->default(1);
            $table->decimal('budget_allocated', 15, 2)->nullable();
            $table->decimal('actual_cost', 15, 2)->nullable();
            $table->decimal('progress_percentage', 5, 2)->default(0);
        });
    }
};