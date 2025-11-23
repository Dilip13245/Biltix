<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_sub_user')->default(false)->after('is_verified');
            $table->unsignedBigInteger('parent_user_id')->nullable()->after('is_sub_user');
            $table->foreign('parent_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['parent_user_id']);
            $table->dropColumn(['is_sub_user', 'parent_user_id']);
        });
    }
};
