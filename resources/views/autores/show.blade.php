@extends('layouts.app')

@section('title', "Matérias de {$autor->nome}")

@section('content')
<div class="container mx-auto p-4 md:p-8 max-w-7xl">
    {{-- Cabeçalho da Página do Autor --}}
    <header class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">{{ $autor->nome }}</h1>
        <p class="text-lg text-gray-500">{{ $autor->tratamento ?? $autor->tipo }}</p>
    </header>

    {{-- Lista de Matérias do Autor --}}
    <main>
        <h2 class="text-2xl font-semibold text-gray-700 mb-6">Matérias de autoria:</h2>
        <div class="space-y-6">
            {{-- Reutilizamos o mesmo "partial" da página de busca! --}}
            @forelse ($materias as $materia)
                @include('materias._materia-search-item', ['materia' => $materia])
            @empty
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 text-center">
                    <p class="text-gray-500">Nenhuma matéria encontrada para este autor.</p>
                </div>
            @endforelse

            <nav class="pt-6">
                {{ $materias->links() }}
            </nav>
        </div>
    </main>
</div>
@endsection
