<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'member_name')) {
                $table->dropColumn('member_name');
            }
            if (Schema::hasColumn('users', 'member_number')) {
                $table->dropColumn('member_number');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('member_name')->nullable();
            $table->string('member_number')->nullable();
        });
    }
};