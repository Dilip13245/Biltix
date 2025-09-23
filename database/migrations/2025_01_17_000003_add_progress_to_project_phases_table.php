<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_phases', function (Blueprint $table) {
            $table->decimal('progress_percentage', 5, 2)->default(0.00);
        });
    }

    public function down(): void
    {
        Schema::table('project_phases', function (Blueprint $table) {
            $table->dropColumn('progress_percentage');
        });
    }
};