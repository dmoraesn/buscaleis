@extends('layouts.app')

@section('title', 'Matérias Legislativas')

@section('content')
<div class="container mx-auto p-8">
    <h1 class="text-3xl font-bold text-gray-700 mb-6">
        Matérias Legislativas
    </h1>

    <div class="bg-white rounded-lg shadow-md">
        <ul class="divide-y divide-gray-200">
            @forelse($materias as $materia)
                <li class="p-6 hover:bg-gray-50">
                    <div class="flex justify-between items-center mb-2">
                        {{-- Usando os relacionamentos que definimos! --}}
                        <span class="text-sm font-semibold text-blue-800">
                            {{ optional($materia->tipo)->descricao ?? 'Tipo não definido' }}
                        </span>
                        <span class="text-sm text-gray-500">
                            Apresentada em: {{ \Carbon\Carbon::parse($materia->data_apresentacao)->format('d/m/Y') }}
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                       Nº {{ $materia->numero }}/{{ $materia->ano }}
                    </h3>
                    <p class="text-gray-600 text-sm">
                       {{ $materia->ementa }}
                    </p>
                </li>
            @empty
                <li class="p-6 text-center text-gray-500">
                    Nenhuma matéria legislativa encontrada.
                </li>
            @endforelse
        </ul>

        {{-- Links de Paginação --}}
        <div class="p-6 border-t border-gray-200">
            {{ $materias->links() }}
        </div>
    </div>
</div>
@endsection
