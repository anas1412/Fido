<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('honoraires', function (Blueprint $table) {
            $table->boolean('exonere_tf')->default(false);
            $table->boolean('exonere_rs')->default(false);
            $table->boolean('exonere_tva')->default(false);
        });
    }

    public function down()
    {
        Schema::table('honoraires', function (Blueprint $table) {
            $table->dropColumn(['exonere_tf', 'exonere_rs', 'exonere_tva']);
        });
    }
};
