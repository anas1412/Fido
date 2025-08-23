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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('client_name');
            $table->string('client_mf');
            $table->string('invoice_number')->unique();
            $table->date('date');
            $table->decimal('total_hors_taxe', 15, 2);
            $table->decimal('tva', 15, 2);
            $table->decimal('montant_ttc', 15, 2);
            $table->decimal('timbre_fiscal', 15, 2);
            $table->decimal('net_a_payer', 15, 2);
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
