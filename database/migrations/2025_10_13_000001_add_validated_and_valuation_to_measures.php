<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('measures', function (Blueprint $table) {
            $table->decimal('traffic_validated', 20, 2)->nullable()->after('pct_diff');
            $table->decimal('valuation', 20, 2)->nullable()->after('traffic_validated');
        });
    }

    public function down()
    {
        Schema::table('measures', function (Blueprint $table) {
            $table->dropColumn(['traffic_validated', 'valuation']);
        });
    }
};
