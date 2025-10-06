<?php

namespace Database\Seeders;

use App\Models\Autor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AutorSeeder extends Seeder
{
    public function run(): void
    {
        // Se já existirem, não tentamos re-inserir. O importador tratará os IDs.
        if (Autor::count() > 0) {
            return;
        }

        // Criando autores baseados nos IDs dos arquivos JSON que são cruciais para a consistência
        Autor::create(['id' => 7, 'nome' => 'Ailson de Almeida', 'tipo' => 'Vereador']);
        Autor::create(['id' => 26, 'nome' => 'Mesa Diretora', 'tipo' => 'Mesa Diretora']);
        Autor::create(['id' => 37, 'nome' => 'Poder Executivo', 'tipo' => 'Executivo']);
        Autor::create(['id' => 51, 'nome' => 'Comissão Especial', 'tipo' => 'Comissão']);
    }
}
