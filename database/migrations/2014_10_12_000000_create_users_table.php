<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('last_name');
                $table->string('first_name');
                $table->integer('type_user');
                $table->string('tel')->nullable();
                $table->string('post')->nullable();
                $table->string('date_debut')->nullable();
                $table->string('is_delete')->nullable();
                $table->string('token')->nullable();
                $table->string('create_by')->nullable();
                $table->string('delete_by')->nullable();
                $table->string('reactive_by')->nullable();
                $table->string('avatar')->nullable();
                $table->string('login')->unique();
                $table->string('email')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
