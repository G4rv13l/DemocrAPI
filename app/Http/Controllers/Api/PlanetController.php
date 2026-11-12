<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlanetResource;
use App\Models\Planet;

/**
 * Routes publiques de l'API pour les planètes.
 *
 * Ce contrôleur ne fait que de la lecture : l'API est en lecture seule,
 * les données sont ajoutées par la commande planets:import.
 */
class PlanetController extends Controller
{
    /**
     * GET /api/planets
     *
     * Renvoie toutes les planètes, triées par leur index dans le jeu.
     */
    public function index()
    {
        $planets = Planet::orderBy('game_index')->get();

        // collection() applique PlanetResource à chaque planète de la liste
        // et place le résultat dans une clé "data"
        return PlanetResource::collection($planets);
    }

    /**
     * GET /api/planets/{index}
     *
     * Renvoie une seule planète.
     */
    public function show(Planet $planet)
    {
        // Laravel a déjà cherché la planète à partir de l'index de l'URL
        // (voir la route dans routes/api.php). Si elle n'existe pas,
        // il a renvoyé une erreur 404 avant même d'arriver ici.
        return new PlanetResource($planet);
    }
}
