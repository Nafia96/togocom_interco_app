<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNationalInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('national_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->nullable();
            $table->string('direction')->nullable();
            $table->string('period')->nullable();
            $table->date('periodDate')->nullable();
            $table->date('invoice_date')->nullable();
            $table->decimal('total_volume', 20, 4)->default(0);
            $table->decimal('total_valorisation', 20, 2)->default(0);
            $table->decimal('total_ttc', 20, 2)->default(0);
            $table->text('lines_json')->nullable();
            $table->integer('created_by')->nullable();
            $table->string('facture_name')->nullable();
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
        Schema::dropIfExists('national_invoices');
    }
}
