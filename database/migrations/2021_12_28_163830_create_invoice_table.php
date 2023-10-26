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
            $table->date('periodDate')->nullable();

            $table->date('invoice_date')->nullable();

            $table->decimal('call_volume', 16, 2)->nullable();
            $table->string('number_of_call')->nullable();
            $table->string('invoice_type')->nullable();
            $table->integer('add_by')->nullable();

            $table->string('is_delete')->default(0);


            $table->integer('tgc_invoice')->nullable(); 
            $table->decimal('amount', 16, 2)->nullable();
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
