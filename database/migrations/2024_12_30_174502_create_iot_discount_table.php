<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIotDiscountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iot_dscount', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('country')->nullable();
            $table->string('operator')->nullable();
            $table->string('live_date')->nullable();
            $table->string('renewal')->nullable();
            $table->string('negociation')->nullable();
            $table->string('comments')->nullable();
            $table->integer('is_delete')->default(0);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account');
    }
}
