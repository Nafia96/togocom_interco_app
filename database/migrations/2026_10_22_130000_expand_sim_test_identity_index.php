<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExpandSimTestIdentityIndex extends Migration
{
    public function up()
    {
        Schema::table('sim_test_roaming', function (Blueprint $table) {
            $table->dropUnique('uq_sim_test_identity');
            $table->unique(['group_number', 'msisdn', 'imsi', 'rp_name'], 'uq_sim_test_identity');
        });
    }

    public function down()
    {
        Schema::table('sim_test_roaming', function (Blueprint $table) {
            $table->dropUnique('uq_sim_test_identity');
            $table->unique(['group_number', 'msisdn', 'imsi'], 'uq_sim_test_identity');
        });
    }
}
