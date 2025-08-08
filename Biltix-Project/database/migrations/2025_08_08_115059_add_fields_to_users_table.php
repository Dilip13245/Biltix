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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->enum('role', ['manager', 'engineer', 'worker', 'inspector'])->default('worker')->after('phone');
            $table->string('designation')->nullable()->after('role');
            $table->string('profile_image')->nullable()->after('designation');
            $table->boolean('is_active')->default(true)->after('profile_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'role', 'designation', 'profile_image', 'is_active']);
        });
    }
};
