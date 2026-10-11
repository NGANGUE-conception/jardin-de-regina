@extends('layouts.app')

@section('title', $titre . ' — Le Jardin de Régina')

@section('content')
<div class="max-w-[900px] mx-auto px-6 py-32 text-center flex flex-col items-center gap-4">
    <span class="font-body text-sm uppercase tracking-widest text-primary font-extrabold">Page en construction</span>
    <h1 class="font-display text-4xl text-primary-deep">{{ $titre }}</h1>
    <p class="font-body text-primary-deep/80 max-w-lg">
        Cette page arrive bientôt. En attendant, découvre notre page d'accueil.
    </p>
    <a href="/" class="mt-4 h-[52px] px-6 rounded-full bg-primary text-white font-body font-bold text-sm flex items-center justify-center shadow-md hover:opacity-90 transition">
        Retour à l'accueil
    </a>
</div>
@endsection
