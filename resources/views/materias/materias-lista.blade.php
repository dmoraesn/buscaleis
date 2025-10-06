@extends('layouts.app')

@section('title', 'Resultados da Busca')

@push('styles')
<style>
    /* Estilos do status dot */
    .status-dot { height: 8px; width: 8px; border-radius: 50%; display: inline-block; margin-right: 6px; }
    .status-encerrada { background-color: #10B981; } /* green-500 */
    .status-tramitacao { background-color: #F59E0B; } /* amber-500 */

    /* Estilos para a lista de filtros expansível (Show More/Less) */
    #tipo-filter-list {
        transition: max-height 0.5s ease-in-out;
        overflow-y: hidden;
    }
    /* Classe padrão que limita a altura e é removida pelo JavaScript ao expandir */
    #tipo-filter-list.collapsed {
        max-height: 120px; /* Altura para mostrar ~4 itens */
    }
</style>
@endpush

@section('content')
<div class="container mx-auto p-4 md:p-8 max-w-7xl">

    {{-- A barra de busca no topo (integrada do novo layout) --}}
    <header class="mb-8">
        <a href="{{ route('home') }}" class="flex items-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
            <h1 class="text-2xl font-bold text-gray-700">Portal Legislativo</h1>
        </a>
        <div class="relative">
            <form action="{{ route('materias.lista') }}" method="GET">
                <input type="text" name="q" class="w-full p-4 pl-12 border border-gray-300 rounded-full shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" value="{{ $searchTerm ?? '' }}" placeholder="Pesquisar por matéria, ementa ou autor...">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </form>
        </div>
        @if(isset($searchTerm) && $searchTerm)
            <p class="text-sm text-gray-500 mt-3 ml-2">{{ $materias->total() }} resultados encontrados para "<strong>{{ $searchTerm }}</strong>"</p>
        @endif
    </header>

    <div class="flex flex-col md:flex-row gap-8">

        <aside class="w-full md:w-1/4">
            {{-- Formulário que envia os filtros --}}
            <form action="{{ route('materias.lista') }}" method="GET" id="filter-form">
                <input type="hidden" name="q" value="{{ $searchTerm ?? '' }}">

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex justify-between items-center border-b pb-3 mb-6">
                        <h2 class="text-lg font-semibold">Filtros</h2>
                        <button type="submit" class="text-sm bg-blue-600 text-white font-semibold py-1 px-3 rounded-md hover:bg-blue-700">Aplicar</button>
                    </div>

                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-700 mb-3">Situação</h3>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                {{-- Usa request('situacao', []) para manter o estado --}}
                                <input type="checkbox" name="situacao[]" value="em_tramitacao" {{ in_array('em_tramitacao', request('situacao', [])) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-gray-600">Em Tramitação</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="situacao[]" value="encerrada" {{ in_array('encerrada', request('situacao', [])) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-gray-600">Tramitação Encerrada</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-700 mb-3">Tipo de Matéria</h3>
                        <div class="space-y-2 collapsed" id="tipo-filter-list">
                            @foreach($tipos as $tipo)
                                <label class="flex items-center">
                                    <input type="checkbox" name="tipo[]" value="{{ $tipo->id }}" {{ in_array($tipo->id, request('tipo', [])) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-gray-600">{{ $tipo->descricao }}</span>
                                </label>
                            @endforeach
                        </div>
                        @if($tipos->count() > 4)
                            <button type="button" id="toggle-tipos" class="text-blue-600 hover:underline text-sm mt-2">Mais opções...</button>
                        @endif
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-700 mb-3">Ano de Apresentação</h3>
                        <select name="ano" class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos os anos</option>
                            @foreach($anos as $ano)
                                <option value="{{ $ano }}" {{ request('ano') == $ano ? 'selected' : '' }}>{{ $ano }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </aside>

        <main class="w-full md:w-3/4">
            <div class="space-y-6">
                @forelse ($materias as $materia)
                    {{-- Usa o partial do card de resultado --}}
                    @include('materias._materia-search-item', ['materia' => $materia])
                @empty
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 text-center">
                        <p class="text-gray-500">Nenhum resultado encontrado para a sua busca ou filtros aplicados.</p>
                    </div>
                @endforelse

                <nav class="pt-6">
                    {{-- Contador de resultados e links de paginação --}}
                    <div class="flex justify-between items-center mb-4 text-sm text-gray-600">
                        <div>
                            Mostrando de <strong>{{ $materias->firstItem() }}</strong> a <strong>{{ $materias->lastItem() }}</strong> de <strong>{{ $materias->total() }}</strong> resultados
                        </div>
                    </div>
                    {{ $materias->appends(request()->query())->links() }}
                </nav>
            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.getElementById('toggle-tipos');
        const filterList = document.getElementById('tipo-filter-list');

        if (toggleButton && filterList) {
            // Inicializa o estado de expansão baseado nos filtros ativos
            let isExpanded = filterList.querySelectorAll('input:checked').length > 4;

            // Define o estado inicial da classe CSS
            if (!isExpanded) {
                filterList.classList.add('collapsed');
            }
            toggleButton.textContent = isExpanded ? 'Menos opções...' : 'Mais opções...';

            toggleButton.addEventListener('click', function() {
                isExpanded = !isExpanded;

                // Alterna a classe 'collapsed' que define a altura máxima
                filterList.classList.toggle('collapsed');

                // Atualiza o texto do botão
                this.textContent = isExpanded ? 'Menos opções...' : 'Mais opções...';
            });
        }
    });
</script>
@endpush
