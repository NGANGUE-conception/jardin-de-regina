@extends('layouts.admin')

@section('title', 'Tableau de bord — Espace admin')

@section('content')
<x-admin-sidebar actif="tableau-de-bord" />

<div class="pl-[240px] flex flex-col min-h-screen w-full bg-cream"
     x-data="{
        recherche: '',
        filtreStatut: '',
        filtreAccueil: '',
        onglet: 'toutes',
        dossierOuvertId: null,
        dossiers: {{ json_encode($dossiers) }},
        get filtres() {
            return this.dossiers.filter(d => {
                if (this.onglet === 'a_traiter' && d.statut !== 'nouvelle') return false;
                if (this.onglet === 'entreprises' && !d.entreprise) return false;
                if (this.onglet === 'urgences' && d.accueilType !== 'urgence') return false;
                if (this.filtreStatut && d.statut !== this.filtreStatut) return false;
                if (this.filtreAccueil && d.accueilType !== this.filtreAccueil) return false;
                if (this.recherche) {
                    const q = this.recherche.toLowerCase();
                    const cible = (d.id + ' ' + d.enfant.nom + ' ' + d.parent.nom + ' ' + d.parent.tel).toLowerCase();
                    if (!cible.includes(q)) return false;
                }
                return true;
            });
        },
        get dossierOuvert() {
            return this.dossiers.find(d => d.id === this.dossierOuvertId) || null;
        },
        get compteATraiter() {
            return this.dossiers.filter(d => d.statut === 'nouvelle').length;
        },
        get compteEntreprises() {
            return this.dossiers.filter(d => d.entreprise).length;
        },
        get compteUrgences() {
            return this.dossiers.filter(d => d.accueilType === 'urgence').length;
        },
        badgeClasses(statut) {
            return {
                nouvelle: 'bg-primary text-white',
                attente: 'bg-peach text-coral',
                acceptee: 'bg-mint text-primary',
                refusee: 'bg-cream text-primary-deep/60 border border-primary-deep/20',
            }[statut];
        },
        badgeLabel(statut) {
            return { nouvelle: 'Nouvelle', attente: 'En attente', acceptee: 'Acceptée', refusee: 'Refusée' }[statut];
        }
     }">

    <x-admin-topbar recherche="recherche" recherchePlaceholder="Rechercher un enfant, dossier, parent..." />

    <main class="w-full pt-16 p-8 flex-1">
        <div class="relative w-full max-w-[1136px] mx-auto pb-10">
            <div class="absolute -top-12 right-24 w-96 h-96 rounded-full bg-mint/50 blur-3xl pointer-events-none -z-10"></div>
            <div class="absolute top-80 -left-16 w-80 h-80 rounded-full bg-peach/50 blur-3xl pointer-events-none -z-10"></div>

            {{-- Titre + actions --}}
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-8">
                <div class="flex flex-col">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold">Espace direction · Gestion des berceaux</span>
                    </div>
                    <h1 class="font-display text-3xl text-primary-deep">Tableau de bord des pré-inscriptions</h1>
                    <p class="font-body text-primary-deep/70 flex items-center gap-2 mt-1">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-7.58 7-12A7 7 0 105 9c0 4.42 7 12 7 12z"/><circle cx="12" cy="9" r="2.3"/></svg>
                        Alfortville (12 berceaux agréés PMI + 2 places d'urgence) · Ouverture prévue août 2026
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.pre-inscriptions.export') }}" class="h-[48px] px-6 rounded-full bg-white text-primary font-body font-bold text-sm shadow-sm hover:bg-mint/30 transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v13m0 0l-4-4m4 4l4-4M5 21h14"/></svg>
                        Exporter en Excel
                    </a>
                    <button type="button" class="h-[48px] px-6 rounded-full bg-coral text-white font-body font-bold text-sm shadow-lg hover:-translate-y-0.5 hover:opacity-90 transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14"/></svg>
                        Nouvelle pré-inscription manuelle
                    </button>
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                <div class="bg-white rounded-3xl p-5 shadow-sm hover:-translate-y-1 transition-transform flex flex-col justify-between min-h-[150px]">
                    <div class="flex items-start justify-between">
                        <div class="flex flex-col">
                            <span class="font-body text-xs uppercase tracking-wider text-primary-deep/50">Nouvelles demandes</span>
                            <div class="flex items-baseline gap-2 mt-2">
                                <span class="font-display text-3xl text-primary-deep">{{ $stats['nouvelles'] }}</span>
                                <span class="font-body text-sm text-primary-deep/60">non traitées</span>
                            </div>
                        </div>
                        <div class="w-11 h-11 rounded-full bg-mint flex items-center justify-center text-primary shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/></svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-mint flex items-center justify-between">
                        <span class="px-2 py-0.5 rounded-full bg-mint text-primary font-body text-xs font-bold">+{{ $stats['nouvellesSemaine'] }} cette semaine</span>
                        <span class="font-body text-xs text-primary-deep/60 font-semibold">Priorité haute</span>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-5 shadow-sm hover:-translate-y-1 transition-transform flex flex-col justify-between min-h-[150px]">
                    <div class="flex items-start justify-between">
                        <div class="flex flex-col">
                            <span class="font-body text-xs uppercase tracking-wider text-primary-deep/50">En liste d'attente</span>
                            <div class="flex items-baseline gap-2 mt-2">
                                <span class="font-display text-3xl text-primary-deep">{{ $stats['attente'] }}</span>
                                <span class="font-body text-sm text-primary-deep/60">dossiers qualifiés</span>
                            </div>
                        </div>
                        <div class="w-11 h-11 rounded-full bg-peach flex items-center justify-center text-coral shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 7v5l3 3"/></svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-mint flex items-center justify-between">
                        <span class="px-2 py-0.5 rounded-full bg-peach text-coral font-body text-xs font-bold">Commission mensuelle</span>
                        <span class="font-body text-xs text-primary-deep/60">Prochaine réunion à planifier</span>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-5 shadow-sm hover:-translate-y-1 transition-transform flex flex-col justify-between min-h-[150px]">
                    <div class="flex items-start justify-between">
                        <div class="flex flex-col">
                            <span class="font-body text-xs uppercase tracking-wider text-primary-deep/50">Dossiers acceptés</span>
                            <div class="flex items-baseline gap-2 mt-2">
                                <span class="font-display text-3xl text-primary">{{ $stats['acceptees'] }}</span>
                                <span class="font-body text-sm text-primary-deep/60">pour la rentrée 2026</span>
                            </div>
                        </div>
                        <div class="w-11 h-11 rounded-full bg-mint flex items-center justify-center text-primary shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-mint flex items-center justify-between">
                        <span class="font-body text-xs text-primary font-bold">Remplissage : {{ $stats['remplissagePct'] }}%</span>
                        <div class="w-16 bg-mint rounded-full h-2 overflow-hidden"><div class="bg-primary h-full rounded-full" style="width: {{ $stats['remplissagePct'] }}%"></div></div>
                    </div>
                </div>

                <div class="bg-primary-deep rounded-3xl p-5 shadow-sm hover:-translate-y-1 transition-transform flex flex-col justify-between min-h-[150px] text-white">
                    <div class="flex items-start justify-between">
                        <div class="flex flex-col">
                            <span class="font-body text-xs uppercase tracking-wider text-white/60">Places disponibles</span>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="font-display text-3xl whitespace-nowrap">12 + 2</span>
                                <span class="px-2 py-0.5 rounded-full bg-white/15 font-body text-xs font-bold uppercase">urgence</span>
                            </div>
                        </div>
                        <div class="w-11 h-11 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="4" y="10" width="16" height="8" rx="2"/><path d="M4 10V8a2 2 0 012-2h12a2 2 0 012 2v2"/></svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-white/15 text-xs text-white/70">
                        <strong class="text-white">12 berceaux réguliers</strong> dès sept. 2026 · 2 urgences
                    </div>
                </div>
            </div>

            {{-- Filtres + tableau --}}
            <div class="w-full flex flex-col gap-5">
                <div class="bg-white rounded-3xl p-5 shadow-sm flex flex-col gap-4">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                        <div class="md:col-span-6 relative">
                            <div class="relative">
                                <select x-model="filtreStatut" class="w-full h-11 pl-4 pr-12 bg-cream text-primary-deep rounded-full font-body text-sm appearance-none focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="">Tous les statuts</option>
                                    <option value="nouvelle">Nouvelles demandes</option>
                                    <option value="attente">En attente</option>
                                    <option value="acceptee">Acceptées</option>
                                    <option value="refusee">Refusées</option>
                                </select>
                                <div class="absolute right-2 top-1/2 -translate-y-1/2 w-7 h-7 rounded-full bg-mint flex items-center justify-center text-primary pointer-events-none">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="md:col-span-6 relative">
                            <select x-model="filtreAccueil" class="w-full h-11 pl-4 pr-12 bg-cream text-primary-deep rounded-full font-body text-sm appearance-none focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">Type d'accueil</option>
                                <option value="regulier">Temps plein / partiel</option>
                                <option value="occasionnel">Occasionnel</option>
                                <option value="urgence">Urgence</option>
                                <option value="entreprise">Réservation entreprise</option>
                            </select>
                            <div class="absolute right-2 top-1/2 -translate-y-1/2 w-7 h-7 rounded-full bg-mint flex items-center justify-center text-primary pointer-events-none">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-mint">
                        <div class="flex flex-wrap items-center gap-2">
                            <button type="button" @click="onglet = 'toutes'" class="px-4 py-1.5 rounded-full font-body text-sm transition-all" :class="onglet === 'toutes' ? 'bg-primary text-white shadow-sm' : 'bg-cream text-primary-deep hover:bg-mint/40'" x-text="'Toutes (' + dossiers.length + ')'"></button>
                            <button type="button" @click="onglet = 'a_traiter'" class="px-4 py-1.5 rounded-full font-body text-sm transition-all flex items-center gap-1.5" :class="onglet === 'a_traiter' ? 'bg-primary text-white shadow-sm' : 'bg-cream text-primary-deep hover:bg-mint/40'">
                                <span>À traiter</span><span class="px-1.5 rounded-full bg-coral text-white text-xs font-bold" x-text="compteATraiter"></span>
                            </button>
                            <button type="button" @click="onglet = 'entreprises'" class="px-4 py-1.5 rounded-full font-body text-sm transition-all" :class="onglet === 'entreprises' ? 'bg-primary text-white shadow-sm' : 'bg-cream text-primary-deep hover:bg-mint/40'" x-text="'Entreprises (' + compteEntreprises + ')'"></button>
                            <button type="button" @click="onglet = 'urgences'" class="px-4 py-1.5 rounded-full font-body text-sm transition-all flex items-center gap-1.5" :class="onglet === 'urgences' ? 'bg-primary text-white shadow-sm' : 'bg-cream text-primary-deep hover:bg-mint/40'">
                                <span>Urgences</span><span class="px-1.5 rounded-full bg-peach text-coral text-xs font-bold" x-text="compteUrgences"></span>
                            </button>
                        </div>
                        <span class="font-body text-sm text-primary-deep/50" x-text="filtres.length + ' résultat(s) affiché(s)'"></span>
                    </div>
                </div>

                {{-- Tableau --}}
                <div class="bg-white rounded-3xl shadow-sm overflow-hidden w-full">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[880px]">
                            <thead>
                                <tr class="bg-cream text-primary-deep/60 font-body text-xs uppercase tracking-wider">
                                    <th class="py-4 px-6">Dossier / Date</th>
                                    <th class="py-4 px-6">Enfant</th>
                                    <th class="py-4 px-6">Parent & contact</th>
                                    <th class="py-4 px-6">Accueil</th>
                                    <th class="py-4 px-6">Entrée</th>
                                    <th class="py-4 px-6">Statut</th>
                                    <th class="py-4 px-6 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-mint font-body text-sm text-primary-deep">
                                <template x-for="d in filtres" :key="d.id">
                                    <tr class="hover:bg-mint/20 transition-colors cursor-pointer" @click="dossierOuvertId = d.id" :class="dossierOuvertId === d.id ? 'bg-mint/30' : ''">
                                        <td class="py-4 px-6">
                                            <div class="font-bold text-primary" x-text="d.id"></div>
                                            <div class="font-body text-xs text-primary-deep/50 mt-0.5" x-text="d.dateLabel"></div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-mint flex items-center justify-center font-bold text-primary text-xs shrink-0" x-text="d.enfant.initiales"></div>
                                                <div>
                                                    <div class="font-bold text-primary-deep" x-text="d.enfant.nom"></div>
                                                    <div class="text-primary-deep/50 text-xs mt-0.5" x-text="d.enfant.age + ' · ' + d.enfant.sexe"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="font-semibold text-primary-deep" x-text="d.parent.nom"></div>
                                            <div class="text-primary-deep/50 text-xs mt-0.5 flex items-center gap-1">
                                                <svg class="w-3 h-3 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2l2 5-2 1a11 11 0 006 6l1-2 5 2v2a2 2 0 01-2 2h-1C9 19 3 13 3 6V5z"/></svg>
                                                <span x-text="d.parent.tel"></span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="px-3 py-1 rounded-full bg-cream font-semibold text-xs text-primary-deep" x-text="d.accueilLabel"></span>
                                        </td>
                                        <td class="py-4 px-6 font-medium text-xs" x-text="d.entreeLabel"></td>
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full font-body text-xs font-bold" :class="badgeClasses(d.statut)">
                                                <span class="w-1.5 h-1.5 rounded-full" :class="d.statut === 'nouvelle' ? 'bg-white' : 'bg-current'"></span>
                                                <span x-text="badgeLabel(d.statut)"></span>
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <button type="button" @click.stop="dossierOuvertId = d.id" class="px-3 py-1.5 rounded-full bg-mint/50 hover:bg-primary text-primary hover:text-white transition-all text-xs font-body font-bold inline-flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12S6 5 12 5s9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7z"/><circle cx="12" cy="12" r="3"/></svg>
                                                Consulter
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="filtres.length === 0">
                                    <td colspan="7" class="py-10 text-center text-primary-deep/50 font-body">Aucun dossier ne correspond à ces critères.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 bg-cream flex flex-col sm:flex-row items-center justify-between gap-2 font-body text-sm text-primary-deep/60">
                        <span>Affichage de <strong class="text-primary-deep">6</strong> demandes sur <strong class="text-primary-deep">14</strong> au total (démonstration)</span>
                        <span class="text-xs italic">Données fictives à titre d'exemple</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- DRAWER --}}
    <div x-show="dossierOuvertId" x-cloak @click="dossierOuvertId = null" class="fixed inset-0 bg-primary-deep/40 backdrop-blur-sm z-50" x-transition.opacity></div>

    <aside x-show="dossierOuvertId" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" class="fixed top-0 right-0 h-full w-full sm:w-[420px] bg-white shadow-2xl z-50 overflow-y-auto flex flex-col" style="display: none;">
        <template x-if="dossierOuvert">
            <div class="flex flex-col h-full">
                <div class="p-6 pb-4 border-b border-mint bg-cream/60 sticky top-0 backdrop-blur-md z-10">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold">Dossier sélectionné</span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full font-body text-xs font-bold" :class="badgeClasses(dossierOuvert.statut)" x-text="badgeLabel(dossierOuvert.statut)"></span>
                        </div>
                        <button type="button" @click="dossierOuvertId = null" title="Fermer" class="w-8 h-8 rounded-full flex items-center justify-center text-primary-deep/50 hover:bg-mint/40 hover:text-primary-deep transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="flex items-center justify-between">
                        <h2 class="font-display text-xl text-primary" x-text="dossierOuvert.id"></h2>
                        <span class="font-body text-xs text-primary-deep/50" x-text="dossierOuvert.dateLabel"></span>
                    </div>
                </div>

                <div class="p-6 flex-1 flex flex-col gap-6">
                    <div class="bg-cream rounded-2xl p-4 flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-mint text-primary font-display text-xl flex items-center justify-center shrink-0" x-text="dossierOuvert.enfant.initiales"></div>
                        <div class="min-w-0 flex-1">
                            <h3 class="font-display text-lg text-primary-deep truncate" x-text="dossierOuvert.enfant.nom"></h3>
                            <p class="font-body text-sm text-primary-deep/60 mt-0.5" x-text="dossierOuvert.enfant.naissance"></p>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2.5">
                        <div class="flex items-center gap-1.5 text-primary">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg>
                            <span class="font-body text-xs uppercase font-bold tracking-wider">Critères d'accueil</span>
                        </div>
                        <div class="bg-cream rounded-2xl p-4 divide-y divide-primary-deep/10 font-body text-sm">
                            <div class="pb-2.5 flex justify-between"><span class="text-primary-deep/60">Date d'entrée souhaitée</span><span class="font-bold text-primary-deep" x-text="dossierOuvert.entreeLabel"></span></div>
                            <div class="py-2.5 flex justify-between"><span class="text-primary-deep/60">Rythme demandé</span><span class="font-bold text-primary-deep" x-text="dossierOuvert.accueilLabel"></span></div>
                            <div class="py-2.5 flex justify-between"><span class="text-primary-deep/60">Horaires souhaités</span><span class="font-bold text-primary-deep">8h30 – 18h30</span></div>
                            <div class="pt-2.5 flex flex-col gap-1">
                                <span class="text-primary-deep/60">Adresse du foyer</span>
                                <span class="font-medium text-primary-deep flex items-start gap-1.5 text-xs">
                                    <svg class="w-4 h-4 text-primary shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-7.58 7-12A7 7 0 105 9c0 4.42 7 12 7 12z"/><circle cx="12" cy="9" r="2.3"/></svg>
                                    <span x-text="dossierOuvert.adresse"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2.5">
                        <div class="flex items-center gap-1.5 text-primary">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="3"/><path stroke-linecap="round" d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6"/></svg>
                            <span class="font-body text-xs uppercase font-bold tracking-wider">Parent référent</span>
                        </div>
                        <div class="bg-cream rounded-2xl p-4 flex flex-col gap-3">
                            <div class="flex items-start justify-between">
                                <div class="flex flex-col">
                                    <span class="font-bold text-primary-deep text-sm" x-text="dossierOuvert.parent.nom"></span>
                                    <span class="text-xs text-primary-deep/50" x-text="dossierOuvert.parent.role"></span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <a :href="'tel:' + dossierOuvert.parent.tel.replace(/\s/g, '')" class="w-8 h-8 rounded-full bg-mint flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2l2 5-2 1a11 11 0 006 6l1-2 5 2v2a2 2 0 01-2 2h-1C9 19 3 13 3 6V5z"/></svg>
                                    </a>
                                    <a :href="'mailto:' + dossierOuvert.parent.email" class="w-8 h-8 rounded-full bg-cream border border-primary-deep/10 flex items-center justify-center text-primary-deep/60 hover:bg-primary hover:text-white transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
                                    </a>
                                </div>
                            </div>
                            <div class="flex flex-col gap-1 text-xs text-primary-deep/60 border-t border-primary-deep/10 pt-2">
                                <div class="flex items-center gap-2"><svg class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2l2 5-2 1a11 11 0 006 6l1-2 5 2v2a2 2 0 01-2 2h-1C9 19 3 13 3 6V5z"/></svg><span x-text="dossierOuvert.parent.tel"></span></div>
                                <div class="flex items-center gap-2"><svg class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg><span class="truncate" x-text="dossierOuvert.parent.email"></span></div>
                            </div>
                            <template x-if="dossierOuvert.entreprise">
                                <div class="pt-2 border-t border-primary-deep/10 flex items-center justify-between">
                                    <span class="text-xs text-primary-deep/60 font-medium">Financement</span>
                                    <span class="px-2.5 py-0.5 rounded-full bg-mint text-primary text-xs font-bold inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14"/></svg>
                                        Berceau entreprise
                                    </span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2.5">
                        <div class="flex items-center gap-1.5 text-primary">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
                            <span class="font-body text-xs uppercase font-bold tracking-wider">Spécificités</span>
                        </div>
                        <p class="font-body text-sm text-primary-deep/70 bg-cream rounded-2xl p-4" x-text="dossierOuvert.specificites"></p>
                    </div>

                    <div class="flex flex-col gap-2.5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5 text-primary">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                <span class="font-body text-xs uppercase font-bold tracking-wider">Notes internes</span>
                            </div>
                        </div>
                        <div class="bg-cream rounded-2xl p-4">
                            <p class="text-xs text-primary-deep/70 leading-relaxed italic" x-text="dossierOuvert.note"></p>
                            <div class="mt-2 pt-2 border-t border-primary-deep/10 flex items-center justify-between text-xs text-primary-deep/50">
                                <span class="font-semibold">[Direction]</span>
                                <span x-text="dossierOuvert.dateLabel"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-5 border-t border-mint bg-cream/70 sticky bottom-0 flex flex-col gap-2.5">
                    <form method="POST" :action="'/admin/dossiers/' + dossierOuvert.dbId + '/statut'" class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <button type="submit" name="status" value="acceptee" class="flex-1 h-11 px-4 rounded-full bg-primary text-white font-body text-sm font-bold shadow-md hover:opacity-90 transition-all flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Accepter le dossier
                        </button>
                        <button type="submit" name="status" value="attente" class="h-11 px-4 rounded-full bg-white text-coral border border-coral/40 font-body text-sm font-bold hover:bg-peach/40 transition-all flex items-center justify-center gap-1.5 shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 7v5l3 3"/></svg>
                            Liste d'attente
                        </button>
                    </form>
                    <div class="flex items-center justify-between pt-1">
                        <a :href="'mailto:' + dossierOuvert.parent.email" class="font-body text-xs font-semibold text-primary hover:underline flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                            Écrire aux parents
                        </a>
                        <form method="POST" :action="'/admin/dossiers/' + dossierOuvert.dbId + '/statut'">
                            @csrf
                            @method('PATCH')
                            <button type="submit" name="status" value="refusee" class="font-body text-xs font-semibold text-primary-deep/50 hover:text-coral hover:underline flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                Refuser la demande
                            </button>
                        </form>
                    </div>
                    {{-- Suppression définitive : uniquement dans l'espace admin (route protégée par la connexion). --}}
                    <form method="POST" :action="'/admin/pre-inscriptions/' + dossierOuvert.dbId"
                          @submit="if (! confirm('Supprimer définitivement le dossier ' + dossierOuvert.id + ' ? Cette action est irréversible.')) $event.preventDefault()"
                          class="border-t border-primary-deep/10 pt-2.5 mt-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full font-body text-xs font-bold text-coral/80 hover:text-coral hover:underline flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M10 11v6M14 11v6M5 7l1 12a2 2 0 002 2h8a2 2 0 002-2l1-12M9 7V4h6v3"/></svg>
                            Supprimer ce dossier
                        </button>
                    </form>
                </div>
            </div>
        </template>
    </aside>
</div>
@endsection
