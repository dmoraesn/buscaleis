<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class ViewHelper
{
    /**
     * Envolve o termo de busca no texto com tags <mark> para destaque visual.
     * Esta função é chamada pela diretiva Blade @highlight.
     *
     * @param string $text O texto onde a busca será feita (ex: ementa).
     * @param string|null $searchTerm O termo de busca a ser destacado.
     * @return string O texto com o termo destacado, se encontrado.
     */
    public static function highlightText(string $text, ?string $searchTerm = null): string
    {
        // Se não houver termo de busca, retorna o texto original sem modificação.
        if (empty($searchTerm)) {
            return $text;
        }

        // Escapa caracteres especiais no termo de busca para evitar erros no Regex.
        // O modificador 'i' torna a busca case-insensitive (não diferencia maiúsculas de minúsculas).
        $pattern = '/' . preg_quote($searchTerm, '/') . '/i';

        // Substitui o termo encontrado pela mesma palavra, mas envolvida em uma tag <mark>
        // A classe 'bg-yellow-200 rounded px-1' é do Tailwind CSS para o estilo do destaque.
        // '\0' no regex representa a string exata que foi encontrada.
        $highlightedText = preg_replace($pattern, '<mark class="bg-yellow-200 rounded px-1">\0</mark>', $text);

        // Retorna o texto com destaque ou o original se a substituição falhar.
        return $highlightedText ?? $text;
    }
}
