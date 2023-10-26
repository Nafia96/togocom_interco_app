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
            $table->date('periodDate')->nullable();
            $table->string('service')->nullable();

    
            $table->unsignedBigInteger('id_operation_1');
            $table->foreign('id_operation_1')->references('id')->on('operation');
                                                                                                                                                                                                                                                
            $table->unsignedBigInteger('id_operation_2');
            $table->foreign('id_operation_2')->references('id')->on('operation');


            $table->unsignedBigInteger('id_operator');
            $table->foreign('id_operator')->references('id')->on('operator');

            //Receivable c'est ce que l'operateur doit à Togocom decimal('amount', 16, 2)
            $table->decimal('receivable', 16, 2)->nullable();
            $table->decimal('receivable_cfa', 16, 2)->nullable();
            //Debt c'est ce que TOGOCOM doit à l'operateur
            $table->decimal('debt', 16, 2)->nullable();
            $table->decimal('debt_cfa', 16, 2)->nullable();

            $table->decimal('incoming_payement', 16, 2)->nullable();
            $table->decimal('incoming_payement_cfa', 16, 2)->nullable();

            $table->decimal('payout', 16, 2)->nullable();
            $table->decimal('payout_cfa', 16, 2)->nullable();

            $table->integer('is_delete')->default(0);

            $table->decimal('netting', 16, 2)->nullable();
            
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
