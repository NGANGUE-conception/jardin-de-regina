@extends('layouts.admin-invite')

@section('title', 'Nouveau mot de passe')
@section('titre_page', 'Choisir un nouveau mot de passe')

@section('content')
    <p class="font-body text-sm text-primary-deep/80 mb-5">
        Compte : <strong class="text-primary-deep">{{ $email }}</strong>
    </p>

    @php($champ = 'w-full h-[52px] px-5 rounded-2xl bg-cream text-primary-deep shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all')

    <form method="POST" action="{{ route('admin.password.update') }}" autocomplete="off" class="flex flex-col gap-4"
          x-data="{ voir: false, mdp: '', confirmation: '' }">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="flex flex-col gap-1.5">
            <label for="password" class="font-body text-sm font-bold text-primary-deep">Nouveau mot de passe</label>
            <input id="password" :type="voir ? 'text' : 'password'" name="password" x-model="mdp" required minlength="12" autofocus autocomplete="new-password"
                   class="{{ $champ }} {{ $errors->has('password') ? 'ring-2 ring-coral' : '' }}">
        </div>
        <div class="flex flex-col gap-1.5">
            <label for="password_confirmation" class="font-body text-sm font-bold text-primary-deep">Confirmer le nouveau mot de passe</label>
            <input id="password_confirmation" :type="voir ? 'text' : 'password'" name="password_confirmation" x-model="confirmation" required autocomplete="new-password" class="{{ $champ }}">
        </div>

        <label class="flex items-center gap-2 font-body text-sm text-primary-deep/80 cursor-pointer select-none w-fit">
            <input type="checkbox" x-model="voir" class="w-4 h-4 accent-primary">
            Afficher les mots de passe
        </label>

        <x-admin.regles-mot-de-passe class="!grid-cols-1" />

        <button type="submit" class="mt-2 h-[52px] rounded-full bg-coral text-white font-body font-bold text-sm shadow-lg hover:-translate-y-0.5 hover:opacity-90 transition-all flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V7a4 4 0 018 0v4"/></svg>
            Enregistrer et me connecter
        </button>
    </form>

    <p class="font-body text-xs text-primary-deep/75 mt-5">
        Une fois le mot de passe changé, tous les autres appareils connectés à l'espace admin seront déconnectés.
    </p>
@endsection

@section('lien_bas')
    <a href="{{ route('admin.login') }}" class="hover:text-primary hover:underline">&larr; Retour à la connexion</a>
@endsection
