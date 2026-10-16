@props(['titre', 'sousTitre', 'sommaire' => [], 'docActif' => '', 'brouillon' => false])

@php
$docs = [
    'mentions-legales' => ['label' => __('Mentions légales'), 'route' => route_langue('mentions-legales')],
    'confidentialite' => ['label' => __('Confidentialité & RGPD'), 'route' => route_langue('politique-de-confidentialite')],
    'cookies' => ['label' => __('Gestion des cookies'), 'route' => route_langue('gestion-des-cookies')],
];
@endphp

{{-- HERO --}}
<section class="relative bg-cream pt-10 pb-10 overflow-hidden">
    <div class="absolute -top-20 -left-16 w-80 h-80 rounded-full bg-mint blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute top-10 -right-16 w-72 h-72 rounded-full bg-peach blur-3xl pointer-events-none -z-10"></div>

    <div class="max-w-[1240px] mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="max-w-2xl flex flex-col gap-3">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white shadow-sm text-primary w-fit">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-body text-xs uppercase tracking-widest font-extrabold">{{ __('Informations réglementaires & transparence') }}</span>
                </span>
                <h1 class="font-display text-4xl md:text-5xl text-primary-deep">{{ __($titre) }}</h1>
                <p class="font-body text-lg text-primary-deep/80 max-w-xl">{{ __($sousTitre) }}</p>
            </div>
            <div class="flex flex-col items-start md:items-end gap-2 shrink-0">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white shadow-sm text-primary-deep/80 font-body text-sm">
                    <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg>
                    <span>{{ __('Dernière mise à jour :') }} <strong class="text-primary-deep">{{ now()->translatedFormat('j F Y') }}</strong></span>
                </span>
                <span class="inline-flex items-center gap-2 text-primary font-body text-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
                    <span>{{ __('Agrément officiel PMI Val-de-Marne') }}</span>
                </span>
            </div>
        </div>

        {{-- Version anglaise : traduction de courtoisie, le texte français reste la référence juridique. --}}
        @if (app()->getLocale() !== 'fr')
        <p class="mt-6 bg-white rounded-2xl p-4 font-body text-sm text-primary-deep shadow-sm" role="note">
            {{ __('Cette page est une traduction fournie pour information. Seule la version française fait foi.') }}
            <a href="{{ App\Support\LangueSite::equivalent('fr') }}" class="font-bold text-primary underline">{{ __('Lire la version française') }}</a>
        </p>
        @endif

        {{-- Rappel visible uniquement par la direction connectée à l'espace admin, jamais par les visiteurs. --}}
        @if ($brouillon && auth()->check())
        <div class="mt-6 bg-coral/10 border border-coral/30 rounded-2xl p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-coral shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-8.18 14A2 2 0 004 21h16a2 2 0 001.89-3.14l-8.18-14a2 2 0 00-3.42 0z"/></svg>
            <p class="font-body text-sm text-primary-deep"><strong>{{ __('Note pour la direction (invisible pour les visiteurs) :') }}</strong> {{ __('ce texte est une base rédigée pour le site. Il doit être relu et validé par la direction, idéalement avec un professionnel du droit, notamment les durées de conservation des données. Les informations entre crochets restent à compléter.') }}</p>
        </div>
        @endif
    </div>

    <x-hill-divider color="mint" />
</section>

{{-- CONTENU --}}
<section class="bg-mint py-12">
    <div class="max-w-[1240px] mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        {{-- Sommaire --}}
        <aside class="lg:col-span-4 flex flex-col gap-5">
            <div class="lg:sticky lg:top-28 flex flex-col gap-5">
                <nav class="bg-white rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2 text-primary-deep">
                            <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-4-7 4V5z"/></svg>
                            <span class="font-display text-lg">{{ __('Sommaire') }}</span>
                        </div>
                        <span class="font-body text-xs uppercase text-primary-deep/75">{{ __(':sommaire articles', ['sommaire' => count($sommaire)]) }}</span>
                    </div>
                    <ul class="flex flex-col gap-1">
                        @foreach ($sommaire as $i => $item)
                        <li>
                            <a href="#section-{{ $i + 1 }}" class="flex items-center gap-2 px-3 py-2 rounded-full font-body text-sm text-primary-deep/80 hover:bg-mint/40 hover:text-primary-deep transition-all">
                                <span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0"></span>
                                <span>{{ $i + 1 }}. {{ __($item) }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    <div class="mt-4 pt-4 border-t border-mint flex flex-col gap-1.5">
                        <span class="font-body text-xs uppercase text-primary-deep/75 font-bold">{{ __('Documents associés') }}</span>
                        @foreach ($docs as $key => $doc)
                        @if ($key === $docActif)
                            <span class="px-4 py-1.5 rounded-full bg-primary text-white font-body text-sm font-bold flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                {{ $doc['label'] }}
                            </span>
                        @else
                            <a href="{{ $doc['route'] }}" class="px-4 py-1.5 rounded-full bg-cream hover:bg-mint/50 text-primary-deep/80 font-body text-sm transition-colors flex items-center justify-between">
                                <span>{{ $doc['label'] }}</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M7 7h10v10"/></svg>
                            </a>
                        @endif
                        @endforeach
                    </div>
                </nav>

                <div class="bg-peach/50 rounded-3xl p-5 flex flex-col gap-2">
                    <div class="flex items-center gap-2 text-coral font-bold font-body text-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="3"/><path stroke-linecap="round" d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6"/></svg>
                        <span>{{ __('Besoin d\'un renseignement légal ?') }}</span>
                    </div>
                    <p class="font-body text-sm text-primary-deep/80 leading-relaxed">{{ __('Notre direction reste à votre écoute pour toute demande statutaire ou convention d\'entreprise.') }}</p>
                    <div class="flex flex-col gap-1 font-body text-sm font-bold mt-1">
                        <a href="tel:{{ $etab->telephoneLien() }}" class="text-coral hover:underline inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2l2 5-2 1a11 11 0 006 6l1-2 5 2v2a2 2 0 01-2 2h-1C9 19 3 13 3 6V5z"/></svg>
                            {{ $etab->telephone() }}
                        </a>
                        <a href="mailto:{{ $etab->email() }}" class="text-primary hover:underline inline-flex items-center gap-1.5 break-all">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
                            {{ $etab->email() }}
                        </a>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Articles --}}
        <main class="lg:col-span-8 flex flex-col gap-6">
            {{ $slot }}
        </main>
    </div>
</section>

{{-- BANDEAU CONFIANCE --}}
<section class="bg-cream py-16">
    <div class="max-w-[1240px] mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach ([
            ['titre' => __('Autorisation PMI 94'), 'texte' => __('Contrôle strict des normes d\'hygiène et de sécurité petite enfance.'), 'bg' => 'bg-peach', 'col' => 'text-coral'],
            ['titre' => __('Données sécurisées'), 'texte' => __('Hébergement européen et conformité totale RGPD.'), 'bg' => 'bg-mint', 'col' => 'text-primary'],
            ['titre' => __('Transparence familiale'), 'texte' => __('Direction accessible et contrat d\'accueil clair, sans clause masquée.'), 'bg' => 'bg-sky', 'col' => 'text-primary-deep'],
        ] as $badge)
        <div class="flex items-center gap-4 p-5 rounded-3xl bg-white shadow-sm">
            <div class="w-12 h-12 rounded-full {{ $badge['bg'] }} flex items-center justify-center {{ $badge['col'] }} shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="flex flex-col">
                <span class="font-display text-base text-primary-deep">{{ $badge['titre'] }}</span>
                <span class="font-body text-sm text-primary-deep/80">{{ $badge['texte'] }}</span>
            </div>
        </div>
        @endforeach
    </div>
</section>
