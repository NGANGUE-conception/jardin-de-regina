<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Le Jardin de Régina')</title>


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
