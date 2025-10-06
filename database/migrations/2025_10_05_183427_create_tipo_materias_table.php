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
        // Baseado em tipomaterialegislativa.json
        Schema::create('tipo_materias', function (Blueprint $table) {
            $table->id();
            $table->string('sigla', 20); // Ex: "REC", "REQUR"
            $table->string('descricao'); // Ex: "Recurso", "Requerimento de Urgência Especial"
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
        Schema::dropIfExists('tipo_materias');
    }
};
