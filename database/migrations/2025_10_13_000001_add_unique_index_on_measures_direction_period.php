<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('measures', function (Blueprint $table) {
            // Add unique index to prevent duplicate direction+period entries
            $table->unique(['direction', 'period'], 'measures_direction_period_unique');
        });
    }

    public function down()
    {
        Schema::table('measures', function (Blueprint $table) {
            $table->dropUnique('measures_direction_period_unique');
        });
    }
};
