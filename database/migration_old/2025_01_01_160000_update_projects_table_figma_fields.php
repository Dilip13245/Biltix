<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Drop fields that don't match Figma
            $table->dropColumn([
                'project_number', 'name', 'description', 'location', 'address', 
                'start_date', 'end_date', 'budget', 'currency', 'actual_cost',
                'client_name', 'client_email', 'client_phone', 'client_address',
                'progress_percentage', 'images', 'project_type'
            ]);
            
            // Add Figma-compliant fields
            $table->string('project_title')->after('id');
            $table->string('contractor_name')->after('project_title');
            $table->unsignedBigInteger('technical_engineer_id')->nullable()->after('project_manager_id');
            $table->string('type')->after('technical_engineer_id'); // Project Type as string
            $table->string('project_location')->after('type');
            $table->date('project_start_date')->nullable()->after('project_location');
            $table->date('project_due_date')->nullable()->after('project_start_date');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Drop Figma fields
            $table->dropColumn([
                'project_title', 'contractor_name', 'technical_engineer_id', 'type',
                'project_location', 'project_start_date', 'project_due_date'
            ]);
            
            // Restore original fields
            $table->string('project_number')->unique()->after('id');
            $table->string('name')->after('project_number');
            $table->text('description')->nullable()->after('name');
            $table->enum('project_type', ['residential', 'commercial', 'industrial', 'renovation'])->after('description');
            $table->string('location')->after('project_type');
            $table->text('address')->nullable()->after('location');
            $table->date('start_date')->after('address');
            $table->date('end_date')->after('start_date');
            $table->decimal('budget', 15, 2)->default(0)->after('end_date');
            $table->string('currency', 3)->default('USD')->after('budget');
            $table->decimal('actual_cost', 15, 2)->default(0)->after('currency');
            $table->string('client_name')->nullable()->after('actual_cost');
            $table->string('client_email')->nullable()->after('client_name');
            $table->string('client_phone')->nullable()->after('client_email');
            $table->text('client_address')->nullable()->after('client_phone');
            $table->integer('progress_percentage')->default(0)->after('created_by');
            $table->json('images')->nullable()->after('progress_percentage');
        });
    }
};