@extends('layouts.admin')

@section('title', 'Journal des visites — Espace admin')

@section('content')
@php
    $aujourdhui = now()->toDateString();

    $periodes = [
        'Tout' => [],
        "Aujourd'hui" => ['du' => $aujourdhui, 'au' => $aujourdhui],
        '7 prochains jours' => ['du' => $aujourdhui, 'au' => now()->addDays(6)->toDateString()],
        'À venir' => ['du' => $aujourdhui],
    ];
    $filtresActifs = array_filter($filtres);

    $config = [
        'journees' => $journees,
        'dossiers' => $dossiers,
        'aujourdhui' => $aujourdhui,
        'urlBase' => route('admin.journal-visites'),
        'ancien' => session()->hasOldInput() ? old() : null,
        'erreurs' => $errors->all(),
    ];

    $champ = 'w-full h-11 px-4 bg-cream rounded-2xl font-body text-sm text-primary-deep focus:outline-none focus:ring-2 focus:ring-primary';
    $etiquette = 'font-body text-xs font-bold text-primary-deep/80 mb-1 block';
@endphp

<script>
    function journalVisites(config) {
        return {
            journees: config.journees,
            dossiers: config.dossiers,
            urlBase: config.urlBase,
            erreurs: config.erreurs,
            recherche: '',
            filtreStatut: '',
            tiroirOuvert: false,
            mode: 'creation',
            form: {},

            init() {
                this.form = this.formVide();
                // Après une erreur de validation : on rouvre le tiroir avec ce qui avait été saisi.
                if (config.ancien) {
                    this.form = Object.assign(this.formVide(), config.ancien);
                    this.mode = config.ancien._visite_id ? 'edition' : 'creation';
                    this.tiroirOuvert = true;
                }
            },

            formVide() {
                return {
                    _visite_id: '', dossier: '', type: 'preinscription',
                    visit_date: config.aujourdhui, visit_time: '10:00',
                    visitor_name: '', phone: '', email: '',
                    status: 'a_confirmer', internal_note: '',
                };
            },

            nouvelleVisite() {
                this.mode = 'creation';
                this.form = this.formVide();
                this.erreurs = [];
                this.tiroirOuvert = true;
            },

            ouvrir(it) {
                this.mode = 'edition';
                this.erreurs = [];
                this.form = {
                    _visite_id: it.dbId, dossier: it.dossierValeur || '', type: it.type,
                    visit_date: it.date, visit_time: it.heureBrute,
                    visitor_name: it.nom, phone: it.tel || '', email: it.email || '',
                    status: it.statut, internal_note: it.note || '',
                };
                this.tiroirOuvert = true;
            },

            remplirDepuisDossier() {
                if (this.mode !== 'creation') return;
                const d = this.dossiers.find(d => d.valeur === this.form.dossier);
                if (!d) return;
                this.form.visitor_name = d.nom;
                this.form.phone = d.tel || '';
                this.form.email = d.email || '';
                this.form.type = d.type;
            },

            get actionFormulaire() {
                return this.mode === 'edition' ? this.urlBase + '/' + this.form._visite_id : this.urlBase;
            },

            get journeesFiltrees() {
                const q = this.recherche.toLowerCase();
                return this.journees
                    .map(j => ({
                        ...j,
                        items: j.items.filter(it => {
                            if (this.filtreStatut && it.statut !== this.filtreStatut) return false;
                            if (q && !(it.nom + ' ' + (it.dossier || '') + ' ' + it.typeLabel).toLowerCase().includes(q)) return false;
                            return true;
                        }),
                    }))
                    .filter(j => j.items.length > 0);
            },

            statutClasses(statut) {
                return {
                    a_confirmer: 'bg-peach text-coral',
                    confirmee: 'bg-mint text-primary',
                    realisee: 'bg-primary text-white',
                    absent: 'bg-coral/15 text-coral',
                    annulee: 'bg-cream text-primary-deep/75 border border-primary-deep/20 line-through',
                }[statut];
            },

            statutLabel(statut) {
                return {
                    a_confirmer: 'À confirmer', confirmee: 'Confirmée', realisee: 'Réalisée',
                    absent: 'Absent', annulee: 'Annulée',
                }[statut];
            },
        };
    }
</script>

<x-admin-sidebar actif="journal" />

<div class="pl-[240px] flex flex-col min-h-screen w-full bg-cream"
     x-data="journalVisites(@js($config))"
     @keydown.escape.window="tiroirOuvert = false">

    <x-admin-topbar recherche="recherche" recherchePlaceholder="Rechercher une famille, un dossier..." />

    <main class="w-full pt-16 p-8 flex-1">
        <div class="relative w-full max-w-[1136px] mx-auto pb-10">
            <div class="absolute -top-12 right-24 w-96 h-96 rounded-full bg-mint/50 blur-3xl pointer-events-none -z-10"></div>

            {{-- En-tête --}}
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-6">
                <div class="flex flex-col">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg>
                        <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold">Agenda</span>
                    </div>
                    <h1 class="font-display text-3xl text-primary-deep">Journal des visites</h1>
                    <p class="font-body text-primary-deep/80 mt-1">Visites et entretiens au jour le jour, liés ou non à un dossier.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <a href="{{ route('admin.visites.export', $filtresActifs) }}" class="h-[48px] px-6 rounded-full bg-white text-primary font-body font-bold text-sm shadow-sm hover:bg-mint/30 transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v13m0 0l-4-4m4 4l4-4M5 21h14"/></svg>
                        Exporter en Excel
                    </a>
                    <button type="button" @click="nouvelleVisite()" class="h-[48px] px-6 rounded-full bg-coral text-white font-body font-bold text-sm shadow-lg hover:-translate-y-0.5 hover:opacity-90 transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 5v14M5 12h14"/></svg>
                        Nouvelle visite
                    </button>
                </div>
            </div>

            <x-journal-onglets actif="rendez-vous" />

            {{-- Filtres --}}
            <div class="bg-white rounded-3xl p-5 shadow-sm flex flex-col gap-4 mb-6">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-2">
                        @foreach ($periodes as $libelle => $params)
                            <a href="{{ route('admin.journal-visites', $params) }}"
                               class="px-4 py-1.5 rounded-full font-body text-sm transition-all {{ $filtresActifs == $params ? 'bg-primary text-white shadow-sm' : 'bg-cream text-primary-deep hover:bg-mint/40' }}">{{ $libelle }}</a>
                        @endforeach
                    </div>
                    <form method="GET" action="{{ route('admin.journal-visites') }}" class="flex flex-wrap items-center gap-2 font-body text-sm text-primary-deep/80">
                        <label for="du">Du</label>
                        <input type="date" id="du" name="du" value="{{ $filtres['du'] ?? '' }}" class="h-9 px-3 bg-cream rounded-full text-primary-deep focus:outline-none focus:ring-2 focus:ring-primary">
                        <label for="au">au</label>
                        <input type="date" id="au" name="au" value="{{ $filtres['au'] ?? '' }}" class="h-9 px-3 bg-cream rounded-full text-primary-deep focus:outline-none focus:ring-2 focus:ring-primary">
                        <button type="submit" class="h-9 px-4 rounded-full bg-primary text-white font-bold hover:opacity-90 transition-all">Filtrer</button>
                    </form>
                </div>
                <div class="flex flex-wrap items-center gap-2 border-t border-mint pt-4">
                    <span class="font-body text-xs font-bold text-primary-deep/75 mr-1">Statut :</span>
                    <button type="button" @click="filtreStatut = ''" class="px-3 py-1 rounded-full font-body text-xs font-bold transition-all" :class="filtreStatut === '' ? 'bg-primary text-white' : 'bg-cream text-primary-deep hover:bg-mint/40'">Tous</button>
                    <template x-for="s in ['a_confirmer', 'confirmee', 'realisee', 'absent', 'annulee']" :key="s">
                        <button type="button" @click="filtreStatut = s" class="px-3 py-1 rounded-full font-body text-xs font-bold transition-all" :class="filtreStatut === s ? 'bg-primary text-white' : 'bg-cream text-primary-deep hover:bg-mint/40'" x-text="statutLabel(s)"></button>
                    </template>
                </div>
            </div>

            {{-- Journées --}}
            <div class="flex flex-col gap-6">
                <template x-for="j in journeesFiltrees" :key="j.dateIso">
                    <div class="bg-white rounded-3xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-mint/40 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="font-display text-lg text-primary-deep" x-text="j.date"></span>
                                <span class="font-body text-xs text-primary-deep/75 capitalize" x-text="j.dateComplete"></span>
                            </div>
                            <span class="font-body text-xs font-bold text-primary" x-text="j.items.length + ' rendez-vous'"></span>
                        </div>
                        <ul class="divide-y divide-mint">
                            <template x-for="it in j.items" :key="it.dbId">
                                <li>
                                    <button type="button" @click="ouvrir(it)" class="w-full text-left px-6 py-4 flex items-center gap-4 hover:bg-mint/20 transition-colors">
                                        <span class="font-display text-base text-primary w-16 shrink-0" x-text="it.heure"></span>
                                        <div class="min-w-0 flex-1">
                                            <p class="font-body text-sm font-bold text-primary-deep truncate" x-text="it.nom"></p>
                                            <p class="font-body text-xs text-primary-deep/75 truncate" x-text="it.typeLabel + (it.dossier ? ' · ' + it.dossier : '')"></p>
                                        </div>
                                        <span x-show="it.note" title="Compte rendu rédigé" class="text-primary-deep/40 shrink-0">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6M9 16h6M7 4h7l5 5v11a1 1 0 01-1 1H7a1 1 0 01-1-1V5a1 1 0 011-1z"/></svg>
                                        </span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full font-body text-xs font-bold shrink-0" :class="statutClasses(it.statut)" x-text="statutLabel(it.statut)"></span>
                                    </button>
                                </li>
                            </template>
                        </ul>
                    </div>
                </template>

                <div x-show="journeesFiltrees.length === 0" class="bg-white rounded-3xl shadow-sm py-12 text-center font-body text-primary-deep/75">
                    Aucune visite pour cette sélection.
                    <button type="button" @click="nouvelleVisite()" class="block mx-auto mt-3 text-coral font-bold hover:underline">Ajouter une visite</button>
                </div>
            </div>
        </div>
    </main>

    {{-- TIROIR : création / modification --}}
    <div x-show="tiroirOuvert" x-cloak @click="tiroirOuvert = false" class="fixed inset-0 bg-primary-deep/40 backdrop-blur-sm z-50" x-transition.opacity></div>

    <aside x-show="tiroirOuvert" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" class="fixed top-0 right-0 h-full w-full sm:w-[460px] bg-white shadow-2xl z-50 overflow-y-auto flex flex-col" style="display: none;">
        <div class="p-6 pb-4 border-b border-mint bg-cream/60 sticky top-0 backdrop-blur-md z-10 flex items-center justify-between">
            <h2 class="font-display text-xl text-primary-deep" x-text="mode === 'edition' ? 'Modifier la visite' : 'Nouvelle visite'"></h2>
            <button type="button" @click="tiroirOuvert = false" class="w-8 h-8 rounded-full flex items-center justify-center text-primary-deep/75 hover:bg-mint/40 hover:text-primary-deep transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-6 flex-1 flex flex-col gap-6">
            <div x-show="erreurs.length" class="bg-coral/10 text-coral rounded-2xl p-4 font-body text-sm">
                <p class="font-bold mb-1">Merci de corriger :</p>
                <ul class="list-disc pl-5"><template x-for="e in erreurs" :key="e"><li x-text="e"></li></template></ul>
            </div>

            <form id="form-visite" method="POST" :action="actionFormulaire" class="flex flex-col gap-4">
                @csrf
                <input type="hidden" name="_method" :value="mode === 'edition' ? 'PUT' : 'POST'">
                <input type="hidden" name="_visite_id" :value="form._visite_id">

                <div>
                    <label for="dossier" class="{{ $etiquette }}">Dossier lié</label>
                    <select id="dossier" name="dossier" x-model="form.dossier" @change="remplirDepuisDossier()" class="{{ $champ }}">
                        <option value="">Aucun dossier (visite spontanée)</option>
                        @foreach (collect($dossiers)->groupBy('groupe') as $groupe => $liste)
                            <optgroup label="{{ $groupe }}">
                                @foreach ($liste as $d)
                                    <option value="{{ $d['valeur'] }}">{{ $d['libelle'] }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    <p x-show="mode === 'creation'" class="font-body text-xs text-primary-deep/40 mt-1">Choisir un dossier remplit automatiquement les champs ci-dessous.</p>
                </div>

                <div>
                    <label for="type" class="{{ $etiquette }}">Type de rendez-vous</label>
                    <select id="type" name="type" x-model="form.type" class="{{ $champ }}">
                        @foreach ($types as $cle => $libelle)
                            <option value="{{ $cle }}">{{ $libelle }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="visit_date" class="{{ $etiquette }}">Date</label>
                        <input type="date" id="visit_date" name="visit_date" x-model="form.visit_date" required class="{{ $champ }}">
                    </div>
                    <div>
                        <label for="visit_time" class="{{ $etiquette }}">Heure</label>
                        <input type="time" id="visit_time" name="visit_time" x-model="form.visit_time" required class="{{ $champ }}">
                    </div>
                </div>

                <div>
                    <label for="visitor_name" class="{{ $etiquette }}">Nom du visiteur</label>
                    <input type="text" id="visitor_name" name="visitor_name" x-model="form.visitor_name" required placeholder="ex. Famille Dupont" class="{{ $champ }}">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="phone" class="{{ $etiquette }}">Téléphone</label>
                        <input type="tel" id="phone" name="phone" x-model="form.phone" class="{{ $champ }}">
                    </div>
                    <div>
                        <label for="email" class="{{ $etiquette }}">Email</label>
                        <input type="email" id="email" name="email" x-model="form.email" class="{{ $champ }}">
                    </div>
                </div>

                <template x-if="mode === 'edition'">
                    <div>
                        <label for="status" class="{{ $etiquette }}">Statut</label>
                        <select id="status" name="status" x-model="form.status" class="{{ $champ }}">
                            <template x-for="s in ['a_confirmer', 'confirmee', 'realisee', 'absent', 'annulee']" :key="s">
                                <option :value="s" x-text="statutLabel(s)" :selected="form.status === s"></option>
                            </template>
                        </select>
                    </div>
                </template>

                <div>
                    <label for="internal_note" class="{{ $etiquette }}">Compte rendu / notes internes</label>
                    <textarea id="internal_note" name="internal_note" x-model="form.internal_note" rows="4" placeholder="Impressions de la visite, questions posées, suite à donner..." class="w-full px-4 py-3 bg-cream rounded-2xl font-body text-sm text-primary-deep focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
                </div>
            </form>

            {{-- Actions rapides (modification uniquement) --}}
            <div x-show="mode === 'edition'" class="flex flex-col gap-3 border-t border-mint pt-5">
                <p class="font-body text-xs font-bold text-primary-deep/75 uppercase tracking-widest">Changer rapidement le statut</p>
                <div class="grid grid-cols-2 gap-2">
                    <template x-for="s in ['confirmee', 'realisee', 'absent', 'annulee']" :key="s">
                        <form method="POST" :action="urlBase + '/' + form._visite_id + '/statut'">
                            @csrf
                            @method('PATCH')
                            <button type="submit" name="status" :value="s" :disabled="form.status === s" class="w-full h-10 rounded-full font-body text-sm font-bold transition-all disabled:opacity-40 disabled:cursor-not-allowed" :class="statutClasses(s)" x-text="statutLabel(s)"></button>
                        </form>
                    </template>
                </div>
                <form method="POST" :action="urlBase + '/' + form._visite_id" onsubmit="return confirm('Supprimer définitivement cette visite du journal ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full mt-1 font-body text-xs font-bold text-coral hover:underline">Supprimer cette visite</button>
                </form>
            </div>
        </div>

        <div class="p-5 border-t border-mint bg-cream/70 sticky bottom-0">
            <button type="submit" form="form-visite" class="w-full h-11 px-4 rounded-full bg-primary text-white font-body text-sm font-bold shadow-md hover:opacity-90 transition-all" x-text="mode === 'edition' ? 'Enregistrer les modifications' : 'Ajouter au journal'"></button>
        </div>
    </aside>
</div>
@endsection
