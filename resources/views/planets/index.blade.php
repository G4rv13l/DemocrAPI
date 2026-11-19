{{-- Page « liste » : elle reprend le modèle base.blade.php --}}
@extends('planets.base')

@section('title', 'Planet list')

@section('content')
    <h1>Planets</h1>

    {{-- Si la collection est vide (aucune planète importée), on affiche un message --}}
    @if ($planets->isEmpty())
        <p class="message">
            No planets in the database. Run this command first: php artisan planets:import
        </p>
    @else
        <p>{{ $planets->count() }} planets</p>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Sector</th>
                        <th>Faction</th>
                        <th>Players</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {{-- On répète une ligne pour chaque planète --}}
                    @foreach ($planets as $planet)
                        <tr>
                            <td>{{ $planet->game_index }}</td>
                            {{-- ?? affiche la valeur de droite si la colonne est vide (null) --}}
                            <td>{{ $planet->name ?? 'Unnamed' }}</td>
                            <td>{{ $planet->sector ?? '-' }}</td>
                            <td>{{ $planet->faction()?->label() ?? 'Unknown' }}</td>
                            <td>{{ $planet->players ?? '-' }}</td>
                            <td><a href="{{ route('planets.show', $planet->game_index) }}">details</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
