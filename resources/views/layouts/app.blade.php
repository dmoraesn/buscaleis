<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('description', 'BuscaLeis - Um portal de acesso à legislação municipal.')">

    <title>@yield('title', 'BuscaLeis')</title>

    {{-- Fontes e Ícones --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Tailwind CSS (via CDN) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Configuração customizada do Tailwind --}}
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] } } } }
    </script>

    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800 antialiased font-sans">

    {{-- A estrutura agora envolve todo o conteúdo da página --}}
    <div class="flex flex-col min-h-screen">
        <header class="p-6">
            <div class="container mx-auto flex justify-between items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span class="text-2xl font-semibold text-gray-800">BuscaLeis</span>
                </a>
                <nav>
                    {{-- Adicione links de navegação futuros aqui --}}
                </nav>
            </div>
        </header>

        <main class="flex-grow">
            @yield('content')
        </main>

        <footer class="p-6">
            <div class="container mx-auto text-center text-gray-500">
                &copy; {{ date('Y') }} BuscaLeis - Todos os direitos reservados.
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
