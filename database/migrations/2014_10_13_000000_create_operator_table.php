<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operator', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('currency')->nullable();
            $table->float('euro_conversion', 10, 3)->nullable();
            $table->integer('dollar_conversion')->nullable();
            $table->integer('xaf_conversion')->nullable();
            $table->string('tel')->nullable();
            $table->string('tel2')->nullable();
            $table->string('email')->nullable();
            $table->string('email2')->nullable();
            $table->string('email3')->nullable();
            $table->integer('cedeao')->nullable();
            $table->integer('afrique')->nullable();
            $table->integer('is_carrier')->nullable();
            $table->string('adresse')->nullable();
            $table->string('country')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('operator');
    }
}
