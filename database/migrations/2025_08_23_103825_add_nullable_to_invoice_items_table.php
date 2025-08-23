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
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->string('object')->nullable()->change();
            $table->integer('quantity')->nullable()->change();
            $table->decimal('single_price', 15, 2)->nullable()->change();
            $table->decimal('total_price', 15, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->string('object')->nullable(false)->change();
            $table->integer('quantity')->nullable(false)->change();
            $table->decimal('single_price', 15, 2)->nullable(false)->change();
            $table->decimal('total_price', 15, 2)->nullable(false)->change();
        });
    }
};
