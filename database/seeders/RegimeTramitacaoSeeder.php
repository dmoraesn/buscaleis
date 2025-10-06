<?php

namespace Database\Seeders;

use App\Models\RegimeTramitacao;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class RegimeTramitacaoSeeder extends Seeder
{
    public function run(): void
    {
        if (RegimeTramitacao::count() > 0) {
            return;
        }

        $json = File::get(database_path('data/regimetramitacao.json'));
        $data = json_decode($json, true);

        foreach ($data['results'] as $item) {
            RegimeTramitacao::create([
                'id'        => $item['id'],
                'descricao' => $item['descricao'],
            ]);
        }
    }
}
