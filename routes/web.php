<?php

use App\Http\Controllers\PlanetController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Liste des planètes (route nommée : planets.index)
Route::get('/planets', [PlanetController::class, 'index'])->name('planets.index');

// Détail d'une planète, {planet} est l'id de la planète (route nommée : planets.show)
Route::get('/planets/{planet}', [PlanetController::class, 'show'])->name('planets.show');
