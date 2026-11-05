{{-- Page « liste » : elle reprend le modèle base.blade.php --}}
@extends('planets.base')

@section('title', 'Liste des planètes')

@section('content')
    <h1>Planètes</h1>

    {{-- Si la collection est vide (aucune planète importée), on affiche un message --}}
    @if ($planets->isEmpty())
        <p class="message">
            Aucune planète en base. Lance d'abord la commande : php artisan planets:import
        </p>
    @else
        <p>{{ $planets->count() }} planètes</p>

        <div class="tableau-conteneur">
            <table>
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Nom</th>
                        <th>Secteur</th>
                        <th>Faction</th>
                        <th>Joueurs</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {{-- On répète une ligne pour chaque planète --}}
                    @foreach ($planets as $planet)
                        <tr>
                            <td>{{ $planet->game_index }}</td>
                            {{-- ?? affiche la valeur de droite si la colonne est vide (null) --}}
                            <td>{{ $planet->name ?? 'Sans nom' }}</td>
                            <td>{{ $planet->sector ?? '-' }}</td>
                            <td>{{ $factions[$planet->owner] ?? 'Inconnue' }}</td>
                            <td>{{ $planet->players ?? '-' }}</td>
                            <td><a href="{{ route('planets.show', $planet) }}">détail</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
