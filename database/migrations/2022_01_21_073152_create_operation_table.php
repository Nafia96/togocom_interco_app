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
            $table->integer('operation_type')->nullable();
            $table->string('repayment_type')->nullable();
            $table->string('invoice_type')->nullable();
            $table->string('operation_name')->nullable();
            $table->string('comment')->nullable();
            $table->string('facture_name')->nullable();

            $table->unsignedBigInteger('id_operator');
            $table->foreign('id_operator')->references('id')->on('operator');

            $table->unsignedBigInteger('id_invoice');
            $table->foreign('id_invoice')->references('id')->on('invoice');

            $table->unsignedBigInteger('add_by');
            $table->foreign('add_by')->references('id')->on('users');

            $table->integer('is_delete')->default(0);
            $table->integer('id_op_account')->nullable();
            $table->decimal('incoming_balance', 16, 2)->nullable();
            $table->decimal('output_balance', 16, 2)->nullable();
            $table->decimal('new_netting', 16, 2)->nullable();
            $table->decimal('amount', 16, 2)->nullable();
            $table->decimal('new_debt', 16, 2)->nullable();
            $table->decimal('new_receivable', 16, 2)->nullable();
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
