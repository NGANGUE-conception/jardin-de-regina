<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Le Jardin de Régina')</title>
      <meta name="description" content="@yield('description', 'Le Jardin de Régina, micro-crèche écoresponsable à Alfortville (94) : 14 places pour les enfants de 10 semaines à 3 ans et plus, du lundi au vendredi de 8h30 à 18h30.')">
      



    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-body">
    <x-header />

    <main>
        @yield('content')
    </main>

    <x-footer />

    @livewireScripts
</body>
</html>
