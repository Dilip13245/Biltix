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
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('token')->nullable();
            $table->enum('device_type', ['A', 'I']); // A = Android, I = iOS
            $table->string('ip_address')->nullable();
            $table->string('uuid')->nullable();
            $table->string('os_version')->nullable();
            $table->string('device_model')->nullable();
            $table->string('app_version')->default('v1');
            $table->string('device_token')->nullable(); // For push notifications
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_devices');
    }
};