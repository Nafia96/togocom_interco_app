<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resum', function (Blueprint $table) {
            $table->id();

            $table->string('period')->nullable();

            $table->unsignedBigInteger('id_invoice_1');
            $table->foreign('id_invoice_1')->references('id')->on('invoice');

            $table->unsignedBigInteger('id_invoice_2');
            $table->foreign('id_invoice_2')->references('id')->on('invoice');

            $table->unsignedBigInteger('id_operator');
            $table->foreign('id_operator')->references('id')->on('operator');

            //Receivable c'est ce que l'operateur doit à Togocom
            $table->string('receivable')->nullable();
            //Debt c'est ce que TOGOCOM doit à l'operateur
            $table->string('debt')->nullable();

            $table->bigInteger('incoming_payement')->nullable();

            $table->bigInteger('payout')->nullable();


            $table->bigInteger('netting')->nullable();
            
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
        Schema::dropIfExists('resum');
    }
}
