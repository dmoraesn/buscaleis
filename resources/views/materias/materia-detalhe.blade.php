@extends('layouts.app')

@section('title', "Matéria Nº {$materia->numero}/{$materia->ano}")

@push('styles')
<style>
    /* Estilos para a timeline de tramitação */
    .timeline-item { position: relative; padding-bottom: 2rem; padding-left: 2.5rem; border-left: 2px solid #e5e7eb; }
    .timeline-item:last-child { border-left: 2px solid transparent; padding-bottom: 0; }
    .timeline-icon { position: absolute; left: -13px; top: 0; display: flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 50%; }
</style>
@endpush

@section('content')
<div class="container mx-auto p-4 md:p-8 max-w-5xl">
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-8">
            <x-materia-detail-header :materia="$materia" />

            {{-- Secção de Ementa e Categorias --}}
            <div class="border-t border-gray-200 pt-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-3">Ementa</h2>
                <p class="text-gray-600 leading-relaxed">
                    {{-- Lógica de destaque aplicada aqui --}}
                    @php
                        $highlight = function ($text, $term) {
                            if (!$term) return $text;
                            $pattern = '/' . preg_quote($term, '/') . '/i';
                            return preg_replace($pattern, '<mark class="bg-yellow-200 rounded px-1 py-0.5">\0</mark>', $text);
                        };
                    @endphp
                    {!! $highlight($materia->ementa, $searchTerm) !!}
                </p>

                @if($materia->categorias->isNotEmpty())
                    <h3 class="font-semibold text-gray-700 mb-2 mt-4">Categorias (Análise de IA):</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($materia->categorias as $categoria)
                            <x-category-tag :categoria="$categoria" />
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 mb-8 bg-gray-50 p-6 rounded-lg border border-gray-200">
                <x-detail-item label="Autor(es)">
                    @forelse($materia->autores as $autor)
                        <a href="{{ route('autores.show', $autor) }}" class="block hover:text-blue-600 hover:underline">{{ $autor->nome }}</a>
                    @empty
                        <span>Não informado</span>
                    @endforelse
                </x-detail-item>

                <x-detail-item label="Regime de Tramitação">
                    {{ optional($materia->regimeTramitacao)->descricao ?? 'Não informado' }}
                </x-detail-item>
            </div>

            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Histórico de Tramitação</h2>
                <div class="flow-root">
                    @forelse($materia->tramitacoes as $tramitacao)
                        <x-timeline-item :tramitacao="$tramitacao" />
                    @empty
                        <div class="timeline-item">
                            <div class="timeline-icon bg-gray-400 text-white"><i class="fas fa-file-import"></i></div>
                            <h3 class="font-semibold text-gray-800">Recebimento da Matéria</h3>
                            <p class="text-sm text-gray-500 mb-1">{{ $materia->data_apresentacao->format('d \d\e F \d\e Y') }}</p>
                            <p class="text-gray-600">Nenhum histórico de tramitação detalhado foi encontrado para esta matéria.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        @if($materia->texto_original)
        <div class="bg-gray-50 px-8 py-5 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Documentos</h3>
            <a href="{{ $materia->texto_original }}" target="_blank" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors w-full sm:w-auto">
                <i class="fas fa-file-pdf mr-2"></i>
                Visualizar Documento Original (PDF)
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
