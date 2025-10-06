@extends('layouts.app')

@section('title', 'BuscaLeis - Encontre Leis e Decretos Facilmente')

@section('content')
<div class="flex items-center justify-center py-12">
    <div class="container mx-auto text-center px-6">
        <h1 class="text-4xl md:text-5xl font-light text-gray-800 mb-3">
            Busque a legislação da sua cidade.
        </h1>
        <p class="text-lg text-gray-500 max-w-2xl mx-auto mb-10">
            Use a barra de busca para encontrar leis, projetos e decretos municipais de forma simples e segura.
        </p>

        <div class="max-w-3xl mx-auto">
            <form action="{{ route('materias.lista') }}" method="get" class="relative flex flex-col sm:flex-row gap-3">
                <div class="relative flex-grow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input
                        type="text"
                        name="q"
                        id="searchInput"
                        class="w-full p-5 pl-12 border border-gray-300 rounded-full shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-lg"
                        placeholder="">
                </div>
                <button type="submit" class="bg-blue-600 text-white font-semibold py-4 px-8 rounded-full hover:bg-blue-700 transition-colors duration-300 shadow-md">
                    Pesquisar
                </button>
            </form>
        </div>

        @if($categorias->isNotEmpty())
        <div class="mt-12">
            <p class="text-gray-500 mb-4">Ou explore por categorias principais:</p>
            <div class="flex justify-center flex-wrap gap-3">
                @foreach($categorias as $categoria)
                    <a href="{{ route('materias.lista', ['cat' => $categoria->slug]) }}" class="no-underline">
                        <x-category-tag :categoria="$categoria" />
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
{{-- Script do placeholder animado --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            const placeholders = [
                'pesquise por leis...', 'saiba quais indicações foram aprovadas...', 'projetos de lei sobre meio ambiente...',
                'leis sobre o IPTU...', 'quem propôs a lei de incentivo fiscal...', 'matérias do vereador José Almeida...'
            ];
            let placeholderIndex = 0, charIndex = 0, isDeleting = false;
            const typingSpeed = 100, deletingSpeed = 50, delayAtEnd = 2000;

            function typePlaceholder() {
                const currentPlaceholder = placeholders[placeholderIndex];
                let displayText = isDeleting
                    ? currentPlaceholder.substring(0, charIndex - 1)
                    : currentPlaceholder.substring(0, charIndex + 1);

                charIndex += isDeleting ? -1 : 1;
                searchInput.setAttribute('placeholder', displayText + '|');

                let timeout = isDeleting ? deletingSpeed : typingSpeed;

                if (!isDeleting && charIndex === currentPlaceholder.length) {
                    timeout = delayAtEnd;
                    isDeleting = true;
                } else if (isDeleting && charIndex === 0) {
                    isDeleting = false;
                    placeholderIndex = (placeholderIndex + 1) % placeholders.length;
                }
                setTimeout(typePlaceholder, timeout);
            }
            typePlaceholder();
        }
    });
</script>
@endpush

@push('styles')
<style>
    #searchInput::placeholder {
        color: #9ca3af;
        animation: blink 1s step-end infinite;
    }
    @keyframes blink {
        from, to { border-right: 2px solid transparent; }
        50% { border-right: 2px solid #9ca3af; }
    }
</style>
@endpush
