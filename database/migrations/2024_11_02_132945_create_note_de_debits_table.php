<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('note_de_debits', function (Blueprint $table) {
            $table->id();
            $table->integer('note')
                ->nullable();
            $table->foreignId('client_id')->constrained()->onDelete('cascade')->nullable();
            $table->decimal('amount')->nullable();
            $table->string('description')->nullable();
            $table->date('date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('note_de_debits');
    }
};
