<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AutorController extends Controller
{
    /**
     * Exibe a página de perfil de um autor e suas matérias.
     */
    public function show(Autor $autor): View
    {
        // Carrega as matérias do autor, com seus relacionamentos, de forma paginada
        $materias = $autor->materias()
                          ->with(['tipo', 'autores'])
                          ->latest('data_apresentacao')
                          ->paginate(15);

        return view('autores.show', [
            'autor'    => $autor,
            'materias' => $materias,
        ]);
    }
}
