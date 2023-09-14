<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContestationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contestation', function (Blueprint $table) {

            $table->id();

          
            $table->unsignedBigInteger('id_operator');
            $table->foreign('id_operator')->references('id')->on('operator');

            $table->unsignedBigInteger('id_invoice');
            $table->foreign('id_invoice')->references('id')->on('invoice');

            $table->unsignedBigInteger('operation_id');
            $table->foreign('operation_id')->references('id')->on('operation');

            $table->bigInteger('amount')->nullable();
            $table->date('contesation_date')->nullable();
            $table->string('comment')->nullable();
            $table->string('is_delete')->default(0);

            
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
        Schema::dropIfExists('contestation');
    }
}
