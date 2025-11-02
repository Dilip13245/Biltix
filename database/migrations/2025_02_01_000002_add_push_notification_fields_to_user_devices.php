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
        Schema::table('user_devices', function (Blueprint $table) {
            if (!Schema::hasColumn('user_devices', 'push_notification_enabled')) {
                $table->boolean('push_notification_enabled')->default(true)->after('device_token');
            }
            if (!Schema::hasColumn('user_devices', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('push_notification_enabled');
            }
            if (!Schema::hasColumn('user_devices', 'is_deleted')) {
                $table->boolean('is_deleted')->default(false)->after('is_active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_devices', function (Blueprint $table) {
            if (Schema::hasColumn('user_devices', 'push_notification_enabled')) {
                $table->dropColumn('push_notification_enabled');
            }
            if (Schema::hasColumn('user_devices', 'is_active')) {
                $table->dropColumn('is_active');
            }
            if (Schema::hasColumn('user_devices', 'is_deleted')) {
                $table->dropColumn('is_deleted');
            }
        });
    }
};

