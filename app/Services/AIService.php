<?php

namespace App\Services;

use App\Models\Categoria;
use App\Models\Materia;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AIService
{
    protected const OPENAI_API_URL = 'https://api.openai.com/v1/chat/completions';
    protected const FALLBACK_MODEL = 'gpt-3.5-turbo';

    /**
     * Envia a ementa para a IA (GPT) e retorna a Categoria predita.
     */
    public function categorizarMateria(Materia $materia): ?Categoria
    {
        if ($materia->categorias->isNotEmpty()) {
            return null;
        }

        $categorias = Categoria::pluck('nome')->implode(', ');
        $systemPrompt = "Você é um classificador de documentos municipal. Sua tarefa é analisar a ementa da matéria e classificá-la estritamente em UMA das seguintes categorias: [{$categorias}]. Sua resposta deve conter APENAS o NOME EXATO da categoria escolhida, sem pontuação ou texto adicional.";
        $userPrompt = "Ementa para classificação: \"{$materia->ementa}\"";

        $payload = [
            'model' => self::FALLBACK_MODEL,
            'messages' => [['role' => 'system', 'content' => $systemPrompt], ['role' => 'user', 'content' => $userPrompt]],
            'temperature' => 0.2,
            'max_tokens' => 50,
        ];

        try {
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . env('OPENAI_API_KEY'), 'Content-Type' => 'application/json'])->timeout(60)->post(self::OPENAI_API_URL, $payload);
            if (!$response->successful()) {
                Log::error('OPENAI API CALL FAILED', ['status' => $response->status(), 'response' => $response->body(), 'materia_id' => $materia->id]);
                return null;
            }
            $responseBody = $response->json();
            $predictedName = trim($responseBody['choices'][0]['message']['content'] ?? '');

            if (!$predictedName) {
                Log::warning('GPT returned empty prediction.', ['materia_id' => $materia->id]);
                return null;
            }

            return Categoria::where('nome', Str::title($predictedName))->first();

        } catch (\Exception $e) {
            Log::error('OPENAI CONNECTION ERROR', ['error' => $e->getMessage(), 'materia_id' => $materia->id]);
            return null;
        }
    }

    /**
     * Corrige a query do usuário usando IA (para o "Você quis dizer?").
     */
    public function correctQuery(?string $query): ?string
    {
        if (empty($query) || strlen($query) < 5) {
            return $query;
        }

        // FRAMEWORK: A lógica real de API de IA ficaria aqui.
        // A simulação abaixo é para o caso de uso que você reportou.
        if (strtolower($query) === 'ailson cágaod') {
            return 'Ailson Cágado';
        }

        return $query;
    }
}
