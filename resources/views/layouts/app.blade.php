@php
    use App\Support\Referencement;

    // Titre, description et consigne pour les robots, définis par chaque page (@section) ou valeurs par défaut.
    // Les sections sont déjà encodées en HTML par Blade : on les décode pour les réencoder une seule fois ci-dessous.
    $metaTitre = html_entity_decode(trim($__env->yieldContent('title', config('creche.nom'))), ENT_QUOTES);
    $metaDescription = html_entity_decode(trim($__env->yieldContent('description', config('creche.description'))), ENT_QUOTES);
    $metaRobots = trim($__env->yieldContent('robots', 'index, follow, max-image-preview:large'));
    $urlCanonique = Referencement::urlCanonique(request());
    $imagePartage = Referencement::url(config('creche.image_partage'));
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $metaTitre }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="robots" content="{{ $metaRobots }}">
    <link rel="canonical" href="{{ $urlCanonique }}">

    {{-- Aperçu lors d'un partage du lien (WhatsApp, Facebook, LinkedIn, SMS...) --}}
    <meta property="og:type" content="website">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:site_name" content="{{ config('creche.nom') }}">
    <meta property="og:title" content="{{ $metaTitre }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $urlCanonique }}">
    <meta property="og:image" content="{{ $imagePartage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Logo du Jardin de Régina, micro-crèche écoresponsable à Alfortville">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitre }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $imagePartage }}">

    @include('partials.icones')

    {{-- Données structurées pour Google : le site et la crèche (adresse, horaires, contact...) --}}
    <script type="application/ld+json">{!! json_encode(Referencement::donneesStructurees(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_PRETTY_PRINT) !!}</script>

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
