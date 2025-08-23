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
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('total_hors_taxe', 15, 2)->nullable()->change();
            $table->decimal('tva', 15, 2)->nullable()->change();
            $table->decimal('montant_ttc', 15, 2)->nullable()->change();
            $table->decimal('timbre_fiscal', 15, 2)->nullable()->change();
            $table->decimal('net_a_payer', 15, 2)->nullable()->change();
            $table->string('client_name')->nullable()->change();
            $table->string('client_mf')->nullable()->change();
            $table->date('date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('total_hors_taxe', 15, 2)->nullable(false)->change();
            $table->decimal('tva', 15, 2)->nullable(false)->change();
            $table->decimal('montant_ttc', 15, 2)->nullable(false)->change();
            $table->decimal('timbre_fiscal', 15, 2)->nullable(false)->change();
            $table->decimal('net_a_payer', 15, 2)->nullable(false)->change();
            $table->string('client_name')->nullable(false)->change();
            $table->string('client_mf')->nullable(false)->change();
            $table->date('date')->nullable(false)->change();
        });
    }
};
