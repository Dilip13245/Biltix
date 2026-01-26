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
        Schema::table('gantt_activities', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->integer('workers_count')->default(0)->after('end_date');
            $table->integer('equipment_count')->default(0)->after('workers_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gantt_activities', function (Blueprint $table) {
            $table->dropColumn(['description', 'workers_count', 'equipment_count']);
        });
    }
};
