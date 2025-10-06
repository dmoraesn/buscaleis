@props(['categoria'])

@php
    // Usa o slug para garantir que a comparação seja consistente (sem acentos ou espaços)
    $slug = Str::slug($categoria->nome);
    $icon = 'fas fa-tag'; // Fallback Padrão
    $color = 'bg-gray-100 text-gray-700';

    // --- Lógica de Mapeamento de Cores e Ícones ---

    if (Str::contains($slug, ['saude', 'saude-publica'])) {
        $icon = 'fas fa-notes-medical'; $color = 'bg-red-100 text-red-800';
    } elseif (Str::contains($slug, ['urbanismo', 'obras', 'habitacao'])) {
        $icon = 'fas fa-helmet-safety'; $color = 'bg-yellow-100 text-yellow-800';
    } elseif (Str::contains($slug, ['tributario', 'financas'])) {
        $icon = 'fas fa-file-invoice-dollar'; $color = 'bg-green-100 text-green-800';
    } elseif (Str::contains($slug, ['educacao', 'cultura'])) {
        $icon = 'fas fa-graduation-cap'; $color = 'bg-blue-100 text-blue-800';
    } elseif (Str::contains($slug, ['meio-ambiente', 'ambiental'])) {
        $icon = 'fas fa-leaf'; $color = 'bg-teal-100 text-teal-800';
    } elseif (Str::contains($slug, ['seguranca', 'transito'])) {
        $icon = 'fas fa-shield-halved'; $color = 'bg-indigo-100 text-indigo-800';
    } elseif (Str::contains($slug, ['direitos', 'cidadania', 'social'])) {
        $icon = 'fas fa-scale-balanced'; $color = 'bg-purple-100 text-purple-800';
    }
@endphp

<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $color }}">
    <i class="{{ $icon }} mr-2"></i>
    {{ $categoria->nome }}
</span>
