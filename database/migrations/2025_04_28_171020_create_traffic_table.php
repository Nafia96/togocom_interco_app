<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrafficTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('traffic', function (Blueprint $table) {
            $table->id();
            $table->string('amount')->nullable();
            $table->date('date')->nullable();
            $table->integer('overall_total_records')->nullable();
            $table->decimal('overall_total_gross', 15, 2)->nullable();
            $table->integer('overall_total_no_imsi')->nullable();
            $table->integer('total_records')->nullable();
            $table->decimal('total_gross', 15, 2)->nullable();
            $table->integer('moc_voice_records')->nullable();
            $table->decimal('moc_voice_duration', 15, 2)->nullable();
            $table->decimal('moc_voice_gross', 15, 2)->nullable();
            $table->decimal('mtc_gross', 15, 2)->nullable();
            $table->integer('moc_sms_records')->nullable();
            $table->decimal('moc_sms_gross', 15, 2)->nullable();
            $table->integer('mtc_records')->nullable();
            $table->decimal('mtc_duration', 15, 2)->nullable();
            $table->integer('gprs_records')->nullable();
            $table->decimal('gprs_volume', 15, 2)->nullable();
            $table->decimal('gprs_gross', 15, 2)->nullable();
            $table->integer('volte_records')->nullable();
            $table->decimal('volte_volume', 15, 2)->nullable();
            $table->decimal('volte_gross', 15, 2)->nullable();
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
        Schema::dropIfExists('traffic');
    }
}
