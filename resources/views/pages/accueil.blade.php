@extends('layouts.app')

 
  @section('title', __('Micro-crèche à Alfortville (94) — Le Jardin de Régina'))
  @section('description', __('Micro-crèche écoresponsable à Alfortville : 14 places pour les enfants de 10 semaines à 3 ans et plus, :horairestexte, repas faits maison. Pré-inscription en ligne.', ['horairestexte' => $etab->horairesTexte()]))
  


@php
$photos = [
    ['src' => asset('images/salle-de-jeux.webp'), 'alt' => __('La salle de jeux')],
    ['src' => asset('images/jardin.webp'), 'alt' => __('Le jardin extérieur')],
    ['src' => asset('images/activites-creatives.webp'), 'alt' => __('Activités créatives')],
    ['src' => asset('images/jeux-jouets.webp'), 'alt' => __('Moment de jeu')],
    ['src' => asset('images/accueil-bienveillant.webp'), 'alt' => __('Un accueil bienveillant')],
];
@endphp

@section('content')

{{-- Petits confettis décoratifs réutilisables --}}
@php
$confettiEtoile = '<svg class="w-full h-full" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l2.2 6.8H21l-5.6 4.1 2.2 6.8L12 15.6l-5.6 4.1 2.2-6.8L3 8.8h6.8z"/></svg>';
$confettiFleur = '<svg class="w-full h-full" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2c-2 2-3 4-3 6a3 3 0 006 0c0-2-1-4-3-6z"/><circle cx="12" cy="12" r="2.4"/></svg>';
$confettiCoeur = '<svg class="w-full h-full" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21s-7-4.35-9.5-8.5C.5 8.5 2.5 5 6 5c2 0 3.5 1.5 4 2.5.5-1 2-2.5 4-2.5 3.5 0 5.5 3.5 3.5 7.5C19 16.65 12 21 12 21z"/></svg>';
$confettiSquiggle = '<svg class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M2 12c2.5-6 5-6 7.5 0s5 6 7.5 0 5-6 5 0"/></svg>';
@endphp

{{-- SECTION 1 : HERO --}}
<section class="relative bg-cream pt-10 pb-16 overflow-hidden">
    {{-- Formes organiques en fond --}}
    <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-mint blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute top-1/3 -right-20 w-[450px] h-[450px] rounded-full bg-peach blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute bottom-0 left-1/4 w-72 h-72 rounded-full bg-sky/40 blur-3xl pointer-events-none -z-10"></div>

    {{-- Confettis flottants --}}
    <div class="hidden md:block absolute top-24 left-[6%] w-8 h-8 text-sun animate-float pointer-events-none">{!! $confettiEtoile !!}</div>
    <div class="hidden md:block absolute top-1/2 left-[2%] w-6 h-6 text-coral animate-wiggle pointer-events-none">{!! $confettiFleur !!}</div>
    <div class="hidden md:block absolute bottom-16 left-[18%] w-5 h-5 rounded-full bg-sky animate-float-slow pointer-events-none"></div>
    <div class="hidden md:block absolute top-10 right-[32%] w-6 h-6 text-coral animate-bounce-soft pointer-events-none">{!! $confettiCoeur !!}</div>
    <div class="hidden md:block absolute bottom-8 left-[38%] w-10 h-10 text-primary animate-float-slower pointer-events-none">{!! $confettiSquiggle !!}</div>
    <div class="hidden md:block absolute top-1/3 right-[3%] w-4 h-4 rounded-full bg-sun animate-bounce-soft pointer-events-none"></div>

    <div class="max-w-[1240px] mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">

            <div class="lg:col-span-7 flex flex-col items-start">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white text-primary shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
                    <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold">{{ __('Micro-crèche écoresponsable · Alfortville') }}</span>
                </div>

                <h1 class="font-display text-5xl md:text-6xl text-primary-deep mt-5 leading-[1.1]">
                    {{ __('Une bonne journée au Jardin de Régina') }}
                </h1>

                <p class="font-body text-lg text-primary-deep/80 mt-5 max-w-xl leading-relaxed">
                    {{ __('Une micro-crèche à taille humaine pour les enfants de 10 semaines à 3 ans et plus : bienveillance, éveil par la nature et repas faits maison, dans un cadre doux et rassurant.') }}
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="{{ route_langue('pre-inscription') }}" class="h-[52px] px-8 rounded-full bg-coral text-white font-body font-bold text-sm flex items-center gap-2 shadow-lg hover:-translate-y-0.5 hover:opacity-90 transition-all">
                        <span>{{ __('Pré-inscrire mon enfant') }}</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ route_langue('notre-projet') }}" class="h-[52px] px-8 rounded-full bg-white text-primary font-body font-bold text-sm flex items-center justify-center shadow-sm hover:bg-mint/50 transition-all">
                        {{ __('Découvrir notre projet') }}
                    </a>
                </div>

                <div class="mt-10 pt-6 w-full flex flex-wrap gap-3 text-primary-deep font-body text-sm">
                    <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-white shadow-sm">
                        <svg class="w-4 h-4 text-coral" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21s-7-4.35-9.5-8.5C.5 8.5 2.5 5 6 5c2 0 3.5 1.5 4 2.5.5-1 2-2.5 4-2.5 3.5 0 5.5 3.5 3.5 7.5C19 16.65 12 21 12 21z"/></svg>
                        <span class="font-semibold">{{ __('12 places + 2 urgences') }}</span>
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-white shadow-sm">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 7v5l3 3"/></svg>
                        <span class="font-semibold">{{ __('Lun – Ven, :horaires', ['horaires' => $etab->horaires()]) }}</span>
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-white shadow-sm">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
                        <span class="font-semibold">{{ __('Repas faits maison') }}</span>
                    </div>
                </div>
            </div>

            {{-- Carrousel d'images, en cadre arche --}}
            <div class="lg:col-span-5 relative flex justify-center items-center"
                 x-data="{
                    slides: {{ json_encode($photos) }},
                    active: 0,
                    init() { setInterval(() => { this.active = (this.active + 1) % this.slides.length }, 3500) }
                 }">
                {{-- Coussins de couleur derrière l'arche --}}
                <div class="absolute -inset-4 bg-mint rounded-[60px] rotate-2 -z-10 blur-sm"></div>
                <div class="absolute -inset-2 bg-peach rounded-[70px] -rotate-3 -z-10"></div>
                <div class="hidden md:block absolute -top-6 -right-6 w-10 h-10 text-sun animate-wiggle">{!! $confettiEtoile !!}</div>

                <div class="w-full max-w-[420px] h-[520px] rounded-t-full rounded-b-[40px] overflow-hidden shadow-2xl relative bg-peach">
                    {{-- Rangée de photos qui glisse horizontalement --}}
                    <div class="flex h-full transition-transform duration-700 ease-in-out"
                         :style="'transform: translateX(-' + (active * 100) + '%)'">
                        <template x-for="(slide, index) in slides" :key="index">
                            <div class="w-full h-full shrink-0">
                                <img :src="slide.src" :alt="slide.alt" class="w-full h-full object-cover">
                            </div>
                        </template>
                    </div>

                    {{-- Voile en bas pour la lisibilité du badge --}}
                    <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-primary-deep/50 to-transparent pointer-events-none"></div>

                    {{-- Badge flottant --}}
                    <div class="absolute bottom-6 left-6 right-6 bg-white/90 backdrop-blur-md rounded-2xl p-4 shadow-lg flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-mint flex items-center justify-center text-primary shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="font-display text-sm text-primary-deep font-semibold truncate">{{ __('Cocon éco-labellisé') }}</span>
                            <span class="font-body text-xs text-primary-deep/80 truncate">{{ __('Matériaux sains & respect du rythme') }}</span>
                        </div>
                    </div>

                    {{-- Points de pagination --}}
                    <div class="absolute top-3 left-0 right-0 flex justify-center">
                        <template x-for="(slide, index) in slides" :key="index">
                            {{-- Zone cliquable de 24 px (confort au doigt), point visible plus petit --}}
                            <button type="button" @click="active = index"
                                    class="h-6 min-w-6 px-1 flex items-center justify-center"
                                    :aria-label="@js(__('Voir la photo')) + ' ' + (index + 1)"
                                    :aria-current="active === index ? 'true' : 'false'">
                                <span class="block h-2.5 rounded-full transition-all"
                                      :class="active === index ? 'bg-coral w-6' : 'bg-white/70 w-2.5'"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION MARQUEE : bande de photos défilantes --}}
<section class="bg-mint py-10 overflow-hidden relative">
    <div class="hidden md:block absolute top-2 left-[4%] w-6 h-6 text-sun animate-wiggle pointer-events-none z-10">{!! $confettiEtoile !!}</div>
    <div class="hidden md:block absolute bottom-2 right-[6%] w-6 h-6 text-coral animate-float pointer-events-none z-10">{!! $confettiFleur !!}</div>
    <div class="flex w-max gap-6 animate-marquee hover:[animation-play-state:paused]">
        @foreach (array_merge($photos, $photos) as $i => $photo)
        <div class="w-52 h-52 md:w-64 md:h-64 rounded-[28px] overflow-hidden shadow-lg shrink-0 border-4 border-white {{ $i % 2 === 0 ? '-rotate-2' : 'rotate-2' }}">
            <img loading="lazy" decoding="async" src="{{ $photo['src'] }}" alt="{{ $photo['alt'] }}" class="w-full h-full object-cover">
        </div>
        @endforeach
    </div>
    <x-hill-divider color="cream" />
</section>

{{-- SECTION 2 : POURQUOI NOUS CHOISIR --}}
<section class="bg-cream py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <div class="hidden md:block absolute top-14 left-[6%] w-8 h-8 text-sun animate-float pointer-events-none">{!! $confettiEtoile !!}</div>
    <div class="hidden md:block absolute top-1/2 right-[5%] w-7 h-7 text-coral animate-wiggle pointer-events-none">{!! $confettiCoeur !!}</div>
    <div class="hidden md:block absolute bottom-10 left-[12%] w-5 h-5 rounded-full bg-sky animate-bounce-soft pointer-events-none"></div>
    <div class="max-w-[1240px] mx-auto px-6 reveal relative z-10" :class="{ 'reveal-shown': shown }">
        <div class="text-center max-w-2xl mx-auto flex flex-col items-center">
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-mint/60 shadow-sm">{{ __('Pourquoi nous choisir') }}</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">{{ __('Un environnement pensé pour leur bien-être') }}</h2>
            <p class="font-body text-primary-deep/80 mt-2">{{ __('Des repères sereins et une attention personnalisée à chaque instant.') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-12">
            @foreach ([
                ['icon' => 'groups', 'titre' => __('Accueil en petit groupe'), 'texte' => __('Jusqu\'à 12 enfants, plus 2 places d\'urgence : une attention personnalisée pour chacun.'), 'bg' => 'bg-mint'],
                ['icon' => 'child', 'titre' => __('10 semaines – 3 ans et plus'), 'texte' => __('Accueil régulier, occasionnel ou d\'urgence, adapté aux besoins de chaque famille.'), 'bg' => 'bg-peach'],
                ['icon' => 'clock', 'titre' => __('Horaires étendus'), 'texte' => __(':horaires, du lundi au vendredi, pour concilier sereinement vie pro et vie de famille.', ['horaires' => $etab->horaires()]), 'bg' => 'bg-sky'],
                ['icon' => 'leaf', 'titre' => __('Repas faits maison'), 'texte' => __('Équilibrés et de saison, préparés avec des produits soigneusement choisis.'), 'bg' => 'bg-mint'],
            ] as $carte)
            <div class="bg-white rounded-3xl p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 hover:rotate-1 transition-all flex flex-col gap-3">
                <div class="w-14 h-14 rounded-2xl {{ $carte['bg'] }} flex items-center justify-center text-primary-deep">
                    @if ($carte['icon'] === 'groups')
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="8" cy="9" r="3"/><circle cx="16" cy="9" r="3"/><path stroke-linecap="round" d="M2 20c0-3 3-5 6-5s6 2 6 5M10 20c0-3 3-5 6-5s6 2 6 5"/></svg>
                    @elseif ($carte['icon'] === 'child')
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="6" r="3"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 21v-4a6 6 0 0112 0v4"/></svg>
                    @elseif ($carte['icon'] === 'clock')
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 7v5l3 3"/></svg>
                    @else
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
                    @endif
                </div>
                <h3 class="font-display text-xl text-primary-deep">{{ $carte['titre'] }}</h3>
                <p class="font-body text-sm text-primary-deep/80">{{ $carte['texte'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SECTION 3 : NOS 4 PILIERS --}}
<section class="bg-mint py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <div class="hidden md:block absolute top-10 right-[8%] w-9 h-9 text-coral animate-float pointer-events-none">{!! $confettiFleur !!}</div>
    <div class="hidden md:block absolute bottom-10 right-[20%] w-6 h-6 rounded-full bg-white animate-float-slower pointer-events-none"></div>
    <div class="hidden md:block absolute top-1/2 left-[5%] w-9 h-9 text-primary-deep animate-spin-slow pointer-events-none">{!! $confettiSquiggle !!}</div>
    <div class="hidden md:block absolute bottom-16 left-[15%] w-6 h-6 text-sun animate-bounce-soft pointer-events-none">{!! $confettiEtoile !!}</div>

    <div class="max-w-[1240px] mx-auto px-6 reveal" :class="{ 'reveal-shown': shown }">
        <div class="text-center max-w-2xl mx-auto flex flex-col items-center">
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-white shadow-sm">{{ __('Notre pédagogie') }}</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">{{ __('Nos 4 piliers d\'épanouissement') }}</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-12">
            @foreach ([
                ['n' => '01', 'titre' => __('Éveil naturel'), 'texte' => __('Le respect de l\'environnement à chaque étape, pour éveiller les sens en douceur.'), 'bg' => 'bg-white', 'tag' => 'text-primary'],
                ['n' => '02', 'titre' => __('Éducation bienveillante'), 'texte' => __('L\'autonomie encouragée à chaque instant de la journée, au rythme de chaque enfant.'), 'bg' => 'bg-peach/60', 'tag' => 'text-coral'],
                ['n' => '03', 'titre' => __('Communauté engagée'), 'texte' => __('Familles, équipe et collectivité réunies au service de l\'enfant.'), 'bg' => 'bg-sky/50', 'tag' => 'text-primary'],
                ['n' => '04', 'titre' => __('Développement durable'), 'texte' => __('Un engagement pour notre avenir, dès le plus jeune âge.'), 'bg' => 'bg-white', 'tag' => 'text-primary'],
            ] as $pilier)
            <div class="{{ $pilier['bg'] }} rounded-3xl p-8 flex flex-col gap-2 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all">
                <span class="font-body text-xs uppercase tracking-widest {{ $pilier['tag'] }} font-bold">{{ __('Pilier :n', ['n' => $pilier['n']]) }}</span>
                <h3 class="font-display text-2xl text-primary-deep">{{ $pilier['titre'] }}</h3>
                <p class="font-body text-sm text-primary-deep/80 leading-relaxed">{{ $pilier['texte'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SECTION 4 : UNE JOURNÉE AU JARDIN --}}
<section class="bg-cream py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <div class="hidden md:block absolute top-8 right-[10%] w-7 h-7 text-coral animate-float pointer-events-none">{!! $confettiFleur !!}</div>
    <div class="hidden md:block absolute bottom-6 right-[25%] w-5 h-5 rounded-full bg-sun animate-bounce-soft pointer-events-none"></div>
    <div class="hidden md:block absolute top-1/3 left-[4%] w-8 h-8 text-primary animate-wiggle pointer-events-none">{!! $confettiEtoile !!}</div>
    <div class="max-w-[1240px] mx-auto px-6 reveal" :class="{ 'reveal-shown': shown }">
        <div class="text-center max-w-2xl mx-auto flex flex-col items-center">
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-mint/60 shadow-sm">{{ __('Le rythme') }}</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">{{ __('Une journée au Jardin') }}</h2>
            <p class="font-body text-primary-deep/80 mt-2">{{ __('Pas d\'horaires rigides, mais une trame rassurante ponctuée de repères doux.') }}</p>
        </div>

        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-5 relative">
            <div class="hidden lg:block absolute top-9 left-12 right-12 h-1 bg-mint -z-0"></div>
            @foreach ([
                ['n' => 1, 'titre' => __('Accueil doux'), 'texte' => __('Transmissions avec les parents, séparation en douceur.'), 'bg' => 'bg-primary'],
                ['n' => 2, 'titre' => __('Ateliers d\'éveil'), 'texte' => __('Motricité libre, musique et découvertes sensorielles.'), 'bg' => 'bg-coral'],
                ['n' => 3, 'titre' => __('Repas partagé'), 'texte' => __('Repas faits maison, dans la bonne humeur.'), 'bg' => 'bg-primary'],
                ['n' => 4, 'titre' => __('Sieste'), 'texte' => __('Le sommeil au rythme propre à chaque enfant.'), 'bg' => 'bg-coral'],
                ['n' => 5, 'titre' => __('Goûter & retour'), 'texte' => __('Récit chaleureux de la journée avec les parents.'), 'bg' => 'bg-primary'],
            ] as $etape)
            <div class="bg-white rounded-3xl p-6 flex flex-col items-center text-center gap-2 shadow-sm relative z-10 hover:shadow-lg hover:-translate-y-1 transition-all">
                <div class="w-12 h-12 rounded-full {{ $etape['bg'] }} text-white font-display text-lg flex items-center justify-center ring-8 ring-cream">{{ $etape['n'] }}</div>
                <h3 class="font-display text-lg text-primary-deep mt-1">{{ $etape['titre'] }}</h3>
                <p class="font-body text-xs text-primary-deep/80">{{ $etape['texte'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SECTION 5 : BERCEAUX ENTREPRISE --}}
<section class="bg-cream py-16" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <div class="max-w-[1240px] mx-auto px-6 reveal" :class="{ 'reveal-shown': shown }">
        <div class="bg-primary-deep rounded-[40px] p-10 md:p-14 text-white relative overflow-hidden shadow-2xl">
            <svg class="absolute -right-16 -bottom-16 w-80 h-80 text-white/5 pointer-events-none" fill="currentColor" viewBox="0 0 200 200">
                <path d="M100,20 C120,60 160,80 190,100 C150,120 130,160 100,190 C80,150 40,130 10,100 C50,80 70,40 100,20 Z"/>
            </svg>
            <div class="hidden md:block absolute top-8 right-[30%] w-8 h-8 text-sun animate-float pointer-events-none">{!! $confettiEtoile !!}</div>
            <div class="hidden md:block absolute bottom-10 right-[12%] w-6 h-6 rounded-full bg-white/60 animate-bounce-soft pointer-events-none"></div>
            <div class="hidden md:block absolute top-1/2 right-[45%] w-5 h-5 text-mint animate-wiggle pointer-events-none">{!! $confettiFleur !!}</div>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
                <div class="lg:col-span-8 flex flex-col items-start gap-4">
                    <span class="inline-flex px-4 py-1 rounded-full bg-white/10 font-body text-xs uppercase tracking-widest font-extrabold">{{ __('Solution employeur') }}</span>
                    <h2 class="font-display text-3xl md:text-4xl leading-tight">{{ __('Réservez des berceaux pour vos salariés à Alfortville') }}</h2>
                    <p class="font-body text-white/85 max-w-xl leading-relaxed">
                        {{ __('Offrez à vos équipes une solution de garde fiable et de proximité, et soutenez la conciliation vie professionnelle / vie familiale.') }}
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <span class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-full font-body text-sm font-semibold backdrop-blur-sm">{{ __('Places garanties') }}</span>
                        <span class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-full font-body text-sm font-semibold backdrop-blur-sm">{{ __('Jusqu\'à 75 % d\'avantages fiscaux') }}</span>
                    </div>
                    <a href="{{ route_langue('berceaux-entreprise') }}" class="mt-2 h-[52px] px-8 rounded-full bg-coral text-white font-body font-bold text-sm flex items-center gap-2 shadow-lg hover:-translate-y-0.5 hover:opacity-90 transition-all">
                        {{ __('Découvrir l\'offre Berceaux entreprise') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 6 : AIDES ET TARIFS --}}
<section class="bg-cream pb-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <div class="hidden md:block absolute top-6 left-[8%] w-7 h-7 text-primary animate-float-slow pointer-events-none">{!! $confettiEtoile !!}</div>
    <div class="hidden md:block absolute bottom-4 right-[10%] w-8 h-8 text-coral animate-spin-slow pointer-events-none">{!! $confettiSquiggle !!}</div>
    <div class="hidden md:block absolute top-1/2 right-[3%] w-5 h-5 rounded-full bg-sun animate-bounce-soft pointer-events-none"></div>
    <div class="max-w-[1240px] mx-auto px-6 reveal relative z-10" :class="{ 'reveal-shown': shown }">
        <div class="bg-peach/50 rounded-[40px] p-10 md:p-14 flex flex-col items-start gap-4">
            <span class="font-body text-xs uppercase tracking-widest text-coral font-extrabold px-4 py-1 rounded-full bg-white shadow-sm">{{ __('Financement & aides') }}</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep">{{ __('Un reste à charge adapté aux revenus de votre famille') }}</h2>
            <p class="font-body text-primary-deep/80 max-w-2xl leading-relaxed">
                {{ __('Le Jardin de Régina permet aux familles de bénéficier des aides de la CAF (PAJE / CMG) ainsi que du crédit d\'impôt garde d\'enfant.') }}
            </p>
            <a href="{{ route_langue('tarifs') }}" class="mt-2 h-[52px] px-8 rounded-full bg-primary text-white font-body font-bold text-sm flex items-center gap-2 shadow-md hover:-translate-y-0.5 hover:opacity-90 transition-all">
                {{ __('Voir les tarifs et les aides') }}
            </a>
        </div>
    </div>
</section>

{{-- SECTION 7 : FAQ --}}
<section class="bg-mint py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    {{-- Décor de fond --}}
    <div class="absolute -top-20 -right-20 w-80 h-80 rounded-full bg-peach/60 blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute bottom-0 -left-16 w-72 h-72 rounded-full bg-sky/50 blur-3xl pointer-events-none -z-10"></div>
    <div class="hidden md:block absolute top-16 left-[10%] w-7 h-7 text-sun animate-wiggle pointer-events-none">{!! $confettiEtoile !!}</div>
    <div class="hidden md:block absolute bottom-24 right-[8%] w-6 h-6 text-coral animate-float pointer-events-none">{!! $confettiFleur !!}</div>
    <div class="hidden md:block absolute top-1/2 right-[4%] w-4 h-4 rounded-full bg-white animate-float-slow pointer-events-none"></div>
    <div class="hidden md:block absolute bottom-10 left-[6%] w-6 h-6 text-primary animate-bounce-soft pointer-events-none">{!! $confettiCoeur !!}</div>

    <div class="max-w-[860px] mx-auto px-6 reveal relative z-10" :class="{ 'reveal-shown': shown }">
        <div class="text-center max-w-2xl mx-auto flex flex-col items-center">
            <div class="w-14 h-14 rounded-full bg-white shadow-md flex items-center justify-center text-coral animate-float-slow">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.5 9a2.5 2.5 0 015 0c0 1.5-1.5 2-2.5 3"/><circle cx="12" cy="16.5" r="0.6" fill="currentColor"/><circle cx="12" cy="12" r="9"/></svg>
            </div>
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-white shadow-sm mt-4">{{ __('Questions fréquentes') }}</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">{{ __('Tout ce que vous souhaitez savoir') }}</h2>
            <p class="font-body text-primary-deep/80 mt-2">{{ __('Une question ? Voici les réponses les plus utiles pour préparer sereinement l\'arrivée de votre tout-petit.') }}</p>
        </div>

        <div class="mt-12 flex flex-col gap-4" x-data="{ open: 1 }">
            @php
            $iconsFaq = [
                1 => '<circle cx="12" cy="6" r="3"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 21v-4a6 6 0 0112 0v4"/>',
                2 => '<circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 7v5l3 3"/>',
                3 => '<circle cx="8" cy="9" r="3"/><circle cx="16" cy="9" r="3"/><path stroke-linecap="round" d="M2 20c0-3 3-5 6-5s6 2 6 5M10 20c0-3 3-5 6-5s6 2 6 5"/>',
                4 => '<path stroke-linecap="round" stroke-linejoin="round" d="M7 3h7l4 4v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z"/><path stroke-linecap="round" d="M9 12h6M9 16h6M14 3v4h4"/>',
                5 => '<rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10h18"/><circle cx="16" cy="14.5" r="1" fill="currentColor"/>',
                6 => '<path stroke-linecap="round" stroke-linejoin="round" fill="currentColor" d="M12 21s-7-4.35-9.5-8.5C.5 8.5 2.5 5 6 5c2 0 3.5 1.5 4 2.5.5-1 2-2.5 4-2.5 3.5 0 5.5 3.5 3.5 7.5C19 16.65 12 21 12 21z"/>',
            ];
            $couleursFaq = [1 => 'bg-mint text-primary', 2 => 'bg-peach text-coral', 3 => 'bg-sky text-primary-deep', 4 => 'bg-mint text-primary', 5 => 'bg-peach text-coral', 6 => 'bg-sky text-primary-deep'];
            @endphp
            @foreach ([
                1 => ['q' => __('Quel âge doivent avoir les enfants pour être accueillis ?'), 'r' => __('Nous accueillons les tout-petits de 10 semaines à 3 ans et plus, dans un groupe d\'âge mélangé qui favorise l\'entraide et l\'apprentissage mutuel.')],
                2 => ['q' => __('Quels sont les horaires d\'ouverture ?'), 'r' => __('Le Jardin de Régina est ouvert du lundi au vendredi, :horaires', ['horaires' => $etab->horairesTexte()]).'.'],
                3 => ['q' => __('Combien de places compte la structure ?'), 'r' => __('12 places régulières, plus 2 places dédiées à l\'accueil d\'urgence, soit 14 places au maximum.')],
                4 => ['q' => __('Comment se déroule la pré-inscription ?'), 'r' => __('La pré-inscription se fait en ligne, en 2 étapes. Notre équipe vous recontacte ensuite pour organiser une visite.')],
                5 => ['q' => __('Quelles aides financières sont disponibles ?'), 'r' => __('Vous pouvez bénéficier du Complément de libre choix du mode de garde (CMG - PAJE) versé par la CAF, ainsi que du crédit d\'impôt garde d\'enfant.')],
                6 => ['q' => __('Proposez-vous un accueil occasionnel ou d\'urgence ?'), 'r' => __('Oui, selon les disponibilités et nos 2 places d\'urgence dédiées, à la demi-journée ou à la journée complète.')],
            ] as $id => $item)
            <div class="rounded-2xl shadow-sm hover:shadow-lg transition-all overflow-hidden border-2"
                 :class="open === {{ $id }} ? 'bg-white border-coral/30' : 'bg-white border-transparent'">
                <button @click="open = (open === {{ $id }} ? null : {{ $id }})" class="w-full p-5 flex items-center gap-4 text-left">
                    <div class="w-11 h-11 rounded-full {{ $couleursFaq[$id] }} flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">{!! $iconsFaq[$id] !!}</svg>
                    </div>
                    <span class="font-display text-lg text-primary-deep flex-1">{{ $item['q'] }}</span>
                    <svg class="w-5 h-5 text-primary shrink-0 transition-transform duration-200" :class="open === {{ $id }} ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open === {{ $id }}" x-collapse class="pl-[84px] pr-5 pb-5 font-body text-sm text-primary-deep/80 leading-relaxed">
                    {{ $item['r'] }}
                </div>
            </div>
            @endforeach
        </div>

        {{-- Mini bandeau contact --}}
        <div class="mt-8 bg-white rounded-2xl shadow-sm p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-full bg-mint flex items-center justify-center text-primary shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5M21 12a9 9 0 11-4-7.5L21 3l-1 4.5A8.96 8.96 0 0121 12z"/></svg>
                </div>
                <div>
                    <p class="font-display text-base text-primary-deep">{{ __('Une autre question ?') }}</p>
                    <p class="font-body text-xs text-primary-deep/80">{{ __('Notre équipe vous répond avec plaisir.') }}</p>
                </div>
            </div>
            <a href="{{ route_langue('contact') }}" class="h-[44px] px-6 rounded-full bg-primary text-white font-body font-bold text-sm flex items-center gap-2 shadow-md hover:-translate-y-0.5 hover:opacity-90 transition-all whitespace-nowrap">
                {{ __('Nous contacter') }}
            </a>
        </div>
    </div>
</section>

{{-- SECTION 8 : CTA FINAL --}}
<section class="bg-cream py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <div class="hidden md:block absolute top-10 left-[15%] w-7 h-7 text-sun animate-wiggle pointer-events-none">{!! $confettiEtoile !!}</div>
    <div class="hidden md:block absolute bottom-14 right-[15%] w-6 h-6 text-primary animate-float pointer-events-none">{!! $confettiFleur !!}</div>
    <div class="hidden md:block absolute top-1/2 left-[6%] w-8 h-8 text-coral animate-spin-slow pointer-events-none">{!! $confettiSquiggle !!}</div>
    <div class="hidden md:block absolute bottom-8 left-[20%] w-5 h-5 rounded-full bg-sky animate-bounce-soft pointer-events-none"></div>

    <div class="max-w-[720px] mx-auto px-6 text-center flex flex-col items-center gap-4 reveal" :class="{ 'reveal-shown': shown }">
        <div class="w-16 h-16 rounded-full bg-peach flex items-center justify-center text-coral animate-float-slow">
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21s-7-4.35-9.5-8.5C.5 8.5 2.5 5 6 5c2 0 3.5 1.5 4 2.5.5-1 2-2.5 4-2.5 3.5 0 5.5 3.5 3.5 7.5C19 16.65 12 21 12 21z"/></svg>
        </div>
        <h2 class="font-display text-3xl md:text-4xl text-primary-deep">{{ __('Prêt à rejoindre notre jardin ?') }}</h2>
        <p class="font-body text-primary-deep/80 max-w-lg">{{ __('Les places se réservent tôt. Venez visiter notre cocon et échanger avec notre équipe.') }}</p>
        <div class="flex flex-wrap items-center justify-center gap-4 mt-2">
            <a href="{{ route_langue('pre-inscription') }}" class="h-[52px] px-8 rounded-full bg-coral text-white font-body font-bold text-sm flex items-center gap-2 shadow-lg hover:-translate-y-0.5 hover:opacity-90 transition-all">
                {{ __('Pré-inscrire mon enfant') }}
            </a>
            <a href="tel:{{ $etab->telephoneLien() }}" class="h-[52px] px-8 rounded-full bg-white text-primary font-body font-bold text-sm flex items-center justify-center gap-2 shadow-sm hover:bg-mint/40 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2l2 5-2 1a11 11 0 006 6l1-2 5 2v2a2 2 0 01-2 2h-1C9 19 3 13 3 6V5z"/></svg>
                {{ $etab->telephone() }}
            </a>
        </div>
    </div>
</section>

@endsection
