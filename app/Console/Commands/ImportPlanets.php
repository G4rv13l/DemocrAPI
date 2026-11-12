<?php

namespace App\Console\Commands;

use App\Models\Planet;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ImportPlanets extends Command
{
    protected $signature = 'planets:import';

    protected $description = 'Importe les planètes et l\'état de la guerre depuis l\'API du Helldivers Training Manual';

    public function handle(): int
    {
        $baseUrl = 'https://helldiverstrainingmanual.com/api/v1';
            // 1. Appel des 3 endpoints de l'API (arrêt si l'un d'eux échoue)
        try {
            $this->info('Récupération des planètes...');
            $planets = Http::timeout(20)->get("{$baseUrl}/planets")->throw()->json();
            // timeout(20) : on abandonne après 20 secondes sans réponse
            // get()       : requête HTTP GET
            // throw()     : lève une exception si l'API répond avec une erreur (404, 500...)
            // json()      : transforme la réponse JSON en tableau PHP
            $this->info('Récupération de l\'état de la guerre...');
            $status = Http::timeout(20)->get("{$baseUrl}/war/status")->throw()->json();

            $this->info('Récupération des infos de la guerre...');
            $info = Http::timeout(20)->get("{$baseUrl}/war/info")->throw()->json();
        } catch (RequestException|ConnectionException $e) {
            $this->error('Impossible de joindre l\'API : '.$e->getMessage());

            return self::FAILURE;
        }
        // 2. Indexation des listes de guerre par "index" pour retrouver chaque planète rapidement
        // On range les listes de guerre par index pour retrouver chaque planète
        $statusByIndex = collect($status['planetStatus'] ?? [])->keyBy('index');
        $infoByIndex = collect($info['planetInfos'] ?? [])->keyBy('index');

        // 3. Liste unique et triée de tous les index (planètes + état de la guerre)
        // Union des index des deux sources (certaines planètes ne sont que dans l'une)
        $indexes = collect(array_keys($planets))
            ->map(fn ($index) => (int) $index)
            ->merge($statusByIndex->keys())
            ->unique()
            ->sort()
            ->values();

        $syncedAt = now();

        // 4. Enregistrement en base : tout ou rien grâce à la transaction
        // Transaction : si une erreur survient au milieu, rien n'est enregistré
        DB::transaction(function () use ($indexes, $planets, $statusByIndex, $infoByIndex, $syncedAt) {
            foreach ($indexes as $index) {
                $planet = $planets[(string) $index] ?? [];
                $war = $statusByIndex->get($index, []);
                $warInfo = $infoByIndex->get($index, []);

                Planet::updateOrCreate(
                    ['game_index' => $index],
                    [
                        'name' => ($planet['name'] ?? '') !== '' ? $planet['name'] : null,
                        'sector' => $planet['sector'] ?? null,
                        'biome_slug' => $planet['biome']['slug'] ?? null,
                        'biome_description' => $planet['biome']['description'] ?? null,
                        'environmentals' => $planet['environmentals'] ?? null,
                        'owner' => $war['owner'] ?? null,
                        'health' => $war['health'] ?? null,
                        'max_health' => $warInfo['maxHealth'] ?? null,
                        'regen_per_second' => $war['regenPerSecond'] ?? null,
                        'players' => $war['players'] ?? null,
                        'position_x' => $war['position']['x'] ?? null,
                        'position_y' => $war['position']['y'] ?? null,
                        'war_synced_at' => $war ? $syncedAt : null,
                    ],
                );
            }
        });

        $this->info("{$indexes->count()} planètes importées ou mises à jour.");

        return self::SUCCESS;
    }
}
