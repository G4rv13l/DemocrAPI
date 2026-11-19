<?php

namespace App\Enums;

// Liste des factions du jeu, définie à un seul endroit.
// En base, la colonne "owner" des planètes contient le numéro (1 à 4) :
// chaque "case" associe ce numéro à une faction.
enum Faction: int
{
    case Humans = 1;
    case Terminids = 2;
    case Automatons = 3;
    case Illuminate = 4;

    // Identifiant renvoyé par l'API (ex. "terminids")
    public function slug(): string
    {
        return strtolower($this->name);
    }

    // Nom affiché sur le site (ex. "Terminids")
    public function label(): string
    {
        return $this->name;
    }
}
