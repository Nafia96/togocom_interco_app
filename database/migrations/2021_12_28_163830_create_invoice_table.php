<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->nullable();
            $table->string('period')->nullable();
            $table->date('invoice_date')->nullable();

            $table->string('call_volume')->nullable();
            $table->string('number_of_call')->nullable();
            $table->string('invoice_type')->nullable();
            $table->integer('add_by')->nullable();



            $table->integer('tgc_invoice')->nullable();
            $table->bigInteger('amount')->nullable();
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
        Schema::dropIfExists('invoice');
    }
}
