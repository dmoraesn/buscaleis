<?php

use App\Http\Controllers\MateriaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutorController; // Importe o novo controller

Route::get('/', [MateriaController::class, 'index'])->name('home');
Route::get('/materias/tipos', [MateriaController::class, 'listarTipos'])->name('materias.tipos');
Route::get('/materias', [MateriaController::class, 'listarMaterias'])->name('materias.lista');

// NOVA ROTA: Exibe uma matéria específica. {materia} é um wildcard.
Route::get('/materias/{materia}', [MateriaController::class, 'mostrarMateria'])->name('materias.show');

Route::get('/autores/{autor}', [AutorController::class, 'show'])->name('autores.show');
