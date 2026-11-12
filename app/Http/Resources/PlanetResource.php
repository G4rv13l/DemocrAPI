<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transforme une planète de la base en JSON pour l'API publique.
 *
 * C'est ici qu'on choisit ce qui est exposé et sous quelle forme :
 * les colonnes internes (id, created_at, updated_at) ne sont pas renvoyées.
 */
class PlanetResource extends JsonResource
{
    // En base, le propriétaire est stocké sous forme de chiffre (voir la migration).
    // L'API renvoie plutôt un nom, plus facile à comprendre pour qui l'utilise.
    private const OWNERS = [
        1 => 'humans',
        2 => 'terminids',
        3 => 'automatons',
        4 => 'illuminate',
    ];

    public function toArray(Request $request): array
    {
        // $this->... lit directement les colonnes de la planète (le modèle Planet)
        return [
            // Index de la planète dans le jeu : c'est l'identifiant public,
            // le même chez tout le monde, contrairement à l'id de la base
            'index' => $this->game_index,

            // Données fixes, venant de l'endpoint /planets
            'name' => $this->name,
            'sector' => $this->sector,

            // Certaines planètes n'ont pas de biome : on renvoie alors null
            // plutôt qu'un objet rempli de valeurs vides
            'biome' => $this->biome_slug ? [
                'slug' => $this->biome_slug,
                'description' => $this->biome_description,
            ] : null,

            // Liste des conditions environnementales (tempêtes, brouillard...).
            // Si la valeur est null en base, on renvoie une liste vide
            'environmentals' => $this->environmentals ?? [],

            // Coordonnées de la planète sur la carte de la galaxie
            'position' => [
                'x' => $this->position_x,
                'y' => $this->position_y,
            ],

            // Dernier état connu de la guerre (endpoints /war/status et /war/info),
            // mis à jour à chaque lancement de planets:import
            'war' => [
                // ?? null : si le chiffre est inconnu ou absent, on renvoie null
                'owner' => self::OWNERS[$this->owner] ?? null,
                'health' => $this->health,
                'max_health' => $this->max_health,
                'regen_per_second' => $this->regen_per_second,
                'players' => $this->players,
                // Date de la dernière synchronisation : indique la fraîcheur des données
                'synced_at' => $this->war_synced_at,
            ],
        ];
    }
}
