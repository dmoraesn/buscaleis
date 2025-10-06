<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Esta linha é crucial para evitar o erro 1452 durante o seeding
        Schema::disableForeignKeyConstraints();

        $this->call([
            TipoMateriaSeeder::class,
            RegimeTramitacaoSeeder::class,
            StatusTramitacaoSeeder::class,
            CategoriasSeeder::class,
            AutorSeeder::class,
            MateriaSeeder::class,
            AutoriaSeeder::class,
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
