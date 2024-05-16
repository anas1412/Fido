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
            $table->integer('note')->nullable();
            $table->text('object');
            $table->decimal('montantHT');
            $table->decimal('montantTTC');
            $table->decimal('tva');
            $table->decimal('rs');
            $table->decimal('tf');
            $table->decimal('netapayer');
            $table->foreignId('client_id')
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
