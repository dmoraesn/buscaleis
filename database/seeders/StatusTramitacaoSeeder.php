<?php

namespace Database\Seeders;

use App\Models\StatusTramitacao;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class StatusTramitacaoSeeder extends Seeder
{
    public function run(): void
    {
        if (StatusTramitacao::count() > 0) {
            return;
        }

        $json = File::get(database_path('data/statustramitacao.json'));
        $data = json_decode($json, true);

        foreach ($data['results'] as $item) {
            StatusTramitacao::create([
                'id'        => $item['id'],
                'sigla'     => $item['sigla'] ?? null,
                'descricao' => $item['descricao'],
                'indicador' => $item['indicador'] ?? null,
            ]);
        }
    }
}
