<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditnoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Creditnote', function (Blueprint $table) {

            $table->id();

          
            $table->unsignedBigInteger('id_operator');
            $table->foreign('id_operator')->references('id')->on('operator');

            $table->unsignedBigInteger('id_invoice');
            $table->foreign('id_invoice')->references('id')->on('invoice');

            $table->unsignedBigInteger('operation_id');
            $table->foreign('operation_id')->references('id')->on('operation');

            $table->unsignedBigInteger('contestation_id');
            $table->foreign('contestation_id')->references('id')->on('contestation');

            $table->decimal('debt', 16, 2)->nullable();

            $table->decimal('receivable', 16, 2)->nullable();
            
            $table->string('is_delete')->default(0);


            $table->string('comment')->nullable();
            
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
        Schema::dropIfExists('Creditnote');
    }
}
