<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('measures', function (Blueprint $table) {
            $table->id();
            $table->string('period', 7)->index(); // YYYY-MM
            $table->decimal('m_tgt', 20, 2)->default(0);
            $table->decimal('m_tgc', 20, 2)->default(0);
            $table->decimal('diff', 20, 2)->default(0);
            $table->decimal('pct_diff', 8, 4)->default(0);
            $table->string('direction')->nullable(); // e.g. TGT->TGC, TGC->MAT
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('measures');
    }
};
