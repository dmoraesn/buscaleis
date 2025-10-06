<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('categoria_materia', function (Blueprint $table) {
            $table->foreignId('materia_id')->constrained()->onDelete('cascade');
            $table->foreignId('categoria_id')->constrained()->onDelete('cascade');
            $table->primary(['materia_id', 'categoria_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('categoria_materia');
    }
};
