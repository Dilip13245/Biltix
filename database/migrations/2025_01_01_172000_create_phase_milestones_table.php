<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('phase_milestones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('phase_id');
            $table->string('milestone_name');
            $table->integer('days');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('phase_milestones');
    }
};