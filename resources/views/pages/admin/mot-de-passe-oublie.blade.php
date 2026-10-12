@extends('layouts.admin-invite')

@section('title', 'Mot de passe oublié')
@section('titre_page', 'Mot de passe oublié')

@section('content')
    <p class="font-body text-sm text-primary-deep/80 mb-5">
        Indiquez l'adresse email de votre compte administrateur. Si elle est reconnue, vous recevrez un lien pour choisir un nouveau mot de passe. Ce lien est valable <strong>{{ $dureeMinutes }} minutes</strong>.
    </p>

    <form method="POST" action="{{ route('admin.password.email') }}" autocomplete="off" class="flex flex-col gap-4">
        @csrf
        <div class="flex flex-col gap-1.5">
            <label for="email" class="font-body text-sm font-bold text-primary-deep">Adresse email</label>
            <input id="email" name="email" value="{{ old('email') }}" type="email" required autofocus autocomplete="off" data-lpignore="true" data-1p-ignore data-bwignore placeholder="votre@email.fr"
                   class="h-[52px] px-5 rounded-2xl bg-cream text-primary-deep placeholder:text-primary-deep/40 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all {{ $errors->has('email') ? 'ring-2 ring-coral' : '' }}">
        </div>
        <button type="submit" class="mt-2 h-[52px] rounded-full bg-coral text-white font-body font-bold text-sm shadow-lg hover:-translate-y-0.5 hover:opacity-90 transition-all flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
            Recevoir le lien
        </button>
    </form>

    <p class="font-body text-xs text-primary-deep/75 mt-5">
        Vous ne recevez rien ? Vérifiez vos courriers indésirables, ou attendez une minute avant de refaire une demande.
    </p>
@endsection

@section('lien_bas')
    <a href="{{ route('admin.login') }}" class="hover:text-primary hover:underline">&larr; Retour à la connexion</a>
@endsection
