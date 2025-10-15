<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('unit_prices', function (Blueprint $table) {
            $table->id();
            $table->string('direction')->index();
            $table->string('period', 7)->nullable()->index();
            $table->decimal('price', 12, 4)->default(0);
            $table->timestamp('effective_from')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('unit_prices');
    }
};
