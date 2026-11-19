<?php

namespace App\Models;

use App\Enums\Faction;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'game_index',
    'name',
    'sector',
    'biome_slug',
    'biome_description',
    'environmentals',
    'owner',
    'health',
    'max_health',
    'regen_per_second',
    'players',
    'position_x',
    'position_y',
    'war_synced_at',
])]
class Planet extends Model
{
    protected function casts(): array
    {
        return [
            'environmentals' => 'array',
            'war_synced_at' => 'datetime',
        ];
    }

    // Renvoie la faction qui contrôle la planète, ou null si elle est inconnue.
    // tryFrom() ne plante pas si le numéro n'existe pas dans l'enum
    // (par exemple si le jeu ajoute une nouvelle faction) : il renvoie null.
    public function faction(): ?Faction
    {
        return $this->owner === null ? null : Faction::tryFrom($this->owner);
    }
}
