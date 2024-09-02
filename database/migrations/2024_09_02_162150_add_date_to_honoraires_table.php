<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('honoraires', function (Blueprint $table) {
            $table->date('date')->default(now())->nullable(); // Adding the date column with default current date
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('honoraires', function (Blueprint $table) {
            $table->dropColumn('date');
        });
    }
};