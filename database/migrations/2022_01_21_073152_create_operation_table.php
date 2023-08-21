<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operation', function (Blueprint $table) {
            $table->id();
            //1 for tgc to opperator, 2 for operator to tgc
            $table->string('operation_type')->nullable();
            $table->string('repayment_type')->nullable();
            $table->string('invoice_type')->nullable();
            $table->string('operation_name')->nullable();
            $table->string('comment')->nullable();

            $table->unsignedBigInteger('id_operator');
            $table->foreign('id_operator')->references('id')->on('operator');

            $table->unsignedBigInteger('id_invoice');
            $table->foreign('id_invoice')->references('id')->on('invoice');


            $table->integer('is_delete')->default(0);
            $table->integer('id_op_account')->nullable();
            $table->integer('add_by')->nullable();
            $table->bigInteger('incoming_balance')->nullable();
            $table->bigInteger('output_balance')->nullable();
            $table->bigInteger('new_netting')->nullable();
            $table->bigInteger('amount')->nullable();
            $table->bigInteger('new_debt')->nullable();
            $table->bigInteger('new_receivable')->nullable();
            $table->bigInteger('account_number');
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
        Schema::dropIfExists('operation');
    }
}
