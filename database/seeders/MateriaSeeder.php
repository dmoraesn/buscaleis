<?php

namespace Database\Seeders;

use App\Models\Materia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class MateriaSeeder extends Seeder
{
    public function run(): void
    {
        Materia::truncate();

        // Limpa a tabela pivô aqui também, para garantir a ordem
        \Illuminate\Support\Facades\DB::table('autor_materia')->truncate();

        $json = File::get(database_path('data/materialegislativa.json'));
        $data = json_decode($json);

        foreach ($data->results as $item) {
            Materia::create([
                'id'                    => $item->id,
                'numero'                => $item->numero,
                'ano'                   => $item->ano,
                'data_apresentacao'     => $item->data_apresentacao,
                'ementa'                => $item->ementa,
                'texto_original'        => $item->texto_original,
                'em_tramitacao'         => $item->em_tramitacao,
                'observacao'            => $item->observacao ?? null,
                'resultado'             => $item->resultado ?? null,
                'tipo_id'               => $item->tipo,
                'regime_tramitacao_id'  => $item->regime_tramitacao,
            ]);
        }
    }
}
