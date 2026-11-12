<?php

use App\Http\Controllers\PlanetController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Liste des planètes (route nommée : planets.index)
Route::get('/planets', [PlanetController::class, 'index'])->name('planets.index');

// Détail d'une planète, {planet} est le numéro de la planète dans le jeu (game_index)
Route::get('/planets/{planet:game_index}', [PlanetController::class, 'show'])->name('planets.show');
