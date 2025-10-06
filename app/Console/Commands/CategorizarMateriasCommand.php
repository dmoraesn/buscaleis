<?php

namespace App\Console\Commands;

use App\Models\Materia;
use App\Services\AIService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CategorizarMateriasCommand extends Command
{
    protected $signature = 'buscaleis:categorizar';
    protected $description = 'Categoriza matérias legislativas usando Inteligência Artificial (AI).';

    public function handle(AIService $aiService): int
    {
        $this->info('Iniciando categorização de matérias com IA...');

        // Busca todas as matérias que ainda não foram categorizadas
        $materias = Materia::doesntHave('categorias')->get();
        $total = $materias->count();

        if ($total === 0) {
            $this->comment('Todas as matérias já estão categorizadas. Encerrando.');
            return self::SUCCESS;
        }

        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        foreach ($materias as $materia) {
            $categoria = $aiService->categorizarMateria($materia);

            if ($categoria) {
                DB::transaction(function () use ($materia, $categoria) {
                    // Anexa a categoria usando a relação muitos-para-muitos
                    $materia->categorias()->attach($categoria->id);
                });
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->info("{$total} matérias processadas. Categorização concluída!");

        return self::SUCCESS;
    }
}
