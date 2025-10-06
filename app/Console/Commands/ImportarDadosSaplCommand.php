<?php

namespace App\Console\Commands;

use App\Models\Autor;
use App\Models\Materia;
use App\Models\RegimeTramitacao;
use App\Models\StatusTramitacao;
use App\Models\TipoMateria;
use App\Models\Tramitacao;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class ImportarDadosSaplCommand extends Command
{
    protected $signature = 'buscaleis:importar-dados';
    protected $description = 'Busca e importa os dados legislativos da API da SAPL.';

    // Base URL usada para construir/normalizar links de paginação
    protected string $baseUrl = 'https://sapl.saogoncalodoamarante.ce.leg.br/api/';

    /**
     * Array de configuração para os endpoints a serem processados.
     */
    protected array $endpoints = [
        // Tabelas de Apoio
        ['label' => 'Tipos de Matéria', 'endpoint' => 'materia/tipomaterialegislativa/', 'model' => TipoMateria::class, 'mapeamento' => ['id'=>'id', 'sigla'=>'sigla', 'descricao'=>'descricao']],
        ['label' => 'Regimes de Tramitação', 'endpoint' => 'materia/regimetramitacao/', 'model' => RegimeTramitacao::class, 'mapeamento' => ['id'=>'id', 'descricao'=>'descricao']],
        ['label' => 'Status de Tramitação', 'endpoint' => 'materia/statustramitacao/', 'model' => StatusTramitacao::class, 'mapeamento' => ['id'=>'id', 'sigla'=>'sigla', 'descricao'=>'descricao', 'indicador'=>'indicador']],
        ['label' => 'Parlamentares (Autores)', 'endpoint' => 'parlamentares/parlamentar/', 'model' => Autor::class, 'mapeamento' => ['id'=>'id', 'nome'=>'nome_completo', 'tratamento'=>'tratamento'], 'dadosFixos' => ['tipo' => 'Parlamentar']],

        // Tabela Principal
        ['label' => 'Matérias Legislativas', 'endpoint' => 'materia/materialegislativa/', 'model' => Materia::class, 'mapeamento' => [
            'id' => 'id', 'numero' => 'numero', 'ano' => 'ano', 'data_apresentacao' => 'data_apresentacao',
            'ementa' => 'ementa', 'texto_original' => 'texto_original', 'em_tramitacao' => 'em_tramitacao',
            'observacao' => 'observacao', 'resultado' => 'resultado', 'tipo_id' => 'tipo',
            'regime_tramitacao_id' => 'regime_tramitacao'
        ]],

        // Tabela Pivot (Relação N:M)
        ['label' => 'Autorias (Vínculos)', 'endpoint' => 'materia/autoria/', 'type' => 'pivot', 'tabela' => 'autor_materia', 'colunas' => ['materia_id', 'autor_id'], 'campos_api' => ['materia', 'autor']],

        // Tabela de Histórico (Tramitação) - Usar ignoreForeignKeys para evitar o erro 1452
        ['label' => 'Histórico de Tramitação', 'endpoint' => 'sessao/expedientemateria/', 'model' => Tramitacao::class, 'mapeamento' => [
            'id' => 'id', 'materia_id' => 'materia', 'data_ordem' => 'data_ordem', 'observacao' => 'observacao'
        ], 'ignoreForeignKeys' => true],
    ];

    public function handle(): int
    {
        $this->info('Iniciando a importação de dados da API SAPL...');

        // Configura o cliente HTTP com a URL base e um timeout maior para evitar cURL errors
        $http = Http::baseUrl($this->baseUrl)->timeout(120);

        foreach ($this->endpoints as $index => $config) {
            $this->newLine();
            $this->info(sprintf('%d/%d - Importando %s...', $index + 1, count($this->endpoints), $config['label']));

            // Lógica para desabilitar FOREIGN KEYs, essencial para Tramitacoes
            if ($config['ignoreForeignKeys'] ?? false) {
                Schema::disableForeignKeyConstraints();
            }

            if (($config['type'] ?? 'model') === 'model') {
                $this->processarEndpoint($http, $config);
            } else {
                $this->processarEndpointPivo($http, $config);
            }

            // Reabilita FOREIGN KEYs
            if ($config['ignoreForeignKeys'] ?? false) {
                Schema::enableForeignKeyConstraints();
            }

            $this->info(sprintf('%s importados.', $config['label']));
        }

        $this->newLine();
        $this->info('Importação concluída com sucesso!');
        return self::SUCCESS;
    }

    /**
     * Processa endpoints para Models padrão (usando updateOrCreate).
     */
    private function processarEndpoint($http, array $config): void
    {
        $nextUrl = $config['endpoint'];
        $progressBar = $this->output->createProgressBar();
        $progressBar->start();

        do {
            try {
                $response = $http->get($nextUrl);
                if (!$response->successful()) throw new \Exception("Status Code: " . $response->status());
                $data = $response->json();
                $results = $data['results'] ?? [];
            } catch (\Exception $e) {
                $this->error(" Erro ao processar {$nextUrl}: " . $e->getMessage());
                break;
            }
            if (empty($results)) break;

            if ($progressBar->getMaxSteps() === 0) {
                $progressBar->setMaxSteps($data['pagination']['total_entries'] ?? count($results));
            }

            DB::transaction(function () use ($results, $config, &$progressBar) {
                foreach ($results as $item) {
                    $attributes = [];
                    foreach ($config['mapeamento'] as $colunaBanco => $campoApi) {
                        $attributes[$colunaBanco] = $item[$campoApi] ?? null;
                    }
                    $config['model']::updateOrCreate(
                        ['id' => $attributes['id']],
                        array_merge($attributes, $config['dadosFixos'] ?? [])
                    );
                    $progressBar->advance();
                }
            });

            // Lógica de paginação: usa o link 'next' completo que a API retorna
            $nextLink = $data['pagination']['links']['next'] ?? null;
            // Remove a baseUrl da resposta para usar com Http::baseUrl
            $nextUrl = $nextLink ? substr($nextLink, strlen($this->baseUrl)) : null;

        } while ($nextUrl);

        $progressBar->finish();
        $this->output->newLine();
    }

    /**
     * Processa endpoints para Tabelas Pivot (usando insertOrIgnore).
     */
    private function processarEndpointPivo($http, array $config): void
    {
        // Limpa a tabela pivot antes de reconstruir as conexões
        DB::table($config['tabela'])->truncate();

        $nextUrl = $config['endpoint'];
        $progressBar = $this->output->createProgressBar();

        do {
            try {
                $response = $http->get($nextUrl);
                if (!$response->successful()) throw new \Exception("Status Code: " . $response->status());
                $data = $response->json();
                $results = $data['results'] ?? [];
            } catch (\Exception $e) {
                $this->error(" Erro ao processar {$nextUrl}: " . $e->getMessage());
                break;
            }
            if (empty($results)) break;

            if ($progressBar->getMaxSteps() === 0) {
                $progressBar->setMaxSteps($data['pagination']['total_entries'] ?? count($results));
            }

            $insertData = [];
            [$coluna1, $coluna2] = $config['colunas'];
            [$campoApi1, $campoApi2] = $config['campos_api'];
            foreach ($results as $item) {
                if (isset($item[$campoApi1]) && isset($item[$campoApi2])) {
                    $insertData[] = [$coluna1 => $item[$campoApi1], $coluna2 => $item[$campoApi2]];
                }
            }

            if (!empty($insertData)) {
                // Inserção em massa otimizada
                DB::table($config['tabela'])->insertOrIgnore($insertData);
            }

            $progressBar->advance(count($results));

            // Lógica de paginação: usa o link 'next' completo que a API retorna
            $nextLink = $data['pagination']['links']['next'] ?? null;
            $nextUrl = $nextLink ? substr($nextLink, strlen($this->baseUrl)) : null;

        } while ($nextUrl);

        $progressBar->finish();
        $this->output->newLine();
    }
}
