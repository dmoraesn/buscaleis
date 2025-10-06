<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materias', function (Blueprint $table) {
            $table->id(); // Chave primária auto-incremental (ex: 1, 2, 3...)
            $table->integer('numero'); // O número da matéria, ex: 71
            $table->year('ano'); // O ano da matéria, ex: 2025
            $table->date('data_apresentacao'); // A data de apresentação
            $table->text('ementa'); // A ementa/resumo. Usamos text por ser um campo longo.
            $table->string('texto_original')->nullable(); // O link para o PDF, pode ser nulo.
            $table->boolean('em_tramitacao')->default(true); // Para sabermos o status geral.
            $table->text('observacao')->nullable(); // Campo de observações, pode ser nulo.
            $table->string('resultado')->nullable(); // O resultado da votação, pode ser nulo.

            // Chaves estrangeiras (vamos criar as tabelas para elas em breve)
            $table->unsignedBigInteger('tipo_id')->nullable();
            $table->unsignedBigInteger('regime_tramitacao_id')->nullable();
            // Adicionei um campo para o status, baseado no arquivo statustramitacao.json
            $table->unsignedBigInteger('status_id')->nullable();

            $table->timestamps(); // Cria as colunas `created_at` e `updated_at` automaticamente
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materias');
    }
};
