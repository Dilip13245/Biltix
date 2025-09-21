<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('snags', function (Blueprint $table) {
            // Remove fields
            $table->dropColumn(['priority', 'severity', 'category_id', 'images_before', 'images_after', 'cost_impact']);
            
            // Add new fields
            $table->string('image')->nullable()->after('location');
            $table->unsignedBigInteger('phase_id')->nullable()->after('project_id');
            $table->text('comment')->nullable()->after('description');
        });
    }

    public function down()
    {
        Schema::table('snags', function (Blueprint $table) {
            // Add back removed fields
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('severity', ['minor', 'major', 'critical'])->default('minor');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->json('images_before')->nullable();
            $table->json('images_after')->nullable();
            $table->decimal('cost_impact', 10, 2)->nullable();
            
            // Remove new fields
            $table->dropColumn(['image', 'phase_id']);
        });
    }
};