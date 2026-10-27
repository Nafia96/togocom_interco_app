<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimTestRoamingTable extends Migration
{
    public function up()
    {
        Schema::create('sim_test_roaming', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('group_number')->nullable();
            $table->string('msisdn', 20);
            $table->string('imsi', 20);
            $table->string('rp_name', 150);
            $table->string('country', 100);
            $table->string('comments', 255)->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->index('country', 'idx_sim_test_country');
            $table->index('rp_name', 'idx_sim_test_rp_name');
            $table->index('msisdn', 'idx_sim_test_msisdn');
            $table->unique(['group_number', 'msisdn', 'imsi', 'rp_name'], 'uq_sim_test_identity');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sim_test_roaming');
    }
}
