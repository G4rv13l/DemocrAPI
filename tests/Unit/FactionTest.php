<?php

namespace Tests\Unit;

use App\Enums\Faction;
use App\Models\Planet;
use PHPUnit\Framework\TestCase;

class FactionTest extends TestCase
{
    // Le même numéro donne l'identifiant de l'API et le nom affiché sur le site
    public function test_faction_has_api_slug_and_display_label(): void
    {
        $this->assertSame('terminids', Faction::Terminids->slug());
        $this->assertSame('Terminids', Faction::Terminids->label());
    }

    // Le modèle traduit la colonne owner en faction
    public function test_planet_returns_its_faction(): void
    {
        $this->assertSame(Faction::Automatons, (new Planet(['owner' => 3]))->faction());
    }

    // Un numéro absent ou inconnu ne fait pas planter : on obtient null
    public function test_unknown_or_missing_owner_returns_null(): void
    {
        $this->assertNull((new Planet(['owner' => 9]))->faction());
        $this->assertNull((new Planet())->faction());
    }
}
