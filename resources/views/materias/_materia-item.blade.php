<a href="{{ route('materias.show', $materia) }}" class="block">
    <li class="p-6 hover:bg-gray-50 transition-colors duration-200">
        <div class="flex justify-between items-center mb-2">
            <span class="text-sm font-semibold text-blue-800">
                {{ optional($materia->tipo)->descricao ?? 'Tipo não definido' }}
            </span>
            <span class="text-sm text-gray-500">
                Apresentada em: {{ $materia->data_apresentacao->format('d/m/Y') }}
            </span>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">
            Nº {{ $materia->numero }}/{{ $materia->ano }}
        </h3>

        {{-- NOVO: Mostrando o nome do primeiro autor --}}
        @if($materia->autores->isNotEmpty())
            <p class="text-sm text-gray-500 mb-3">
                Autor: <span class="font-medium">{{ $materia->autores->first()->nome }}</span>
            </p>
        @endif

        <p class="text-gray-600 text-sm leading-relaxed">
            {{ $materia->ementa }}
        </p>
    </li>
</a>
