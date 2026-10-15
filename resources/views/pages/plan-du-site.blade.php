@extends('layouts.app')

@section('title', __('Plan du site — Le Jardin de Régina'))
@section('description', __('Toutes les pages du site du Jardin de Régina, micro-crèche à Alfortville : projet, fonctionnement, tarifs, pré-inscription, contact et informations légales.'))

@section('content')

{{-- SECTION 1 : HERO --}}
<section class="relative bg-cream pt-10 pb-10 overflow-hidden">
    <div class="absolute -top-24 right-1/4 w-96 h-96 rounded-full bg-mint blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute top-12 left-10 w-80 h-80 rounded-full bg-peach blur-3xl pointer-events-none -z-10"></div>
    <x-confetti shape="fleur" class="top-16 left-[8%] w-7 h-7 text-coral animate-float" />

    <div class="max-w-[860px] mx-auto px-6 text-center relative z-10">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white shadow-sm text-primary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5-2V4l5 2m0 14l6-2m-6 2V6m6 12l5 2V6l-5-2m0 14V4m0 0L9 6"/></svg>
            <span class="font-body text-xs uppercase tracking-widest font-extrabold">{{ __('Plan du site') }}</span>
        </div>
        <h1 class="font-display text-4xl md:text-5xl text-primary-deep mt-4">{{ __('Toutes nos pages') }}</h1>
        <p class="font-body text-lg text-primary-deep/80 mt-4 max-w-xl mx-auto leading-relaxed">
            {{ __('Retrouvez en un coup d\'œil l\'ensemble des pages du site du Jardin de Régina.') }}
        </p>
    </div>
</section>

{{-- SECTION 2 : PAGES PAR RUBRIQUE --}}
<section class="bg-cream pb-20">
    <div class="max-w-[1000px] mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach ($rubriques as $rubrique => $pages)
            <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm">
                <h2 class="font-display text-2xl text-primary-deep mb-4">{{ __($rubrique) }}</h2>
                <ul class="flex flex-col gap-1">
                    @foreach ($pages as $page)
                        <li>
                            <a href="{{ route($page['route']) }}" class="group flex items-start gap-3 rounded-2xl px-3 py-2.5 -mx-3 hover:bg-mint/40 transition-colors {{ request()->routeIs($page['route']) ? 'bg-mint/40' : '' }}">
                                <svg class="w-5 h-5 text-primary shrink-0 mt-0.5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                <span class="flex flex-col">
                                    <span class="font-body font-bold text-primary-deep group-hover:text-primary transition-colors">{{ __($page['titre']) }}</span>
                                    <span class="font-body text-sm text-primary-deep/75">{{ __($page['resume']) }}</span>
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>

    <div class="max-w-[1000px] mx-auto px-6 mt-8">
        <div class="bg-mint/50 rounded-3xl p-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
            <p class="font-body text-primary-deep">
                <span class="font-bold">{{ __('Vous ne trouvez pas ce que vous cherchez ?') }}</span>
                {{ __('Appelez-nous au') }} <a href="tel:{{ config('creche.telephone') }}" class="text-primary font-bold hover:underline">{{ config('creche.telephone_affiche') }}</a>.
            </p>
            <a href="{{ route('contact') }}" class="h-[48px] px-6 rounded-full bg-primary text-white font-body font-bold text-sm flex items-center justify-center shadow-md hover:opacity-90 transition shrink-0">
                {{ __('Nous contacter') }}
            </a>
        </div>
    </div>
</section>

@endsection
