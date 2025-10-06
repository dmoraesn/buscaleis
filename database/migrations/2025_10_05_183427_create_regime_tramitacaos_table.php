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
        // Baseado em regimetramitacao.json
        Schema::create('regime_tramitacaos', function (Blueprint $table) {
            $table->id();
            $table->string('descricao'); // Ex: "Ordinária", "Urgência"
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
        Schema::dropIfExists('regime_tramitacaos');
    }
};
