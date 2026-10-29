<?php

namespace App\Models;

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
}
