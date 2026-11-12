<?php

use App\Http\Controllers\Api\PlanetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Toutes les routes de ce fichier sont automatiquement préfixées par /api
// (configuré dans bootstrap/app.php)

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/ping', function () {
    return response()->json(['status' => 'ok']);
});

// --- Planètes ---

// Liste de toutes les planètes
Route::get('/planets', [PlanetController::class, 'index']);

// Une seule planète, à partir de son index dans le jeu (ex. /api/planets/1).
// {planet:game_index} demande à Laravel de chercher dans la colonne game_index
// plutôt que dans l'id, puis de passer la planète trouvée au contrôleur.
// Si aucune planète ne correspond, Laravel renvoie une 404.
Route::get('/planets/{planet:game_index}', [PlanetController::class, 'show']);
