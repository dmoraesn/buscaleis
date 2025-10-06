<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('autor_materia', function (Blueprint $table) {
            // Chave estrangeira para a tabela de matérias
            $table->foreignId('materia_id')->constrained()->onDelete('cascade');

            // Chave estrangeira para a tabela de autores
            $table->foreignId('autor_id')->constrained()->onDelete('cascade');

            // Define que a combinação de materia_id e autor_id deve ser única
            $table->primary(['materia_id', 'autor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('autor_materia');
    }
};
