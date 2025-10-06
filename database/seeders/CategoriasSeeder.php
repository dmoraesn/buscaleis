<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriasSeeder extends Seeder
{
    protected array $categoriasPadrao = [
        'Saúde Pública', 'Educação e Cultura', 'Urbanismo e Obras',
        'Tributário e Finanças', 'Meio Ambiente', 'Segurança e Trânsito',
        'Serviços Públicos', 'Direitos Humanos e Cidadania'
    ];

    public function run(): void
    {
        // TRUNCATE MANTIDO: Essencial para garantir a lista exata de categorias de destino para a IA.
        DB::table('categorias')->truncate();

        $data = [];
        foreach ($this->categoriasPadrao as $nome) {
            $data[] = [
                'nome' => $nome,
                'slug' => Str::slug($nome),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Categoria::insert($data);
    }
}
