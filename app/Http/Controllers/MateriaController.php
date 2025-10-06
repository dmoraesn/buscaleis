<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\TipoMateria;
use App\Models\Categoria;
use App\Services\AIService;
use Illuminate\Http\Request; // Importe a classe Request
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MateriaController extends Controller
{
    public function index(): View
    {
        $categorias = Categoria::orderBy('nome')->get();
        return view('welcome', ['categorias' => $categorias]);
    }

    public function listarMaterias(Request $request, AIService $aiService): View
    {
        $originalSearchTerm = $request->q;
        $categorySlug = $request->cat;

        $correctedSearchTerm = $aiService->correctQuery($originalSearchTerm ?? '');
        $searchTerm = $correctedSearchTerm;

        $materias = Materia::with(['tipo', 'regimeTramitacao', 'autores', 'categorias'])
                           ->search($searchTerm)
                           ->filterByCategorySlug($categorySlug)
                           ->filterBySituacao($request->situacao)
                           ->filterByTipo($request->tipo)
                           ->filterByAno($request->ano)
                           ->latest('data_apresentacao')
                           ->paginate(15)
                           ->withQueryString();

        $tipos = TipoMateria::has('materias')->orderBy('descricao')->get();
        $anos = Materia::select(DB::raw('DISTINCT YEAR(data_apresentacao) as ano'))
                       ->whereNotNull('data_apresentacao')->orderBy('ano', 'desc')->pluck('ano');
        $categorias = Categoria::orderBy('nome')->get();

        return view('materias.materias-lista', [
            'materias'   => $materias,
            'searchTerm' => $searchTerm,
            'originalSearchTerm' => $originalSearchTerm,
            'tipos'      => $tipos,
            'anos'       => $anos,
            'categorias' => $categorias,
        ]);
    }

    /**
     * Exibe a página de detalhes de uma matéria específica.
     */
    public function mostrarMateria(Materia $materia, Request $request): View // Adicionado Request
    {
        $materia->load(['tipo', 'regimeTramitacao', 'autores', 'tramitacoes', 'categorias']);

        return view('materias.materia-detalhe', [
            'materia' => $materia,
            'searchTerm' => $request->q, // Passa o termo de busca para a view
        ]);
    }
}
