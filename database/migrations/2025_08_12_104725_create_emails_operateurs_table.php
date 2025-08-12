<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailsOperateursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails_operateurs', function (Blueprint $table) {


            $table->id();
            $table->foreignId('operateur_id')->constrained('operateurs')->onDelete('cascade');
            $table->string('email');
            $table->boolean('est_principal')->default(false);
            $table->date('update_date')->nullable();
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
        Schema::dropIfExists('emails_operateurs');
    }
}
