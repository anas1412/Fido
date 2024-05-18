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
        Schema::create('honoraires', function (Blueprint $table) {
            $table->id();
            $table->integer('note')
                ->nullable();
            $table->text('object')
                ->nullable();
            $table->decimal('montantHT')
                ->nullable();
            $table->decimal('montantTTC')
                ->nullable();
            $table->decimal('tva')
                ->nullable();
            $table->decimal('rs')
                ->nullable();
            $table->decimal('tf')
                ->nullable();
            $table->decimal('netapayer')
                ->nullable();
            $table->foreignId('client_id')
                ->constrained()
                ->onDelete('cascade')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('honoraires');
    }
};
