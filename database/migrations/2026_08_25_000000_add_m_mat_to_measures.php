<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('measures') && !Schema::hasColumn('measures', 'm_mat')) {
            Schema::table('measures', function (Blueprint $table) {
                $table->decimal('m_mat', 20, 2)->default(0)->after('m_tgc');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('measures') && Schema::hasColumn('measures', 'm_mat')) {
            Schema::table('measures', function (Blueprint $table) {
                $table->dropColumn('m_mat');
            });
        }
    }
};
