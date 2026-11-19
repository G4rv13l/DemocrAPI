{{-- Modèle commun à toutes les pages : menu en haut, pied de page en bas --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    {{-- Cette ligne rend la page lisible sur mobile --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Democrapi - @yield('title', 'Planets')</title>
    {{-- asset() construit l'adresse du fichier qui se trouve dans le dossier public --}}
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>
    <header class="menu">
        <span class="logo">DEMOCRAPI</span>
        <nav>
            <a href="{{ route('planets.index') }}">Planets</a>
        </nav>
    </header>

    <main class="content">
        {{-- Chaque page enfant insère son contenu ici --}}
        @yield('content')
    </main>

    <footer class="site-footer">
        For Super Earth! ESIG school project, data from the local database.
    </footer>
</body>
</html>
