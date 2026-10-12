@extends('layouts.admin')

@section('title', 'Demandes entreprises — Espace admin')

@section('content')
<x-admin-sidebar actif="entreprises" />

<div class="pl-[240px] flex flex-col min-h-screen w-full bg-cream"
     x-data="{
        recherche: '',
        filtreStatut: '',
        demandeOuverteId: null,
        demandes: {{ json_encode($demandes) }},
        get filtres() {
            return this.demandes.filter(d => {
                if (this.filtreStatut && d.statut !== this.filtreStatut) return false;
                if (this.recherche) {
                    const q = this.recherche.toLowerCase();
                    const cible = (d.id + ' ' + d.nom + ' ' + d.contact).toLowerCase();
                    if (!cible.includes(q)) return false;
                }
                return true;
            });
        },
        get demandeOuverte() {
            return this.demandes.find(d => d.id === this.demandeOuverteId) || null;
        },
        badgeClasses(statut) {
            return {
                nouvelle: 'bg-primary text-white',
                en_discussion: 'bg-peach text-coral',
                signee: 'bg-mint text-primary',
                refusee: 'bg-cream text-primary-deep/75 border border-primary-deep/20',
            }[statut];
        },
        badgeLabel(statut) {
            return { nouvelle: 'Nouvelle', en_discussion: 'En discussion', signee: 'Convention signée', refusee: 'Refusée' }[statut];
        }
     }">

    <x-admin-topbar recherche="recherche" recherchePlaceholder="Rechercher une entreprise, un contact..." />

    <main class="w-full pt-16 p-8 flex-1">
        <div class="relative w-full max-w-[1136px] mx-auto pb-10">
            <div class="absolute -top-12 right-24 w-96 h-96 rounded-full bg-mint/50 blur-3xl pointer-events-none -z-10"></div>

            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-8">
                <div class="flex flex-col">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14M9 9h1m-1 4h1m4-4h1m-1 4h1M9 21v-4h6v4"/></svg>
                        <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold">Berceaux entreprise</span>
                    </div>
                    <h1 class="font-display text-3xl text-primary-deep">Demandes entreprises</h1>
                    <p class="font-body text-primary-deep/80 mt-1">Suivi des entreprises intéressées par une réservation de berceau pour leurs salariés.</p>
                </div>
                <a href="{{ route('berceaux-entreprise') }}" class="h-[48px] px-6 rounded-full bg-white text-primary font-body font-bold text-sm shadow-sm hover:bg-mint/30 transition-all flex items-center gap-2 shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M7 7h10v10"/></svg>
                    Voir la page publique
                </a>
            </div>

            <div class="w-full flex flex-col gap-5">
                <div class="bg-white rounded-3xl p-5 shadow-sm flex flex-wrap items-center justify-between gap-3">
                    <div class="relative w-full sm:w-64">
                        <select x-model="filtreStatut" class="w-full h-11 pl-4 pr-12 bg-cream text-primary-deep rounded-full font-body text-sm appearance-none focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="">Tous les statuts</option>
                            <option value="nouvelle">Nouvelles</option>
                            <option value="en_discussion">En discussion</option>
                            <option value="signee">Convention signée</option>
                            <option value="refusee">Refusées</option>
                        </select>
                        <div class="absolute right-2 top-1/2 -translate-y-1/2 w-7 h-7 rounded-full bg-mint flex items-center justify-center text-primary pointer-events-none">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                    <span class="font-body text-sm text-primary-deep/75" x-text="filtres.length + ' résultat(s) affiché(s)'"></span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <template x-for="d in filtres" :key="d.id">
                        <div class="bg-white rounded-3xl p-5 shadow-sm hover:-translate-y-1 transition-transform cursor-pointer flex flex-col gap-3" @click="demandeOuverteId = d.id">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-11 h-11 rounded-full bg-mint flex items-center justify-center text-primary shrink-0">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14M9 9h1m-1 4h1m4-4h1m-1 4h1M9 21v-4h6v4"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="font-display text-base text-primary-deep truncate" x-text="d.nom"></h3>
                                        <p class="font-body text-xs text-primary-deep/75" x-text="d.secteur + ' · ' + d.effectif"></p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full font-body text-xs font-bold shrink-0" :class="badgeClasses(d.statut)" x-text="badgeLabel(d.statut)"></span>
                            </div>
                            <p class="font-body text-sm text-primary-deep/80 line-clamp-2" x-text="d.message"></p>
                            <div class="flex items-center justify-between pt-2 border-t border-mint text-xs font-body text-primary-deep/75">
                                <span x-text="d.berceaux + ' berceau(x) souhaité(s)'"></span>
                                <span x-text="d.dateLabel"></span>
                            </div>
                        </div>
                    </template>
                    <div x-show="filtres.length === 0" class="md:col-span-2 py-10 text-center text-primary-deep/75 font-body bg-white rounded-3xl">Aucune demande ne correspond à ces critères.</div>
                </div>
            </div>
        </div>
    </main>

    {{-- DRAWER --}}
    <div x-show="demandeOuverteId" x-cloak @click="demandeOuverteId = null" class="fixed inset-0 bg-primary-deep/40 backdrop-blur-sm z-50" x-transition.opacity></div>

    <aside x-show="demandeOuverteId" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" class="fixed top-0 right-0 h-full w-full sm:w-[420px] bg-white shadow-2xl z-50 overflow-y-auto flex flex-col" style="display: none;">
        <template x-if="demandeOuverte">
            <div class="flex flex-col h-full">
                <div class="p-6 pb-4 border-b border-mint bg-cream/60 sticky top-0 backdrop-blur-md z-10">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold" x-text="demandeOuverte.id"></span>
                        <button type="button" @click="demandeOuverteId = null" class="w-8 h-8 rounded-full flex items-center justify-center text-primary-deep/75 hover:bg-mint/40 hover:text-primary-deep transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <h2 class="font-display text-xl text-primary-deep" x-text="demandeOuverte.nom"></h2>
                    <span class="inline-flex items-center gap-1.5 mt-2 px-3 py-1 rounded-full font-body text-xs font-bold" :class="badgeClasses(demandeOuverte.statut)" x-text="badgeLabel(demandeOuverte.statut)"></span>
                </div>

                <div class="p-6 flex-1 flex flex-col gap-6">
                    <div class="bg-cream rounded-2xl p-4 divide-y divide-primary-deep/10 font-body text-sm">
                        <div class="pb-2.5 flex justify-between"><span class="text-primary-deep/75">Secteur</span><span class="font-bold text-primary-deep" x-text="demandeOuverte.secteur"></span></div>
                        <div class="py-2.5 flex justify-between"><span class="text-primary-deep/75">Effectif</span><span class="font-bold text-primary-deep" x-text="demandeOuverte.effectif"></span></div>
                        <div class="pt-2.5 flex justify-between"><span class="text-primary-deep/75">Berceaux souhaités</span><span class="font-bold text-primary-deep" x-text="demandeOuverte.berceaux"></span></div>
                    </div>

                    <div class="flex flex-col gap-2.5">
                        <div class="flex items-center gap-1.5 text-primary">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="3"/><path stroke-linecap="round" d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6"/></svg>
                            <span class="font-body text-xs uppercase font-bold tracking-wider">Contact</span>
                        </div>
                        <div class="bg-cream rounded-2xl p-4 flex flex-col gap-2">
                            <span class="font-bold text-primary-deep text-sm" x-text="demandeOuverte.contact"></span>
                            <div class="flex flex-col gap-1 text-xs text-primary-deep/75">
                                <div class="flex items-center gap-2"><svg class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2l2 5-2 1a11 11 0 006 6l1-2 5 2v2a2 2 0 01-2 2h-1C9 19 3 13 3 6V5z"/></svg><span x-text="demandeOuverte.tel"></span></div>
                                <div class="flex items-center gap-2"><svg class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg><span class="truncate" x-text="demandeOuverte.email"></span></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2.5">
                        <div class="flex items-center gap-1.5 text-primary">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12a9 9 0 11-9-9 9 9 0 019 9z"/></svg>
                            <span class="font-body text-xs uppercase font-bold tracking-wider">Message</span>
                        </div>
                        <p class="font-body text-sm text-primary-deep/80 bg-cream rounded-2xl p-4" x-text="demandeOuverte.message"></p>
                    </div>

                    <template x-if="demandeOuverte.motif">
                        <div class="bg-peach/40 rounded-2xl p-4 flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-coral shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-8.18 14A2 2 0 004 21h16a2 2 0 001.89-3.14l-8.18-14a2 2 0 00-3.42 0z"/></svg>
                            <p class="font-body text-xs text-primary-deep/80"><strong>Motif du refus :</strong> <span x-text="demandeOuverte.motif"></span></p>
                        </div>
                    </template>
                </div>

                <div class="p-5 border-t border-mint bg-cream sticky bottom-0 flex flex-col gap-2.5">
                    <a :href="'mailto:' + demandeOuverte.email" class="h-11 px-4 rounded-full bg-primary text-white font-body text-sm font-bold shadow-md hover:opacity-90 transition-all flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                        Répondre à l'entreprise
                    </a>
                    <form method="POST" :action="'/admin/demandes-entreprises/' + demandeOuverte.dbId + '/statut'" class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <button type="submit" name="status" value="en_discussion" class="flex-1 h-11 px-3 rounded-full bg-white text-primary border border-primary/30 font-body text-xs font-bold hover:bg-mint/30 transition-all">En discussion</button>
                        <button type="submit" name="status" value="signee" class="flex-1 h-11 px-3 rounded-full bg-mint text-primary font-body text-xs font-bold hover:bg-mint/70 transition-all">Convention signée</button>
                        <button type="submit" name="status" value="refusee" class="h-11 px-3 rounded-full bg-white text-coral border border-coral/30 font-body text-xs font-bold hover:bg-peach/40 transition-all">Refuser</button>
                    </form>
                    {{-- Suppression définitive : uniquement dans l'espace admin (route protégée par la connexion). --}}
                    <form method="POST" :action="'/admin/demandes-entreprises/' + demandeOuverte.dbId"
                          @submit="if (! confirm('Supprimer définitivement la demande ' + demandeOuverte.id + ' ? Cette action est irréversible.')) $event.preventDefault()"
                          class="border-t border-primary-deep/10 pt-2.5 mt-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full font-body text-xs font-bold text-coral/80 hover:text-coral hover:underline flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M10 11v6M14 11v6M5 7l1 12a2 2 0 002 2h8a2 2 0 002-2l1-12M9 7V4h6v3"/></svg>
                            Supprimer cette demande
                        </button>
                    </form>
                </div>
            </div>
        </template>
    </aside>
</div>
@endsection
