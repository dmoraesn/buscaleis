@props(['tramitacao'])

<div class="timeline-item">
    <div class="timeline-icon bg-blue-500 text-white">
        {{-- Ícone padrão para históricos de tramitação --}}
        <i class="fas fa-history"></i>
    </div>
    {{-- A observação é o título da tramitação --}}
    <h3 class="font-semibold text-gray-800">{{ $tramitacao->observacao }}</h3>
    {{-- Data de registro da tramitação --}}
    <p class="text-sm text-gray-500 mb-1">{{ $tramitacao->data_ordem->format('d \d\e F \d\e Y') }}</p>
</div>
