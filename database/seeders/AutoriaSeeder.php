<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AutoriaSeeder extends Seeder
{
    public function run(): void
    {
        // Apenas inserimos, não precisamos truncar pois a tabela é simples
        // DB::table('autor_materia')->truncate();

        $json = File::get(database_path('data/autoria.json'));
        $data = json_decode($json);

        foreach ($data->results as $item) {
            // Insere a conexão na tabela pivô
            DB::table('autor_materia')->insert([
                'materia_id' => $item->materia,
                'autor_id'   => $item->autor,
            ]);
        }
    }
}
