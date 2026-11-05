{{-- Modèle commun à toutes les pages : menu en haut, pied de page en bas --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    {{-- Cette ligne rend la page lisible sur mobile --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Democrapi - @yield('title', 'Planètes')</title>
    {{-- asset() construit l'adresse du fichier qui se trouve dans le dossier public --}}
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>
    <header class="menu">
        <span class="logo">DEMOCRAPI</span>
        <nav>
            <a href="{{ route('planets.index') }}">Planètes</a>
        </nav>
    </header>

    <main class="contenu">
        {{-- Chaque page enfant insère son contenu ici --}}
        @yield('content')
    </main>

    <footer class="pied">
        Pour la Super Terre ! Projet scolaire ESIG, données issues de la base locale.
    </footer>
</body>
</html>
