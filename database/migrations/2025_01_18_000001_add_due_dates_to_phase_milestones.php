<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('phase_milestones', function (Blueprint $table) {
            $table->integer('extension_days')->default(0)->after('days');
            $table->boolean('is_extended')->default(false)->after('extension_days');
        });
    }

    public function down(): void
    {
        Schema::table('phase_milestones', function (Blueprint $table) {
            $table->dropColumn(['extension_days', 'is_extended']);
        });
    }
};