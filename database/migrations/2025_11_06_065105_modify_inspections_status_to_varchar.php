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
        // Change status column from enum to varchar to match local database structure
        DB::statement("ALTER TABLE `inspections` MODIFY COLUMN `status` VARCHAR(50) DEFAULT 'open'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to enum if needed
        DB::statement("ALTER TABLE `inspections` MODIFY COLUMN `status` ENUM('open', 'in_progress', 'completed', 'failed') DEFAULT 'open'");
    }
};
