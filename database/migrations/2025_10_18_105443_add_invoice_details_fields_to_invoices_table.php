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
            $table->string('mode_de_paiement')->nullable();
            $table->string('mode_de_livraison')->nullable();
            $table->string('banque')->nullable();
            $table->string('iban')->nullable();
            $table->string('swift')->nullable();
            $table->string('nombre_de_lot')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'mode_de_paiement',
                'mode_de_livraison',
                'banque',
                'iban',
                'swift',
                'nombre_de_lot',
            ]);
        });
    }
};
