<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class WidenCodeColumnOnRoamingOpeningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `roaming_openings` MODIFY `code` VARCHAR(255) NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (DB::table('roaming_openings')->whereRaw('CHAR_LENGTH(`code`) > 20')->exists()) {
            throw new \RuntimeException('Cannot shrink roaming_openings.code while values exceed 20 characters.');
        }

        DB::statement('ALTER TABLE `roaming_openings` MODIFY `code` VARCHAR(20) NOT NULL');
    }
}
