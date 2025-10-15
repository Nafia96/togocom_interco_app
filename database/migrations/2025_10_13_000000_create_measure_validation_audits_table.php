<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measure_validation_audits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('measure_id')->index();
            $table->unsignedBigInteger('changed_by')->nullable()->index();
            $table->decimal('old_value', 20, 2)->nullable();
            $table->decimal('new_value', 20, 2)->nullable();
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('measure_validation_audits');
    }
};
