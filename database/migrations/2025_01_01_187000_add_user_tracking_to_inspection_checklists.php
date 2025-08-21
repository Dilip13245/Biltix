<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('inspection_checklists', function (Blueprint $table) {
            $table->unsignedBigInteger('updated_by')->nullable()->after('is_checked');
            $table->timestamp('checked_at')->nullable()->after('updated_by');
        });
    }

    public function down()
    {
        Schema::table('inspection_checklists', function (Blueprint $table) {
            $table->dropColumn(['updated_by', 'checked_at']);
        });
    }
};