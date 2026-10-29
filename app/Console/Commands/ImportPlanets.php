<?php

namespace App\Console\Commands;

use App\Models\Planet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportPlanets extends Command
{
    protected $signature = 'planets:import';

    protected $description = 'Importe les planètes depuis l\'API du Helldivers Training Manual';

    public function handle(): int
    {
        $response = Http::timeout(20)->get('https://helldiverstrainingmanual.com/api/v1/planets');

        if ($response->failed()) {
            $this->error('Erreur HTTP '.$response->status());

            return self::FAILURE;
        }

        // La réponse est un objet : les clés sont les index ("0", "1", ...)
        $planets = $response->json();

        foreach ($planets as $index => $planet) {
            Planet::updateOrCreate(
                ['game_index' => (int) $index],
                [
                    // Certaines planètes ont un nom vide : on stocke null
                    'name' => ($planet['name'] ?? '') !== '' ? $planet['name'] : null,
                    'sector' => $planet['sector'] ?? null,
                    'biome_slug' => $planet['biome']['slug'] ?? null,
                    'biome_description' => $planet['biome']['description'] ?? null,
                    'environmentals' => $planet['environmentals'] ?? null,
                ],
            );
        }

        $this->info(count($planets).' planètes importées ou mises à jour.');

        return self::SUCCESS;
    }
}
