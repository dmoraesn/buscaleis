<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\Helpers\ViewHelper; // Importa o Helper

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registra a diretiva Blade '@highlight' para o destaque de palavras-chave.
        Blade::directive('highlight', function ($expression) {
            // A expressão será algo como "$texto, $termoDeBusca"
            // A diretiva compila para uma chamada estática ao nosso ViewHelper.
            return "<?php echo \\App\\Helpers\\ViewHelper::highlightText({$expression}); ?>";
        });
    }
}
