<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, update all existing 'in_progress' status to 'todo'
        DB::statement("UPDATE `snags` SET `status` = 'todo' WHERE `status` = 'in_progress'");
        
        // Change status column to VARCHAR temporarily
        DB::statement("ALTER TABLE `snags` MODIFY COLUMN `status` VARCHAR(50)");
        
        // Now change to enum without 'in_progress'
        DB::statement("ALTER TABLE `snags` MODIFY COLUMN `status` ENUM('todo', 'complete', 'approve') DEFAULT 'todo'");
    }

    public function down()
    {
        // Change status column to VARCHAR temporarily
        DB::statement("ALTER TABLE `snags` MODIFY COLUMN `status` VARCHAR(50)");
        
        // Restore enum with 'in_progress'
        DB::statement("ALTER TABLE `snags` MODIFY COLUMN `status` ENUM('todo', 'in_progress', 'complete', 'approve') DEFAULT 'todo'");
    }
};

