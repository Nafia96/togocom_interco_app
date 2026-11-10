<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('international_declarations', function (Blueprint $table) {
            $table->id();
            $table->string('period', 7)->unique();
            $table->decimal('retail_tgc', 20, 2)->nullable();
            $table->decimal('wholesale', 20, 2)->nullable();
            $table->decimal('visitor_roaming', 20, 2)->nullable();
            $table->decimal('charge', 20, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('international_declarations');
    }
};
