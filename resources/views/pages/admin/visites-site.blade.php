@extends('layouts.admin')

@section('title', 'Visites du site — Espace admin')

@section('content')
@php
    // Paramètres d'URL de la période affichée (réutilisés par les liens et l'export).
    $paramsPeriode = array_filter([
        'periode' => $stats->periode,
        'du' => $stats->periode === 'perso' ? $stats->debut->toDateString() : null,
        'au' => $stats->periode === 'perso' ? $stats->fin->toDateString() : null,
    ]);
    $paramsCourants = $paramsPeriode + ['regroupement' => $stats->regroupement];
    $paramsFiltre = $seulementFormulaires ? ['formulaires' => 1] : [];

    $lienPeriode = function (string $cle) use ($stats, $paramsFiltre) {
        $regroupement = ($cle === '12m' && $stats->regroupement === 'jour') ? 'mois' : $stats->regroupement;

        return route('admin.visites-site', ['periode' => $cle, 'regroupement' => $regroupement] + $paramsFiltre);
    };

    $maxSerie = max(1, (int) $serie->max('visites'));
    $pasEtiquettes = max(1, (int) ceil($serie->count() / 10));
    $maxSources = max(1, (int) $sources->max('visites'));
    $maxPages = max(1, (int) $pages->max('vues'));
    $nomRegroupement = ['jour' => 'jour', 'semaine' => 'semaine', 'mois' => 'mois'][$stats->regroupement];

    $config = [
        'visites' => $visites,
        'urlBase' => route('admin.visites-site'),
    ];
@endphp

<script>
    function visitesSite(config) {
        return {
            visites: config.visites,
            urlBase: config.urlBase,
            visiteOuverteId: null,
            survol: null,

            get visiteOuverte() {
                return this.visites.find(v => v.id === this.visiteOuverteId) || null;
            },

            sourceClasses(source) {
                if (source === 'direct') return 'bg-cream text-primary-deep/80 border border-primary-deep/15';
                if (source === 'autre') return 'bg-peach text-primary-deep';
                return 'bg-mint text-primary';
            },
        };
    }
</script>

<x-admin-sidebar actif="journal" />

<div class="pl-[240px] flex flex-col min-h-screen w-full bg-cream"
     x-data="visitesSite(@js($config))"
     @keydown.escape.window="visiteOuverteId = null">

    <x-admin-topbar recherchePlaceholder="Rechercher..." />

    <main class="w-full pt-16 p-8 flex-1">
        <div class="relative w-full max-w-[1136px] mx-auto pb-10">
            <div class="absolute -top-12 right-24 w-96 h-96 rounded-full bg-mint/50 blur-3xl pointer-events-none -z-10"></div>

            {{-- En-tête --}}
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-6">
                <div class="flex flex-col">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 010 18M12 3a14 14 0 000 18"/></svg>
                        <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold">Audience</span>
                    </div>
                    <h1 class="font-display text-3xl text-primary-deep">Journal des visites</h1>
                    <p class="font-body text-primary-deep/80 mt-1">Fréquentation du site internet : combien de personnes le consultent, d'où elles viennent, et qui a envoyé un formulaire.</p>
                </div>
                <a href="{{ route('admin.visites-site.export', $paramsCourants) }}" class="h-[48px] px-6 rounded-full bg-coral text-white font-body font-bold text-sm shadow-lg hover:-translate-y-0.5 hover:opacity-90 transition-all flex items-center gap-2 shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v13m0 0l-4-4m4 4l4-4M5 21h14"/></svg>
                    Exporter le rapport Excel
                </a>
            </div>

            <x-journal-onglets actif="site" />

            {{-- Filtres : période + regroupement --}}
            <div class="bg-white rounded-3xl p-5 shadow-sm flex flex-col gap-4 mb-6">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-2">
                        @foreach (\App\Support\StatistiquesVisites::PERIODES as $cle => $libelle)
                            @continue($cle === 'perso')
                            <a href="{{ $lienPeriode($cle) }}"
                               class="px-4 py-1.5 rounded-full font-body text-sm transition-all {{ $stats->periode === $cle ? 'bg-primary text-white shadow-sm' : 'bg-cream text-primary-deep hover:bg-mint/40' }}">{{ $libelle }}</a>
                        @endforeach
                    </div>
                    <form method="GET" action="{{ route('admin.visites-site') }}" class="flex flex-wrap items-center gap-2 font-body text-sm text-primary-deep/80">
                        <input type="hidden" name="periode" value="perso">
                        <input type="hidden" name="regroupement" value="{{ $stats->regroupement }}">
                        @if ($seulementFormulaires)
                            <input type="hidden" name="formulaires" value="1">
                        @endif
                        <label for="du">Du</label>
                        <input type="date" id="du" name="du" value="{{ $stats->debut->toDateString() }}" required class="h-9 px-3 bg-cream rounded-full text-primary-deep focus:outline-none focus:ring-2 focus:ring-primary {{ $stats->periode === 'perso' ? 'ring-2 ring-primary' : '' }}">
                        <label for="au">au</label>
                        <input type="date" id="au" name="au" value="{{ $stats->fin->toDateString() }}" required class="h-9 px-3 bg-cream rounded-full text-primary-deep focus:outline-none focus:ring-2 focus:ring-primary {{ $stats->periode === 'perso' ? 'ring-2 ring-primary' : '' }}">
                        <button type="submit" class="h-9 px-4 rounded-full bg-primary text-white font-bold hover:opacity-90 transition-all">Filtrer</button>
                    </form>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-mint pt-4">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="font-body text-xs font-bold text-primary-deep/75 mr-1">Regrouper :</span>
                        @foreach (\App\Support\StatistiquesVisites::REGROUPEMENTS as $cle => $libelle)
                            <a href="{{ route('admin.visites-site', $paramsPeriode + ['regroupement' => $cle] + $paramsFiltre) }}"
                               class="px-3 py-1 rounded-full font-body text-xs font-bold transition-all {{ $stats->regroupement === $cle ? 'bg-primary text-white' : 'bg-cream text-primary-deep hover:bg-mint/40' }}">{{ $libelle }}</a>
                        @endforeach
                    </div>
                    <span class="font-body text-sm text-primary-deep/75">Période affichée : <strong class="text-primary-deep">{{ $stats->libellePeriode() }}</strong></span>
                </div>
            </div>

            @if ($errors->any())
                <div class="bg-coral/10 text-coral rounded-2xl px-5 py-3 mb-6 font-body text-sm font-bold">{{ $errors->first() }}</div>
            @endif

            {{-- Chiffres clés --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                @foreach ([
                    ['titre' => 'Visites', 'valeur' => $resume['visites'], 'detail' => $resume['visiteurs'].' visiteur(s) unique(s)'],
                    ['titre' => 'Nouveaux visiteurs', 'valeur' => $resume['nouveaux'], 'detail' => ($resume['visites'] ? round($resume['nouveaux'] / $resume['visites'] * 100) : 0).' % des visites'],
                    ['titre' => 'Formulaires envoyés', 'valeur' => $resume['formulaires'], 'detail' => $resume['tauxConversion'].' % des visites aboutissent à un formulaire'],
                    ['titre' => 'Pages par visite', 'valeur' => str_replace('.', ',', (string) $resume['pagesParVisite']), 'detail' => $resume['mobilePct'].' % des visites sur mobile'],
                ] as $carte)
                    <div class="bg-white rounded-3xl p-5 shadow-sm flex flex-col gap-1">
                        <span class="font-body text-xs uppercase tracking-widest text-primary-deep/75 font-extrabold">{{ $carte['titre'] }}</span>
                        <span class="font-display text-4xl text-primary-deep">{{ $carte['valeur'] }}</span>
                        <span class="font-body text-xs text-primary-deep/75">{{ $carte['detail'] }}</span>
                    </div>
                @endforeach
            </div>

            @if ($resume['visites'] === 0)
                <div class="bg-mint/40 rounded-3xl p-5 mb-6 flex items-start gap-3 font-body text-sm text-primary-deep/80">
                    <svg class="w-5 h-5 text-primary shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 8h.01M11 12h1v4h1"/></svg>
                    <p>Aucune visite sur cette période. Les visites du site public sont comptées automatiquement. Ne sont <strong>pas</strong> comptés : les robots des moteurs de recherche, les personnes connectées à l'espace admin, et les visiteurs qui ont refusé les statistiques.</p>
                </div>
            @endif

            {{-- Graphique : visites par jour / semaine / mois --}}
            <div class="bg-white rounded-3xl p-6 shadow-sm mb-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-display text-xl text-primary-deep">Visites par {{ $nomRegroupement }}</h2>
                    <span class="font-body text-xs text-primary-deep/75">Survolez une barre pour le détail</span>
                </div>
                <div class="flex gap-3">
                    <div class="flex flex-col justify-between h-48 font-body text-xs text-primary-deep/40 text-right w-6 shrink-0 -mt-1.5">
                        <span>{{ $maxSerie }}</span>
                        <span>0</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="relative h-48 border-b border-primary-deep/15 flex items-end gap-[2px]">
                            <div class="absolute inset-x-0 top-0 border-t border-dashed border-primary-deep/10 pointer-events-none"></div>
                            @foreach ($serie as $i => $point)
                                <div class="relative flex-1 h-full flex items-end group cursor-default"
                                     @mouseenter="survol = {{ $i }}" @mouseleave="survol = null">
                                    <div class="w-full rounded-t-[4px] transition-opacity {{ $point['visites'] ? 'bg-primary' : '' }}"
                                         :class="survol !== null && survol !== {{ $i }} ? 'opacity-40' : ''"
                                         style="height: {{ round($point['visites'] / $maxSerie * 100, 2) }}%"></div>
                                    <div x-show="survol === {{ $i }}" x-cloak
                                         class="absolute bottom-full mb-2 z-20 bg-primary-deep text-white rounded-2xl px-3 py-2 font-body text-xs shadow-lg whitespace-nowrap pointer-events-none {{ $i < $serie->count() / 2 ? 'left-0' : 'right-0' }}">
                                        <p class="font-bold mb-0.5">{{ $point['libelle'] }}</p>
                                        <p>{{ $point['visites'] }} visite(s) · {{ $point['visiteurs'] }} visiteur(s) unique(s)</p>
                                        <p>{{ $point['pagesVues'] }} page(s) vue(s) · {{ $point['formulaires'] }} formulaire(s)</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex gap-[2px] mt-2">
                            @foreach ($serie as $i => $point)
                                <span class="flex-1 min-w-0 text-center font-body text-[10px] text-primary-deep/75 truncate">{{ $i % $pasEtiquettes === 0 ? $point['libelleCourt'] : '' }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Provenance + pages + appareils --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-3xl p-6 shadow-sm">
                    <h2 class="font-display text-xl text-primary-deep mb-1">D'où viennent les visiteurs</h2>
                    <p class="font-body text-xs text-primary-deep/75 mb-4">Google ne transmet pas les mots-clés recherchés : ils sont visibles dans Google Search Console.</p>
                    <ul class="flex flex-col gap-3">
                        @forelse ($sources as $source)
                            <li class="flex flex-col gap-1">
                                <div class="flex items-center justify-between font-body text-sm">
                                    <span class="text-primary-deep font-semibold">{{ $source['libelle'] }}</span>
                                    <span class="text-primary-deep/75">{{ $source['visites'] }} <span class="text-primary-deep/40">· {{ str_replace('.', ',', (string) $source['pct']) }} %</span></span>
                                </div>
                                <div class="h-2 rounded-full bg-mint/50 overflow-hidden">
                                    <div class="h-full rounded-full bg-primary" style="width: {{ round($source['visites'] / $maxSources * 100, 2) }}%"></div>
                                </div>
                            </li>
                        @empty
                            <li class="font-body text-sm text-primary-deep/75">Aucune donnée sur la période.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="bg-white rounded-3xl p-6 shadow-sm flex flex-col gap-6">
                    <div>
                        <h2 class="font-display text-xl text-primary-deep mb-4">Pages les plus consultées</h2>
                        <ul class="flex flex-col gap-3">
                            @forelse ($pages as $page)
                                <li class="flex flex-col gap-1">
                                    <div class="flex items-center justify-between font-body text-sm">
                                        <span class="text-primary-deep font-semibold">{{ $page['page'] }}</span>
                                        <span class="text-primary-deep/75">{{ $page['vues'] }} vue(s)</span>
                                    </div>
                                    <div class="h-2 rounded-full bg-mint/50 overflow-hidden">
                                        <div class="h-full rounded-full bg-primary" style="width: {{ round($page['vues'] / $maxPages * 100, 2) }}%"></div>
                                    </div>
                                </li>
                            @empty
                                <li class="font-body text-sm text-primary-deep/75">Aucune donnée sur la période.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="border-t border-mint pt-5">
                        <h3 class="font-body text-xs uppercase tracking-widest text-primary-deep/75 font-extrabold mb-3">Appareils</h3>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach ($appareils as $appareil)
                                <div class="bg-cream rounded-2xl p-3 text-center">
                                    <p class="font-display text-2xl text-primary-deep">{{ str_replace('.', ',', (string) $appareil['pct']) }} %</p>
                                    <p class="font-body text-xs text-primary-deep/75">{{ $appareil['libelle'] }} · {{ $appareil['visites'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tableau par période (même données que le graphique) --}}
            <div class="bg-white rounded-3xl shadow-sm overflow-hidden mb-6" x-data="{ ouvert: {{ $serie->count() <= 31 ? 'true' : 'false' }} }">
                <button type="button" @click="ouvert = !ouvert" class="w-full px-6 py-4 flex items-center justify-between hover:bg-mint/20 transition-colors">
                    <h2 class="font-display text-xl text-primary-deep">Détail par {{ $nomRegroupement }}</h2>
                    <svg class="w-5 h-5 text-primary-deep/75 transition-transform" :class="ouvert ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="ouvert" x-collapse class="overflow-x-auto">
                    <table class="w-full font-body text-sm">
                        <thead class="bg-mint/40 text-primary-deep/80 text-xs uppercase tracking-wider">
                            <tr>
                                <th class="text-left px-6 py-3 font-extrabold">{{ ucfirst($nomRegroupement) }}</th>
                                <th class="text-right px-4 py-3 font-extrabold">Visites</th>
                                <th class="text-right px-4 py-3 font-extrabold">Visiteurs uniques</th>
                                <th class="text-right px-4 py-3 font-extrabold">Pages vues</th>
                                <th class="text-right px-4 py-3 font-extrabold">Formulaires</th>
                                <th class="text-right px-6 py-3 font-extrabold">Conversion</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-mint">
                            @foreach ($serie->reverse() as $point)
                                <tr class="{{ $point['visites'] ? 'text-primary-deep' : 'text-primary-deep/35' }}">
                                    <td class="px-6 py-2.5">{{ $point['libelle'] }}</td>
                                    <td class="text-right px-4 py-2.5 font-bold">{{ $point['visites'] }}</td>
                                    <td class="text-right px-4 py-2.5">{{ $point['visiteurs'] }}</td>
                                    <td class="text-right px-4 py-2.5">{{ $point['pagesVues'] }}</td>
                                    <td class="text-right px-4 py-2.5">{{ $point['formulaires'] }}</td>
                                    <td class="text-right px-6 py-2.5">{{ str_replace('.', ',', (string) $point['tauxConversion']) }} %</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-cream font-bold text-primary-deep">
                            <tr>
                                <td class="px-6 py-3">Total</td>
                                <td class="text-right px-4 py-3">{{ $resume['visites'] }}</td>
                                <td class="text-right px-4 py-3">{{ $resume['visiteurs'] }}</td>
                                <td class="text-right px-4 py-3">{{ $resume['pagesVues'] }}</td>
                                <td class="text-right px-4 py-3">{{ $resume['formulaires'] }}</td>
                                <td class="text-right px-6 py-3">{{ str_replace('.', ',', (string) $resume['tauxConversion']) }} %</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Liste des visites --}}
            <div class="bg-white rounded-3xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 flex flex-wrap items-center justify-between gap-3 border-b border-mint">
                    <h2 class="font-display text-xl text-primary-deep">
                        {{ $seulementFormulaires ? 'Visites avec formulaire' : 'Toutes les visites' }}
                        <span class="font-body text-sm text-primary-deep/75">({{ $liste->total() }})</span>
                    </h2>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.visites-site', $paramsCourants) }}" class="px-3 py-1 rounded-full font-body text-xs font-bold transition-all {{ ! $seulementFormulaires ? 'bg-primary text-white' : 'bg-cream text-primary-deep hover:bg-mint/40' }}">Toutes</a>
                        <a href="{{ route('admin.visites-site', $paramsCourants + ['formulaires' => 1]) }}" class="px-3 py-1 rounded-full font-body text-xs font-bold transition-all {{ $seulementFormulaires ? 'bg-primary text-white' : 'bg-cream text-primary-deep hover:bg-mint/40' }}">Avec formulaire</a>
                    </div>
                </div>

                <ul class="divide-y divide-mint">
                    <template x-for="v in visites" :key="v.id">
                        <li>
                            <button type="button" @click="visiteOuverteId = v.id" class="w-full text-left px-6 py-4 flex items-center gap-4 hover:bg-mint/20 transition-colors" :class="visiteOuverteId === v.id ? 'bg-mint/30' : ''">
                                <div class="w-20 shrink-0">
                                    <p class="font-display text-base text-primary" x-text="v.heure"></p>
                                    <p class="font-body text-xs text-primary-deep/75" x-text="v.dateCourte"></p>
                                </div>
                                <div class="min-w-0 flex-1 flex flex-col gap-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-body text-xs font-bold" :class="sourceClasses(v.source)" x-text="v.provenance"></span>
                                        <span class="font-body text-xs text-primary-deep/75" x-text="v.appareil + ' · ' + v.pages + ' page(s) · ' + v.duree"></span>
                                        <span x-show="v.nouveau" class="font-body text-[10px] uppercase tracking-wider font-extrabold text-primary/70">Nouveau</span>
                                    </div>
                                    <p class="font-body text-xs text-primary-deep/40 truncate" x-text="v.parcours.map(p => p.page).join(' → ')"></p>
                                </div>
                                <template x-if="v.formulaires.length">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-coral text-white font-body text-xs font-bold shrink-0 max-w-[220px]">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        <span class="truncate" x-text="v.formulaires[0].type + (v.formulaires[0].nom ? ' · ' + v.formulaires[0].nom : '')"></span>
                                    </span>
                                </template>
                            </button>
                        </li>
                    </template>
                </ul>
                <div x-show="visites.length === 0" class="py-10 text-center font-body text-primary-deep/75">Aucune visite à afficher.</div>

                @if ($liste->hasPages())
                    <div class="px-6 py-4 border-t border-mint flex items-center justify-between font-body text-sm">
                        @if ($liste->onFirstPage())
                            <span class="px-4 py-1.5 rounded-full bg-cream text-primary-deep/30">← Plus récentes</span>
                        @else
                            <a href="{{ $liste->previousPageUrl() }}" class="px-4 py-1.5 rounded-full bg-cream text-primary-deep hover:bg-mint/40 transition-all">← Plus récentes</a>
                        @endif
                        <span class="text-primary-deep/75">Page {{ $liste->currentPage() }} sur {{ $liste->lastPage() }}</span>
                        @if ($liste->hasMorePages())
                            <a href="{{ $liste->nextPageUrl() }}" class="px-4 py-1.5 rounded-full bg-cream text-primary-deep hover:bg-mint/40 transition-all">Plus anciennes →</a>
                        @else
                            <span class="px-4 py-1.5 rounded-full bg-cream text-primary-deep/30">Plus anciennes →</span>
                        @endif
                    </div>
                @endif
            </div>

            <p class="mt-6 font-body text-xs text-primary-deep/40 italic">
                Statistiques anonymes (aucune adresse IP enregistrée), conservées 25 mois puis supprimées automatiquement.
                Seules les visites qui se terminent par un formulaire sont reliées à une identité, celle que la personne a elle-même saisie.
            </p>
        </div>
    </main>

    {{-- TIROIR : détail d'une visite --}}
    <div x-show="visiteOuverteId" x-cloak @click="visiteOuverteId = null" class="fixed inset-0 bg-primary-deep/40 backdrop-blur-sm z-50" x-transition.opacity></div>

    <aside x-show="visiteOuverteId" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" class="fixed top-0 right-0 h-full w-full sm:w-[460px] bg-white shadow-2xl z-50 overflow-y-auto flex flex-col" style="display: none;">
        <template x-if="visiteOuverte">
            <div class="flex flex-col h-full">
                <div class="p-6 pb-4 border-b border-mint bg-cream/60 sticky top-0 backdrop-blur-md z-10">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold">Visite du site</span>
                        <button type="button" @click="visiteOuverteId = null" class="w-8 h-8 rounded-full flex items-center justify-center text-primary-deep/75 hover:bg-mint/40 hover:text-primary-deep transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <h2 class="font-display text-xl text-primary-deep" x-text="visiteOuverte.date"></h2>
                    <span class="font-body text-sm text-primary-deep/75" x-text="'Arrivée à ' + visiteOuverte.heure + ' · ' + visiteOuverte.duree"></span>
                </div>

                <div class="p-6 flex-1 flex flex-col gap-6">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-cream rounded-2xl p-3">
                            <p class="font-body text-xs text-primary-deep/75">Provenance</p>
                            <p class="font-body text-sm font-bold text-primary-deep" x-text="visiteOuverte.provenance"></p>
                        </div>
                        <div class="bg-cream rounded-2xl p-3">
                            <p class="font-body text-xs text-primary-deep/75">Appareil</p>
                            <p class="font-body text-sm font-bold text-primary-deep" x-text="visiteOuverte.appareil"></p>
                            <p class="font-body text-xs text-primary-deep/75" x-text="visiteOuverte.navigateur"></p>
                        </div>
                        <div class="bg-cream rounded-2xl p-3">
                            <p class="font-body text-xs text-primary-deep/75">Visiteur</p>
                            <p class="font-body text-sm font-bold text-primary-deep" x-text="visiteOuverte.nouveau ? 'Première visite' : 'Déjà venu'"></p>
                        </div>
                        <div class="bg-cream rounded-2xl p-3">
                            <p class="font-body text-xs text-primary-deep/75">Pages vues</p>
                            <p class="font-body text-sm font-bold text-primary-deep" x-text="visiteOuverte.pages"></p>
                        </div>
                    </div>

                    {{-- Formulaires envoyés --}}
                    <template x-for="f in visiteOuverte.formulaires" :key="f.numero + f.envoyeA">
                        <div class="rounded-2xl border-2 border-coral/30 bg-peach/40 p-4 flex flex-col gap-3">
                            <div class="flex items-center justify-between gap-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-coral text-white font-body text-xs font-bold">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span x-text="f.type"></span>
                                </span>
                                <span class="font-body text-xs text-primary-deep/75" x-text="(f.numero || '') + ' · envoyé à ' + f.envoyeA"></span>
                            </div>
                            <template x-if="f.nom">
                                <div class="flex flex-col gap-1 font-body text-sm text-primary-deep">
                                    <p class="font-bold" x-text="f.nom"></p>
                                    <a x-show="f.email" :href="'mailto:' + f.email" class="text-primary hover:underline" x-text="f.email"></a>
                                    <a x-show="f.tel" :href="'tel:' + f.tel" class="text-primary hover:underline" x-text="f.tel"></a>
                                </div>
                            </template>
                            <dl x-show="Object.keys(f.details).length" class="grid grid-cols-[auto_1fr] gap-x-3 gap-y-1 font-body text-xs">
                                <template x-for="(valeur, cle) in f.details" :key="cle">
                                    <div class="contents">
                                        <dt class="text-primary-deep/75" x-text="cle"></dt>
                                        <dd class="text-primary-deep font-semibold" x-text="valeur"></dd>
                                    </div>
                                </template>
                            </dl>
                            <a x-show="f.lien" :href="f.lien" class="self-start font-body text-xs font-bold text-coral hover:underline">Voir le dossier dans l'espace admin →</a>
                        </div>
                    </template>

                    {{-- Parcours --}}
                    <div>
                        <p class="font-body text-xs font-bold text-primary-deep/75 uppercase tracking-widest mb-3">Parcours sur le site</p>
                        <ol class="relative border-l-2 border-mint ml-2 flex flex-col gap-3">
                            <template x-for="(etape, index) in visiteOuverte.parcours" :key="index">
                                <li class="pl-5 relative">
                                    <span class="absolute -left-[7px] top-1.5 w-3 h-3 rounded-full ring-2 ring-white" :class="index === 0 ? 'bg-coral' : 'bg-primary'"></span>
                                    <p class="font-body text-sm text-primary-deep font-semibold" x-text="etape.page"></p>
                                    <p class="font-body text-xs text-primary-deep/75" x-text="etape.heure + (index === 0 ? ' · page d\'arrivée' : '')"></p>
                                </li>
                            </template>
                        </ol>
                    </div>
                </div>

                <div class="p-5 border-t border-mint bg-cream sticky bottom-0">
                    <form method="POST" :action="urlBase + '/' + visiteOuverte.id" onsubmit="return confirm('Supprimer cette visite des statistiques ? Le formulaire éventuellement envoyé n\'est pas supprimé.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full font-body text-xs font-bold text-coral hover:underline">Supprimer cette visite des statistiques</button>
                    </form>
                </div>
            </div>
        </template>
    </aside>
</div>
@endsection
