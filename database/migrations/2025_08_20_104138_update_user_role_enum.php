<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update the enum to include new values
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('manager', 'engineer', 'worker', 'inspector', 'contractor', 'consultant', 'site_engineer', 'project_manager', 'stakeholder') DEFAULT 'worker'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('manager', 'engineer', 'worker', 'inspector') DEFAULT 'worker'");
    }
};