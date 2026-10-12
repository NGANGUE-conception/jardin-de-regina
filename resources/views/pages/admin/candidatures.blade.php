@extends('layouts.admin')

@section('title', 'Candidatures — Espace admin')

@section('content')
<x-admin-sidebar actif="candidatures" />

<div class="pl-[240px] flex flex-col min-h-screen w-full bg-cream"
     x-data="{
        recherche: '',
        filtreStatut: '',
        candidatOuvertId: null,
        candidatures: {{ json_encode($candidatures) }},
        get filtres() {
            return this.candidatures.filter(c => {
                if (this.filtreStatut && c.statut !== this.filtreStatut) return false;
                if (this.recherche) {
                    const q = this.recherche.toLowerCase();
                    const cible = (c.id + ' ' + c.nom + ' ' + c.poste).toLowerCase();
                    if (!cible.includes(q)) return false;
                }
                return true;
            });
        },
        get candidatOuvert() {
            return this.candidatures.find(c => c.id === this.candidatOuvertId) || null;
        },
        badgeClasses(statut) {
            return {
                nouvelle: 'bg-primary text-white',
                entretien_prevu: 'bg-peach text-coral',
                retenue: 'bg-mint text-primary',
                refusee: 'bg-cream text-primary-deep/75 border border-primary-deep/20',
            }[statut];
        },
        badgeLabel(statut) {
            return { nouvelle: 'Nouvelle', entretien_prevu: 'Entretien prévu', retenue: 'Retenue', refusee: 'Refusée' }[statut];
        }
     }">

    <x-admin-topbar recherche="recherche" recherchePlaceholder="Rechercher un candidat, un poste..." />

    <main class="w-full pt-16 p-8 flex-1">
        <div class="relative w-full max-w-[1136px] mx-auto pb-10">
            <div class="absolute -top-12 right-24 w-96 h-96 rounded-full bg-sky/40 blur-3xl pointer-events-none -z-10"></div>

            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-8">
                <div class="flex flex-col">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="4" y="7" width="16" height="14" rx="2"/><path d="M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2"/><circle cx="12" cy="13" r="2"/></svg>
                        <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold">Recrutement</span>
                    </div>
                    <h1 class="font-display text-3xl text-primary-deep">Candidatures</h1>
                    <p class="font-body text-primary-deep/80 mt-1">Candidatures reçues via la page « Rejoindre l'équipe ».</p>
                </div>
                <a href="{{ route('recrutement') }}" class="h-[48px] px-6 rounded-full bg-white text-primary font-body font-bold text-sm shadow-sm hover:bg-mint/30 transition-all flex items-center gap-2 shrink-0">
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
                            <option value="entretien_prevu">Entretien prévu</option>
                            <option value="retenue">Retenues</option>
                            <option value="refusee">Refusées</option>
                        </select>
                        <div class="absolute right-2 top-1/2 -translate-y-1/2 w-7 h-7 rounded-full bg-mint flex items-center justify-center text-primary pointer-events-none">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                    <span class="font-body text-sm text-primary-deep/75" x-text="filtres.length + ' résultat(s) affiché(s)'"></span>
                </div>

                <div class="bg-white rounded-3xl shadow-sm overflow-hidden w-full">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[820px]">
                            <thead>
                                <tr class="bg-cream text-primary-deep/75 font-body text-xs uppercase tracking-wider">
                                    <th class="py-4 px-6">Candidat / Date</th>
                                    <th class="py-4 px-6">Poste souhaité</th>
                                    <th class="py-4 px-6">Expérience</th>
                                    <th class="py-4 px-6">CV</th>
                                    <th class="py-4 px-6">Statut</th>
                                    <th class="py-4 px-6 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-mint font-body text-sm text-primary-deep">
                                <template x-for="c in filtres" :key="c.id">
                                    <tr class="hover:bg-mint/20 transition-colors cursor-pointer" @click="candidatOuvertId = c.id" :class="candidatOuvertId === c.id ? 'bg-mint/30' : ''">
                                        <td class="py-4 px-6">
                                            <div class="font-bold text-primary-deep" x-text="c.nom"></div>
                                            <div class="font-body text-xs text-primary-deep/75 mt-0.5" x-text="c.dateLabel"></div>
                                        </td>
                                        <td class="py-4 px-6 font-semibold" x-text="c.poste"></td>
                                        <td class="py-4 px-6 text-xs text-primary-deep/80" x-text="c.experience"></td>
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center gap-1 text-primary text-xs font-bold" x-show="c.cvJoint">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M9 8h1M5 21h14a2 2 0 002-2V7l-5-5H5a2 2 0 00-2 2v15a2 2 0 002 2z"/></svg>
                                                Joint
                                            </span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full font-body text-xs font-bold" :class="badgeClasses(c.statut)" x-text="badgeLabel(c.statut)"></span>
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <button type="button" @click.stop="candidatOuvertId = c.id" class="px-3 py-1.5 rounded-full bg-mint/50 hover:bg-primary text-primary hover:text-white transition-all text-xs font-body font-bold inline-flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12S6 5 12 5s9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7z"/><circle cx="12" cy="12" r="3"/></svg>
                                                Consulter
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="filtres.length === 0">
                                    <td colspan="6" class="py-10 text-center text-primary-deep/75 font-body">Aucune candidature ne correspond à ces critères.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- DRAWER --}}
    <div x-show="candidatOuvertId" x-cloak @click="candidatOuvertId = null" class="fixed inset-0 bg-primary-deep/40 backdrop-blur-sm z-50" x-transition.opacity></div>

    <aside x-show="candidatOuvertId" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" class="fixed top-0 right-0 h-full w-full sm:w-[420px] bg-white shadow-2xl z-50 overflow-y-auto flex flex-col" style="display: none;">
        <template x-if="candidatOuvert">
            <div class="flex flex-col h-full">
                <div class="p-6 pb-4 border-b border-mint bg-cream/60 sticky top-0 backdrop-blur-md z-10">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold" x-text="candidatOuvert.id"></span>
                        <button type="button" @click="candidatOuvertId = null" class="w-8 h-8 rounded-full flex items-center justify-center text-primary-deep/75 hover:bg-mint/40 hover:text-primary-deep transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <h2 class="font-display text-xl text-primary-deep" x-text="candidatOuvert.nom"></h2>
                    <span class="inline-flex items-center gap-1.5 mt-2 px-3 py-1 rounded-full font-body text-xs font-bold" :class="badgeClasses(candidatOuvert.statut)" x-text="badgeLabel(candidatOuvert.statut)"></span>
                </div>

                <div class="p-6 flex-1 flex flex-col gap-6">
                    <div class="bg-cream rounded-2xl p-4 divide-y divide-primary-deep/10 font-body text-sm">
                        <div class="pb-2.5 flex justify-between"><span class="text-primary-deep/75">Poste souhaité</span><span class="font-bold text-primary-deep" x-text="candidatOuvert.poste"></span></div>
                        <div class="py-2.5 flex justify-between gap-4"><span class="text-primary-deep/75 shrink-0">Diplôme</span><span class="font-bold text-primary-deep text-right" x-text="candidatOuvert.diplome"></span></div>
                        <div class="pt-2.5 flex justify-between gap-4"><span class="text-primary-deep/75 shrink-0">Expérience</span><span class="font-bold text-primary-deep text-right" x-text="candidatOuvert.experience"></span></div>
                    </div>

                    <div class="flex flex-col gap-2.5">
                        <div class="flex items-center gap-1.5 text-primary">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="3"/><path stroke-linecap="round" d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6"/></svg>
                            <span class="font-body text-xs uppercase font-bold tracking-wider">Contact</span>
                        </div>
                        <div class="bg-cream rounded-2xl p-4 flex flex-col gap-1 text-xs text-primary-deep/75">
                            <div class="flex items-center gap-2"><svg class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2l2 5-2 1a11 11 0 006 6l1-2 5 2v2a2 2 0 01-2 2h-1C9 19 3 13 3 6V5z"/></svg><span x-text="candidatOuvert.tel"></span></div>
                            <div class="flex items-center gap-2"><svg class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg><span class="truncate" x-text="candidatOuvert.email"></span></div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2.5">
                        <div class="flex items-center gap-1.5 text-primary">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12a9 9 0 11-9-9 9 9 0 019 9z"/></svg>
                            <span class="font-body text-xs uppercase font-bold tracking-wider">Message</span>
                        </div>
                        <p class="font-body text-sm text-primary-deep/80 bg-cream rounded-2xl p-4" x-text="candidatOuvert.message"></p>
                    </div>

                    <template x-if="candidatOuvert.cvJoint">
                        <a :href="'/admin/candidatures/' + candidatOuvert.dbId + '/documents/0'" class="flex items-center justify-between gap-3 bg-mint/40 rounded-2xl p-4 hover:bg-mint/60 transition-colors text-left">
                            <span class="flex items-center gap-2.5 font-body text-sm font-bold text-primary-deep">
                                <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M9 8h1M5 21h14a2 2 0 002-2V7l-5-5H5a2 2 0 00-2 2v15a2 2 0 002 2z"/></svg>
                                Télécharger le document joint
                            </span>
                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v13m0 0l-4-4m4 4l4-4M5 21h14"/></svg>
                        </a>
                    </template>
                </div>

                <div class="p-5 border-t border-mint bg-cream/70 sticky bottom-0 flex flex-col gap-2.5">
                    <form method="POST" :action="'/admin/candidatures/' + candidatOuvert.dbId + '/statut'" class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <button type="submit" name="status" value="entretien_prevu" class="flex-1 h-11 px-4 rounded-full bg-primary text-white font-body text-sm font-bold shadow-md hover:opacity-90 transition-all flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg>
                            Planifier un entretien
                        </button>
                        <button type="submit" name="status" value="retenue" class="h-11 px-4 rounded-full bg-mint text-primary font-body text-sm font-bold hover:bg-mint/70 transition-all shrink-0">
                            Retenir
                        </button>
                        <button type="submit" name="status" value="refusee" class="h-11 px-4 rounded-full bg-white text-coral border border-coral/40 font-body text-sm font-bold hover:bg-peach/40 transition-all shrink-0">
                            Refuser
                        </button>
                    </form>
                    {{-- Suppression définitive : uniquement dans l'espace admin (route protégée par la connexion). --}}
                    <form method="POST" :action="'/admin/candidatures/' + candidatOuvert.dbId"
                          @submit="if (! confirm('Supprimer définitivement la candidature (et ses documents) ' + candidatOuvert.id + ' ? Cette action est irréversible.')) $event.preventDefault()"
                          class="border-t border-primary-deep/10 pt-2.5 mt-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full font-body text-xs font-bold text-coral/80 hover:text-coral hover:underline flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M10 11v6M14 11v6M5 7l1 12a2 2 0 002 2h8a2 2 0 002-2l1-12M9 7V4h6v3"/></svg>
                            Supprimer cette candidature et son CV
                        </button>
                    </form>
                </div>
            </div>
        </template>
    </aside>
</div>
@endsection
