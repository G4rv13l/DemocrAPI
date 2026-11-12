{{-- Page « détail » : toutes les informations d'une planète --}}
@extends('planets.base')

@section('title', $planet->name ?? 'Planète sans nom')

@section('content')
    <p><a href="{{ route('planets.index') }}">&larr; Retour à la liste</a></p>

    <h1>{{ $planet->name ?? 'Planète sans nom' }}</h1>

    <h2>Informations générales</h2>
    <dl class="infos">
        <dt>Numéro dans le jeu</dt>
        <dd>{{ $planet->game_index }}</dd>

        <dt>Secteur</dt>
        <dd>{{ $planet->sector ?? '-' }}</dd>

        <dt>Biome</dt>
        <dd>{{ $planet->biome_slug ?? '-' }}</dd>

        <dt>Description du biome</dt>
        <dd>{{ $planet->biome_description ?? '-' }}</dd>
    </dl>

    <h2>Conditions environnementales</h2>
    {{-- environmentals est un tableau (colonne JSON) : on le parcourt avec @foreach --}}
    @if (!empty($planet->environmentals))
        <ul class="liste-env">
            @foreach ($planet->environmentals as $environmental)
                <li>
                    <strong>{{ $environmental['name'] ?? 'Sans nom' }}</strong>
                    : {{ $environmental['description'] ?? '' }}
                </li>
            @endforeach
        </ul>
    @else
        <p>Aucune condition particulière.</p>
    @endif

    <h2>État de la guerre</h2>
    {{-- war_synced_at est vide si la guerre n'a jamais été synchronisée pour cette planète --}}
    @if ($planet->war_synced_at)
        <dl class="infos">
            <dt>Faction</dt>
            <dd>{{ $factions[$planet->owner] ?? 'Inconnue' }}</dd>

            <dt>Santé</dt>
            <dd>
                {{ $planet->health ?? '-' }} / {{ $planet->max_health ?? '-' }}
                {{-- On évite une division par zéro --}}
                @if ($planet->max_health)
                    ({{ round($planet->health / $planet->max_health * 100) }} %)
                @endif
            </dd>

            <dt>Régénération par seconde</dt>
            <dd>{{ $planet->regen_per_second ?? '-' }}</dd>

            <dt>Joueurs</dt>
            <dd>{{ $planet->players ?? '-' }}</dd>

            <dt>Position (x, y)</dt>
            <dd>{{ $planet->position_x ?? '-' }} ; {{ $planet->position_y ?? '-' }}</dd>

            <dt>Dernière synchronisation</dt>
            <dd>{{ $planet->war_synced_at->format('d.m.Y H:i') }}</dd>
        </dl>
    @else
        <p class="message">Pas de données de guerre pour cette planète.</p>
    @endif

    <p class="petit">
        Enregistrée le {{ $planet->created_at->format('d.m.Y H:i') }},
        modifiée le {{ $planet->updated_at->format('d.m.Y H:i') }}.
    </p>
@endsection
