@extends('layouts.admin-invite')

@section('title', 'Connexion')
@section('titre_page', "Connexion à l'administration")

@section('content')
    <form method="POST" action="{{ route('admin.login.submit') }}" autocomplete="off" class="flex flex-col gap-4">
        @csrf
        <div class="flex flex-col gap-1.5">
            <label for="email" class="font-body text-sm font-bold text-primary-deep">Adresse email</label>
            <input id="email" name="email" value="{{ old('email') }}" type="email" required autofocus autocomplete="off" data-lpignore="true" data-1p-ignore data-bwignore placeholder="votre@email.fr" class="h-[52px] px-5 rounded-2xl bg-cream text-primary-deep placeholder:text-primary-deep/40 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all">
        </div>
        <div class="flex flex-col gap-1.5">
            <div class="flex items-center justify-between gap-3">
                <label for="password" class="font-body text-sm font-bold text-primary-deep">Mot de passe</label>
                <a href="{{ route('admin.password.request') }}" class="font-body text-xs font-bold text-primary hover:underline">Mot de passe oublié ?</a>
            </div>
            <input id="password" name="password" type="password" required autocomplete="off" data-lpignore="true" data-1p-ignore data-bwignore placeholder="••••••••" class="h-[52px] px-5 rounded-2xl bg-cream text-primary-deep placeholder:text-primary-deep/40 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all">
        </div>
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="remember" value="1" class="w-4 h-4 rounded text-primary accent-primary">
            <span class="font-body text-sm text-primary-deep/80">Rester connecté</span>
        </label>
        <button type="submit" class="mt-2 h-[52px] rounded-full bg-coral text-white font-body font-bold text-sm shadow-lg hover:-translate-y-0.5 hover:opacity-90 transition-all flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l4-4m0 0l-4-4m4 4H3m8 7a9 9 0 110-14"/></svg>
            Se connecter
        </button>
    </form>
@endsection
