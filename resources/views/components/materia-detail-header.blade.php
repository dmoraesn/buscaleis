@props(['materia'])

<div class="mb-8">
    <a href="{{ route('materias.lista', request()->query()) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors mb-4">
        <i class="fas fa-arrow-left mr-2"></i>
        Voltar para os resultados
    </a>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                {{ optional($materia->tipo)->descricao }} Nº {{ $materia->numero }}/{{ $materia->ano }}
            </h1>
            <p class="text-md text-gray-500 mt-1">
                Apresentada em {{ $materia->data_apresentacao->format('d \d\e F \d\e Y') }}
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            @if($materia->em_tramitacao)
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                    <i class="fas fa-hourglass-half mr-2"></i> Em Tramitação
                </span>
            @else
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-2"></i> Tramitação Encerrada
                </span>
            @endif
        </div>
    </div>
</div>
