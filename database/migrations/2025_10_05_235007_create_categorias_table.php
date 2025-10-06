<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique(); // Ex: "Saúde", "Educação"
            $table->string('slug')->unique(); // Ex: "saude", "educacao"
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('categorias');
    }
};
