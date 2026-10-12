{{--
    Gabarit des pages de l'espace admin accessibles sans être connecté :
    connexion, mot de passe oublié, choix d'un nouveau mot de passe.
--}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Espace admin — Le Jardin de Régina</title>
    <meta name="robots" content="noindex, nofollow">
    @include('partials.icones')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body bg-cream min-h-screen flex items-center justify-center px-6 py-10 relative overflow-hidden">

    <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-mint blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-peach blur-3xl pointer-events-none -z-10"></div>

    <div class="w-full max-w-md">
        <div class="flex flex-col items-center mb-6 text-center">
            <img src="{{ asset('images/logo.webp') }}" width="375" height="192" alt="Le Jardin de Régina" class="h-16 w-auto object-contain mb-3">
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold">Espace direction</span>
            <h1 class="font-display text-2xl text-primary-deep mt-1">@yield('titre_page')</h1>
        </div>

        <div class="bg-white rounded-[32px] shadow-lg p-6 sm:p-8">
            @if (session('statut'))
                <div class="bg-mint/60 rounded-2xl p-4 mb-5 flex items-start gap-3" role="status">
                    <svg class="w-5 h-5 text-primary shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <p class="font-body text-sm text-primary-deep">{{ session('statut') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-coral/10 border border-coral/30 rounded-2xl p-4 mb-5 flex items-start gap-3" role="alert">
                    <svg class="w-5 h-5 text-coral shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-8.18 14A2 2 0 004 21h16a2 2 0 001.89-3.14l-8.18-14a2 2 0 00-3.42 0z"/></svg>
                    <ul class="font-body text-sm text-primary-deep flex flex-col gap-0.5">
                        @foreach ($errors->all() as $erreur)
                            <li>{{ $erreur }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>

        <p class="text-center font-body text-xs text-primary-deep/75 mt-6">
            @hasSection('lien_bas')
                @yield('lien_bas')
            @else
                <a href="{{ route('accueil') }}" class="hover:text-primary hover:underline">&larr; Retour au site public</a>
            @endif
        </p>
    </div>

</body>
</html>
