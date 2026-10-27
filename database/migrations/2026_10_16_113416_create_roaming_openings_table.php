<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoamingOpeningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roaming_openings', function (Blueprint $table) {
            $table->id();
            $table->string('country', 100);
            $table->string('code', 20);
            $table->string('operator', 150);
            $table->string('service', 50);
            $table->string('direction', 20)->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->index('country', 'idx_country');
            $table->index('operator', 'idx_operator');
            $table->index('service', 'idx_service');
            $table->index('direction', 'idx_direction');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roaming_openings');
    }
}
