<?php

namespace Tests\Feature;

use App\Models\Planet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanetApiTest extends TestCase
{
    // Recrée une base vide avant chaque test : les tests ne dépendent
    // donc pas de l'import ni des données présentes dans MySQL
    use RefreshDatabase;

    // Crée une planète de test. On peut remplacer certaines valeurs
    // en les passant en paramètre, par ex. createPlanet(['game_index' => 5])
    private function createPlanet(array $attributes = []): Planet
    {
        return Planet::create(array_merge([
            'game_index' => 1,
            'name' => 'Klen Dahth II',
            'sector' => 'Altus',
            'biome_slug' => 'mesa',
            'biome_description' => 'A blazing-hot desert planet.',
            'environmentals' => [
                ['name' => 'Sandstorms', 'description' => 'Dense sandstorms reduce mobility.'],
            ],
            'owner' => 2,
            'health' => 750000,
            'max_health' => 1000000,
            'regen_per_second' => 4.17,
            'players' => 1500,
            'position_x' => 0.05,
            'position_y' => 0.1,
            'war_synced_at' => now(),
        ], $attributes));
    }

    public function test_it_lists_planets_sorted_by_index(): void
    {
        // Créées volontairement dans le désordre, pour vérifier le tri
        $this->createPlanet(['game_index' => 5, 'name' => 'Cinquième']);
        $this->createPlanet(['game_index' => 1, 'name' => 'Première']);

        $this->getJson('/api/planets')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.index', 1)
            ->assertJsonPath('data.1.index', 5);
    }

    public function test_it_shows_a_planet_by_its_game_index(): void
    {
        $this->createPlanet();

        $this->getJson('/api/planets/1')
            ->assertOk()
            ->assertJsonPath('data.name', 'Klen Dahth II')
            ->assertJsonPath('data.biome.slug', 'mesa')
            // Le chiffre 2 stocké en base doit être traduit en texte
            ->assertJsonPath('data.war.owner', 'terminids')
            // L'id interne de la base ne doit pas être exposé
            ->assertJsonMissingPath('data.id');
    }

    public function test_a_planet_without_biome_returns_null(): void
    {
        $this->createPlanet(['biome_slug' => null, 'biome_description' => null]);

        $this->getJson('/api/planets/1')
            ->assertOk()
            ->assertJsonPath('data.biome', null);
    }

    public function test_an_unknown_planet_returns_a_clean_404(): void
    {
        // assertExactJson vérifie qu'il n'y a QUE le message :
        // ni trace, ni nom de classe, même avec le mode debug activé
        $this->getJson('/api/planets/9999')
            ->assertNotFound()
            ->assertExactJson(['message' => 'Sweet liberty! Resource not found!']);
    }

    public function test_an_unknown_api_route_returns_a_clean_404(): void
    {
        $this->getJson('/api/n-importe-quoi')
            ->assertNotFound()
            ->assertExactJson(['message' => 'Sweet liberty! Resource not found!']);
    }
}
