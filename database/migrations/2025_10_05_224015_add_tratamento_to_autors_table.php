<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('autors', function (Blueprint $table) {
            // Adiciona a nova coluna 'tratamento' depois da coluna 'nome'
            $table->string('tratamento')->nullable()->after('nome');
        });
    }
    public function down(): void {
        Schema::table('autors', function (Blueprint $table) {
            $table->dropColumn('tratamento');
        });
    }
};
