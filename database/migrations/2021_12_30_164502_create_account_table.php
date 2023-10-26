<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_operator');
            $table->foreign('id_operator')->references('id')->on('operator');

            $table->string('account_number')->unique();
            //Receivable c'est ce que l'operateur doit à Togocom
            $table->string('receivable')->nullable();
            //Debt c'est ce que TOGOCOM doit à l'operateur
            $table->string('debt')->nullable();
            $table->integer('delete_by')->nullable();
            $table->integer('reactive_by')->nullable();
            $table->decimal('netting', 16, 2)->nullable();
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
