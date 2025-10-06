<?php

namespace Database\Seeders;

use App\Models\TipoMateria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class TipoMateriaSeeder extends Seeder
{
    public function run(): void
    {
        // Se a tabela já tiver dados, não faz nada (deixa o importador cuidar)
        if (TipoMateria::count() > 0) {
            return;
        }

        // Se for um ambiente local, insere o JSON inicial como fallback
        $json = File::get(database_path('data/tipomaterialegislativa.json'));
        $data = json_decode($json, true);

        // O 'results' do JSON é um array de arrays
        foreach ($data['results'] as $item) {
            TipoMateria::create([
                'id'        => $item['id'],
                'sigla'     => $item['sigla'] ?? null,
                'descricao' => $item['descricao'],
            ]);
        }
    }
}
