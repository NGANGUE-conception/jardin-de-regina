<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Espace admin — Le Jardin de Régina')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Chewy&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-body bg-cream">
    @if (session('success'))
    <div x-data="{ shown: true }" x-show="shown" x-init="setTimeout(() => shown = false, 4000)" x-transition
         class="fixed top-4 left-1/2 -translate-x-1/2 z-[100] bg-primary text-white px-5 py-3 rounded-full shadow-lg font-body text-sm font-bold flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    @yield('content')

    @livewireScripts
</body>
</html>
