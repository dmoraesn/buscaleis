<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('autors', function (Blueprint $table) {
            $table->id();
            $table->string('nome'); // Ex: "Poder Executivo", "Vereador José da Silva"
            $table->string('tipo')->nullable(); // Ex: "Vereador", "Prefeito"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('autors');
    }
};
