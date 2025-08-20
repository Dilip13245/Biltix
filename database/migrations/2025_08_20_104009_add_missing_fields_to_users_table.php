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
            $table->string('company_name')->nullable()->after('role');
            $table->integer('employee_count')->nullable()->after('company_name');
            $table->string('member_number')->nullable()->after('employee_count');
            $table->string('member_name')->nullable()->after('member_number');
            $table->string('language', 2)->default('en')->after('member_name');
            $table->string('timezone')->nullable()->after('language');
            $table->timestamp('last_login_at')->nullable()->after('timezone');
            $table->string('otp', 6)->nullable()->after('last_login_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'employee_count', 
                'member_number',
                'member_name',
                'language',
                'timezone',
                'last_login_at',
                'otp'
            ]);
        });
    }
};