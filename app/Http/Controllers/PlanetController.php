<?php

namespace App\Http\Controllers;

use App\Models\Planet;
use Illuminate\View\View;

// Ce contrôleur affiche les planètes stockées dans NOTRE base de données.
// Il n'appelle jamais l'API externe (c'est le rôle de la commande planets:import).
class PlanetController extends Controller
{
    // Noms des factions : la colonne "owner" contient un numéro (voir la migration)
    private const FACTIONS = [
        1 => 'Humains',
        2 => 'Terminides',
        3 => 'Automates',
        4 => 'Illuminés',
    ];

    // Affiche la liste de toutes les planètes
    public function index(): View
    {
        // orderBy trie par numéro de planète, get() récupère le résultat
        $planets = Planet::orderBy('game_index')->get();

        return view('planets.liste', [
            'planets' => $planets,
            'factions' => self::FACTIONS,
        ]);
    }

    // Affiche le détail d'une planète.
    // Laravel retrouve tout seul la planète grâce au game_index présent dans l'URL
    // (on appelle ça le "route model binding"). Si le numéro n'existe pas : erreur 404.
    public function show(Planet $planet): View
    {
        return view('planets.detail', [
            'planet' => $planet,
            'factions' => self::FACTIONS,
        ]);
    }
}
