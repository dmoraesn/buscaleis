@php
    // --- Bloco de Preparação de Dados ---
    $searchTerm = request('q');
    $tipoDescricao = optional($materia->tipo)->descricao ?? 'Tipo não informado';
    $tipoSlug = Str::slug($tipoDescricao);
    $autor = $materia->autores->first();
    $dataApresentacao = optional($materia->data_apresentacao)->format('d/m/Y') ?? 'Data indisponível';
    $regime = optional($materia->regimeTramitacao)->descricao ?? 'Não especificado';

    $bgColorClass = $materia->em_tramitacao ? 'bg-yellow-100' : 'bg-green-100';
    $iconColorClass = $materia->em_tramitacao ? 'text-yellow-700' : 'text-green-700';
    $statusDotClass = $materia->em_tramitacao ? 'bg-blue-500' : 'bg-gray-400';
    $statusText = $materia->em_tramitacao ? 'Em Tramitação' : 'Tramitação Encerrada';

    // Lógica do Ícone SVG
    $iconSvg = match (true) {
        Str::contains($tipoSlug, ['projeto-de-lei', 'lei-ordinaria']) => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>',
        Str::contains($tipoSlug, 'indicacao') => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-3.375M10.5 2.25h1.5m-4.5 0h1.5m-4.5 0h1.5M3 16.5v-12A1.5 1.5 0 014.5 3h15A1.5 1.5 0 0121 4.5v12a1.5 1.5 0 01-1.5 1.5h-15A1.5 1.5 0 013 16.5z" /></svg>',
        Str::contains($tipoSlug, 'decreto') => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        default => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>',
    };
@endphp

<div class="bg-white p-5 rounded-xl border border-gray-200 hover:shadow-lg hover:border-blue-500 transition-all duration-300 flex flex-col sm:flex-row items-start gap-5">

    <div class="flex-shrink-0">
        <div class="w-14 h-14 rounded-full flex items-center justify-center {{ $bgColorClass }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 {{ $iconColorClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                {!! $iconSvg !!}
            </svg>
        </div>
    </div>

    <div class="flex-grow">

        {{-- Tags de Categoria da IA (DESTAQUE) --}}
        @if($materia->categorias->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-2">
                @foreach($materia->categorias as $categoria)
                    <x-category-tag :categoria="$categoria" />
                @endforeach
            </div>
        @endif

        <a href="{{ route('materias.show', $materia) }}" class="block">
            <h2 class="text-xl font-bold text-blue-800 hover:underline mb-2">
                {{ $tipoDescricao }} Nº {{ $materia->numero }}/{{ $materia->ano }}
            </h2>
        </a>

        <p class="text-gray-600 leading-relaxed mb-4">
            {{-- Função PHP inline para HIGHLIGHT --}}
            @php
                $highlight = function ($text, $term) {
                    if (!$term) return $text;
                    $pattern = '/' . preg_quote($term, '/') . '/i';
                    return preg_replace($pattern, '<mark class="bg-yellow-300 rounded px-1">\0</mark>', $text);
                };
            @endphp
            {!! $highlight(Str::limit($materia->ementa, 150), $searchTerm) !!}
        </p>

        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-gray-500">

            {{-- Situação --}}
            <div class="flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full {{ $statusDotClass }}"></span>
                <span>{{ $statusText }}</span>
            </div>
            <span class="hidden sm:inline">|</span>

            {{-- Autor --}}
            @if($autor)
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    <span>Autor: <a href="{{ route('autores.show', $autor) }}" class="ml-1 font-medium text-gray-700 hover:text-blue-600 hover:underline">{{ $autor->nome }}</a></span>
                </div>
            @endif
            <span class="hidden sm:inline">|</span>

            {{-- Data --}}
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                <span>Apresentada em {{ $dataApresentacao }}</span>
            </div>
        </div>
    </div>
</div>
