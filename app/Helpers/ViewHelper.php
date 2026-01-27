<?php

namespace App\Helpers;

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
        $sanitizedText = e($text);

        // Se não houver termo de busca, retorna o texto original sem modificação.
        if (empty($searchTerm)) {
            return $sanitizedText;
        }

        // Divide o termo de busca em palavras relevantes (ignorando espaços extras).
        $terms = array_unique(array_filter(preg_split('/\s+/u', trim($searchTerm))));

        if (empty($terms)) {
            return $sanitizedText;
        }

        // Monta uma expressão regular que considera todas as palavras buscadas (case insensitive).
        $escapedTerms = array_map(fn ($term) => preg_quote($term, '/'), $terms);
        $pattern = '/(' . implode('|', $escapedTerms) . ')/iu';

        // Substitui cada ocorrência das palavras encontradas pela mesma palavra envolvida na tag <mark>.
        $highlightedText = preg_replace_callback(
            $pattern,
            fn ($matches) => '<mark class="bg-yellow-200 rounded px-1">' . $matches[0] . '</mark>',
            $sanitizedText
        );

        // Retorna o texto com destaque ou o original se a substituição falhar.
        return $highlightedText ?? $sanitizedText;
    }
}
