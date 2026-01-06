<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('static_content', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['privacy', 'terms']);
            $table->enum('language', ['en', 'ar']);
            $table->string('title');
            $table->longText('content');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
            
            $table->unique(['type', 'language']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('static_content');
    }
};
