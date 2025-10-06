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
        // Baseado em statustramitacao.json
        Schema::create('status_tramitacaos', function (Blueprint $table) {
            $table->id();
            $table->string('sigla', 20); // Ex: "ADIAVOTAC", "AGORDIA"
            $table->string('descricao'); // Ex: "Adiada discussão e votação."
            $table->char('indicador', 1)->nullable(); // Ex: "R", "F"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status_tramitacaos');
    }
};
