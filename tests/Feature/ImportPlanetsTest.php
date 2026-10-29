<?php

namespace Tests\Feature;

use App\Models\Planet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ImportPlanetsTest extends TestCase
{
    // Recrée une base vide avant chaque test
    use RefreshDatabase;

    // Santé renvoyée par la fausse API pour la planète 1 (modifiable entre deux imports)
    private int $health = 1000000;

    // Remplace les vraies requêtes HTTP par de fausses réponses
    private function fakeApi(): void
    {
        Http::fake([
            '*/planets' => Http::response([
                '1' => [
                    'name' => 'Klen Dahth II',
                    'sector' => 'Altus',
                    'biome' => ['slug' => 'mesa', 'description' => 'A blazing-hot desert planet.'],
                    'environmentals' => [
                        ['name' => 'Sandstorms', 'description' => 'Dense sandstorms reduce mobility.'],
                    ],
                ],
                // Planète sans nom, sans biome et sans secteur, comme dans la vraie API
                '263' => ['name' => '', 'biome' => null, 'environmentals' => []],
            ]),
            '*/war/status' => fn () => Http::response([
                'planetStatus' => [
                    ['index' => 1, 'owner' => 2, 'health' => $this->health, 'regenPerSecond' => 4.17, 'players' => 1500, 'position' => ['x' => 0.05, 'y' => 0.1]],
                    // Planète présente dans l'état de guerre mais absente de /planets
                    ['index' => 270, 'owner' => 1, 'health' => 1000000, 'regenPerSecond' => 4.17, 'players' => 0, 'position' => ['x' => 0.3, 'y' => 0.4]],
                ],
            ]),
            '*/war/info' => Http::response([
                'planetInfos' => [
                    ['index' => 1, 'maxHealth' => 1000000],
                    ['index' => 270, 'maxHealth' => 1000000],
                ],
            ]),
        ]);
    }

    public function test_it_imports_planets_from_all_endpoints(): void
    {
        $this->fakeApi();

        $this->artisan('planets:import')->assertSuccessful();

        // 1 et 263 viennent de /planets, 270 seulement de /war/status
        $this->assertSame(3, Planet::count());

        $planet = Planet::where('game_index', 1)->first();
        $this->assertSame('Klen Dahth II', $planet->name);
        $this->assertSame('Altus', $planet->sector);
        $this->assertSame('mesa', $planet->biome_slug);
        $this->assertSame('Sandstorms', $planet->environmentals[0]['name']);
        $this->assertSame(2, $planet->owner);
        $this->assertSame(1500, $planet->players);
        $this->assertSame(1000000, $planet->max_health);
        $this->assertNotNull($planet->war_synced_at);

        $unnamed = Planet::where('game_index', 263)->first();
        $this->assertNull($unnamed->name);
        $this->assertNull($unnamed->biome_slug);
        $this->assertNull($unnamed->war_synced_at);
    }

    public function test_running_it_twice_updates_instead_of_duplicating(): void
    {
        $this->fakeApi();
        $this->artisan('planets:import')->assertSuccessful();

        // La santé de la planète 1 change entre les deux imports
        $this->health = 400000;
        $this->artisan('planets:import')->assertSuccessful();

        $this->assertSame(3, Planet::count());
        $this->assertSame(400000, Planet::where('game_index', 1)->first()->health);
    }

    public function test_it_fails_cleanly_when_the_api_is_down(): void
    {
        // Toutes les requêtes renvoient une erreur 500
        Http::fake(['*' => Http::response(null, 500)]);

        $this->artisan('planets:import')->assertFailed();

        $this->assertSame(0, Planet::count());
    }
}
