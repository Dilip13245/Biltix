<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropColumn([
                'inspection_number', 'phase_id', 'template_id', 'inspection_type', 'title',
                'scheduled_date', 'scheduled_time', 'started_at', 'completed_at', 'inspector_id',
                'location', 'overall_result', 'score_percentage', 'notes', 'images'
            ]);
            $table->string('category')->after('project_id');
            $table->text('comment')->nullable()->after('description');
            $table->string('status')->default('open')->change();
            $table->unsignedBigInteger('inspected_by')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropColumn(['category', 'description', 'comment', 'inspected_by']);
            $table->string('inspection_number')->after('id');
            $table->unsignedBigInteger('phase_id')->nullable();
            $table->unsignedBigInteger('template_id')->nullable();
            $table->string('inspection_type');
            $table->string('title');
            $table->date('scheduled_date')->nullable();
            $table->time('scheduled_time')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->unsignedBigInteger('inspector_id')->nullable();
            $table->string('location')->nullable();
            $table->string('overall_result')->nullable();
            $table->decimal('score_percentage', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->json('images')->nullable();
        });
    }
};