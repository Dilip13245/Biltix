<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, change status column to VARCHAR temporarily
        DB::statement("ALTER TABLE `snags` MODIFY COLUMN `status` VARCHAR(50)");
        
        // Update all existing status values to match new enum
        DB::statement("UPDATE `snags` SET `status` = 'todo' WHERE `status` IN ('new', 'open', 'pending')");
        DB::statement("UPDATE `snags` SET `status` = 'complete' WHERE `status` IN ('resolved', 'completed', 'closed')");
        DB::statement("UPDATE `snags` SET `status` = 'in_progress' WHERE `status` NOT IN ('todo', 'complete', 'approve')");
        
        // Now change to enum with new values
        DB::statement("ALTER TABLE `snags` MODIFY COLUMN `status` ENUM('todo', 'in_progress', 'complete', 'approve') DEFAULT 'todo'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE `snags` MODIFY COLUMN `status` ENUM('open', 'in_progress', 'resolved', 'closed') DEFAULT 'open'");
    }
};
