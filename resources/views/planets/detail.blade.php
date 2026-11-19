{{-- Page « détail » : toutes les informations d'une planète --}}
@extends('planets.base')

@section('title', $planet->name ?? 'Unnamed planet')

@section('content')
    <p><a href="{{ route('planets.index') }}">&larr; Back to the list</a></p>

    <h1>{{ $planet->name ?? 'Unnamed planet' }}</h1>

    <h2>General information</h2>
    <dl class="info-list">
        <dt>Game index</dt>
        <dd>{{ $planet->game_index }}</dd>

        <dt>Sector</dt>
        <dd>{{ $planet->sector ?? '-' }}</dd>

        <dt>Biome</dt>
        <dd>{{ $planet->biome_slug ?? '-' }}</dd>

        <dt>Biome description</dt>
        <dd>{{ $planet->biome_description ?? '-' }}</dd>
    </dl>

    <h2>Environmental conditions</h2>
    {{-- environmentals est un tableau (colonne JSON) : on le parcourt avec @foreach --}}
    @if (!empty($planet->environmentals))
        <ul class="env-list">
            @foreach ($planet->environmentals as $environmental)
                <li>
                    <strong>{{ $environmental['name'] ?? 'Unnamed' }}</strong>
                    : {{ $environmental['description'] ?? '' }}
                </li>
            @endforeach
        </ul>
    @else
        <p>No special conditions.</p>
    @endif

    <h2>War status</h2>
    {{-- war_synced_at est vide si la guerre n'a jamais été synchronisée pour cette planète --}}
    @if ($planet->war_synced_at)
        <dl class="info-list">
            <dt>Faction</dt>
            <dd>{{ $planet->faction()?->label() ?? 'Unknown' }}</dd>

            <dt>Health</dt>
            <dd>
                {{ $planet->health ?? '-' }} / {{ $planet->max_health ?? '-' }}
                {{-- On évite une division par zéro --}}
                @if ($planet->max_health)
                    ({{ round($planet->health / $planet->max_health * 100) }} %)
                @endif
            </dd>

            <dt>Regeneration per second</dt>
            <dd>{{ $planet->regen_per_second ?? '-' }}</dd>

            <dt>Players</dt>
            <dd>{{ $planet->players ?? '-' }}</dd>

            <dt>Position (x, y)</dt>
            <dd>{{ $planet->position_x ?? '-' }} ; {{ $planet->position_y ?? '-' }}</dd>

            <dt>Last sync</dt>
            <dd>{{ $planet->war_synced_at->format('Y-m-d H:i') }}</dd>
        </dl>
    @else
        <p class="message">No war data for this planet.</p>
    @endif

    <p class="note">
        Created on {{ $planet->created_at->format('Y-m-d H:i') }},
        updated on {{ $planet->updated_at->format('Y-m-d H:i') }}.
    </p>
@endsection
