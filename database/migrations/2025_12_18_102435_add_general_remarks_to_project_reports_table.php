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
        Schema::table('project_reports', function (Blueprint $table) {
            $table->text('general_remarks')->nullable()->after('generated_by');
            $table->string('general_remarks_attachment')->nullable()->after('general_remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_reports', function (Blueprint $table) {
            $table->dropColumn(['general_remarks', 'general_remarks_attachment']);
        });
    }
};
