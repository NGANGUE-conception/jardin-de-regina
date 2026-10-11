<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Espace admin — Le Jardin de Régina</title>


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-body bg-cream min-h-screen flex items-center justify-center px-6 relative overflow-hidden">

    <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-mint blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-peach blur-3xl pointer-events-none -z-10"></div>

    <div class="w-full max-w-md">
        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('images/logo.png') }}" alt="Le Jardin de Régina" class="h-16 w-auto object-contain mb-3">
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold">Espace direction</span>
            <h1 class="font-display text-2xl text-primary-deep mt-1">Connexion à l'administration</h1>
        </div>

        <div class="bg-white rounded-[32px] shadow-lg p-6 sm:p-8">
            @if ($errors->any())
            <div class="bg-coral/10 border border-coral/30 rounded-2xl p-4 mb-5 flex items-start gap-3">
                <svg class="w-5 h-5 text-coral shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-8.18 14A2 2 0 004 21h16a2 2 0 001.89-3.14l-8.18-14a2 2 0 00-3.42 0z"/></svg>
                <p class="font-body text-sm text-primary-deep">{{ $errors->first() }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" autocomplete="off" class="flex flex-col gap-4">
                @csrf
                <div class="flex flex-col gap-1.5">
                    <label for="email" class="font-body text-sm font-bold text-primary-deep">Adresse email</label>
                    <input id="email" name="email" value="{{ old('email') }}" type="email" required autofocus autocomplete="off" data-lpignore="true" data-1p-ignore data-bwignore placeholder="votre@email.fr" class="h-[52px] px-5 rounded-2xl bg-cream text-primary-deep placeholder:text-primary-deep/40 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label for="password" class="font-body text-sm font-bold text-primary-deep">Mot de passe</label>
                    <input id="password" name="password" type="password" required autocomplete="off" data-lpignore="true" data-1p-ignore data-bwignore placeholder="••••••••" class="h-[52px] px-5 rounded-2xl bg-cream text-primary-deep placeholder:text-primary-deep/40 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all">
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" value="1" class="w-4 h-4 rounded text-primary accent-primary">
                    <span class="font-body text-sm text-primary-deep/70">Rester connecté</span>
                </label>
                <button type="submit" class="mt-2 h-[52px] rounded-full bg-coral text-white font-body font-bold text-sm shadow-lg hover:-translate-y-0.5 hover:opacity-90 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l4-4m0 0l-4-4m4 4H3m8 7a9 9 0 110-14"/></svg>
                    Se connecter
                </button>
            </form>
        </div>

        <p class="text-center font-body text-xs text-primary-deep/50 mt-6">
            <a href="{{ route('accueil') }}" class="hover:text-primary hover:underline">&larr; Retour au site public</a>
        </p>
    </div>

    @livewireScripts
</body>
</html>
