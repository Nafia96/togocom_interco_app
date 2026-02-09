<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetIntercoJournalierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_interco_journalier', function (Blueprint $table) {
            $table->id();
            $table->integer('annee');
            $table->string('mois');
            $table->bigInteger('revenu_journalier');
            $table->bigInteger('charge_journaliere');
            $table->timestamps();

            $table->unique(['annee', 'mois']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budget_interco_journalier');
    }
}
