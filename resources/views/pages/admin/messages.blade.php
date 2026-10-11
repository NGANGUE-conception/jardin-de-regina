@extends('layouts.admin')

@section('title', 'Messages — Espace admin')

@section('content')
<x-admin-sidebar actif="messages" />

<div class="pl-[240px] flex flex-col min-h-screen w-full bg-cream"
     x-data="{
        recherche: '',
        filtreStatut: '',
        messageOuvertId: null,
        messages: {{ json_encode($messages) }},
        get filtres() {
            return this.messages.filter(m => {
                if (this.filtreStatut && m.statut !== this.filtreStatut) return false;
                if (this.recherche) {
                    const q = this.recherche.toLowerCase();
                    const cible = (m.nom + ' ' + m.objet + ' ' + m.message).toLowerCase();
                    if (!cible.includes(q)) return false;
                }
                return true;
            });
        },
        get messageOuvert() {
            return this.messages.find(m => m.id === this.messageOuvertId) || null;
        },
        ouvrir(m) {
            this.messageOuvertId = m.id;
            if (m.statut === 'non_lu') {
                m.statut = 'lu';
                this.changerStatut(m.dbId, 'lu');
            }
        },
        changerStatut(dbId, statut) {
            fetch('/admin/messages/' + dbId + '/statut', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-HTTP-Method-Override': 'PATCH',
                },
                body: JSON.stringify({ status: statut }),
            });
        },
        badgeClasses(statut) {
            return {
                non_lu: 'bg-coral text-white',
                lu: 'bg-cream text-primary-deep/60 border border-primary-deep/20',
                repondu: 'bg-mint text-primary',
            }[statut];
        },
        badgeLabel(statut) {
            return { non_lu: 'Non lu', lu: 'Lu', repondu: 'Répondu' }[statut];
        }
     }">

    <x-admin-topbar recherche="recherche" recherchePlaceholder="Rechercher un message, un expéditeur..." />

    <main class="w-full pt-16 p-8 flex-1">
        <div class="relative w-full max-w-[1136px] mx-auto pb-10">
            <div class="absolute -top-12 right-24 w-96 h-96 rounded-full bg-peach/40 blur-3xl pointer-events-none -z-10"></div>

            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-8">
                <div class="flex flex-col">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.5a8.38 8.38 0 01-4.8 7.6 8.5 8.5 0 01-9.3-1.8L3 20l1.9-5.7a8.5 8.5 0 018.4-11 8.38 8.38 0 017.7 6.7z"/></svg>
                        <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold">Boîte de réception</span>
                    </div>
                    <h1 class="font-display text-3xl text-primary-deep">Messages</h1>
                    <p class="font-body text-primary-deep/70 mt-1">Messages envoyés via le formulaire de contact du site public.</p>
                </div>
                <a href="{{ route('contact') }}" class="h-[48px] px-6 rounded-full bg-white text-primary font-body font-bold text-sm shadow-sm hover:bg-mint/30 transition-all flex items-center gap-2 shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M7 7h10v10"/></svg>
                    Voir la page publique
                </a>
            </div>

            <div class="w-full flex flex-col gap-5">
                <div class="bg-white rounded-3xl p-5 shadow-sm flex flex-wrap items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" @click="filtreStatut = ''" class="px-4 py-1.5 rounded-full font-body text-sm transition-all" :class="filtreStatut === '' ? 'bg-primary text-white shadow-sm' : 'bg-cream text-primary-deep hover:bg-mint/40'">Tous</button>
                        <button type="button" @click="filtreStatut = 'non_lu'" class="px-4 py-1.5 rounded-full font-body text-sm transition-all" :class="filtreStatut === 'non_lu' ? 'bg-primary text-white shadow-sm' : 'bg-cream text-primary-deep hover:bg-mint/40'">Non lus</button>
                        <button type="button" @click="filtreStatut = 'lu'" class="px-4 py-1.5 rounded-full font-body text-sm transition-all" :class="filtreStatut === 'lu' ? 'bg-primary text-white shadow-sm' : 'bg-cream text-primary-deep hover:bg-mint/40'">Lus</button>
                        <button type="button" @click="filtreStatut = 'repondu'" class="px-4 py-1.5 rounded-full font-body text-sm transition-all" :class="filtreStatut === 'repondu' ? 'bg-primary text-white shadow-sm' : 'bg-cream text-primary-deep hover:bg-mint/40'">Répondus</button>
                    </div>
                    <span class="font-body text-sm text-primary-deep/50" x-text="filtres.length + ' résultat(s) affiché(s)'"></span>
                </div>

                <div class="bg-white rounded-3xl shadow-sm overflow-hidden w-full divide-y divide-mint">
                    <template x-for="m in filtres" :key="m.id">
                        <button type="button" @click="ouvrir(m)" class="w-full text-left px-6 py-4 flex items-center gap-4 hover:bg-mint/20 transition-colors" :class="messageOuvertId === m.id ? 'bg-mint/30' : ''">
                            <span class="w-2 h-2 rounded-full shrink-0" :class="m.statut === 'non_lu' ? 'bg-coral' : 'bg-transparent'"></span>
                            <div class="w-10 h-10 rounded-full bg-mint flex items-center justify-center font-bold text-primary text-xs shrink-0" x-text="m.nom.split(' ').map(p => p[0]).slice(0,2).join('')"></div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-body text-sm text-primary-deep" :class="m.statut === 'non_lu' ? 'font-extrabold' : 'font-semibold'" x-text="m.nom"></span>
                                    <span class="font-body text-xs text-primary-deep/40 shrink-0 ml-auto hidden sm:inline" x-text="m.dateLabel"></span>
                                </div>
                                <p class="font-body text-sm truncate" :class="m.statut === 'non_lu' ? 'font-bold text-primary-deep' : 'text-primary-deep/60'" x-text="m.objet"></p>
                                <p class="font-body text-xs text-primary-deep/40 truncate" x-text="m.message"></p>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full font-body text-xs font-bold shrink-0" :class="badgeClasses(m.statut)" x-text="badgeLabel(m.statut)"></span>
                        </button>
                    </template>
                    <div x-show="filtres.length === 0" class="py-10 text-center text-primary-deep/50 font-body">Aucun message ne correspond à ces critères.</div>
                </div>
            </div>
        </div>
    </main>

    {{-- DRAWER --}}
    <div x-show="messageOuvertId" x-cloak @click="messageOuvertId = null" class="fixed inset-0 bg-primary-deep/40 backdrop-blur-sm z-50" x-transition.opacity></div>

    <aside x-show="messageOuvertId" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" class="fixed top-0 right-0 h-full w-full sm:w-[420px] bg-white shadow-2xl z-50 overflow-y-auto flex flex-col" style="display: none;">
        <template x-if="messageOuvert">
            <div class="flex flex-col h-full">
                <div class="p-6 pb-4 border-b border-mint bg-cream/60 sticky top-0 backdrop-blur-md z-10">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold" x-text="messageOuvert.id"></span>
                        <button type="button" @click="messageOuvertId = null" class="w-8 h-8 rounded-full flex items-center justify-center text-primary-deep/50 hover:bg-mint/40 hover:text-primary-deep transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <h2 class="font-display text-xl text-primary-deep" x-text="messageOuvert.objet"></h2>
                    <span class="font-body text-xs text-primary-deep/50" x-text="messageOuvert.dateLabel"></span>
                </div>

                <div class="p-6 flex-1 flex flex-col gap-6">
                    <div class="bg-cream rounded-2xl p-4 flex flex-col gap-2">
                        <span class="font-bold text-primary-deep text-sm" x-text="messageOuvert.nom"></span>
                        <div class="flex flex-col gap-1 text-xs text-primary-deep/60">
                            <div class="flex items-center gap-2"><svg class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2l2 5-2 1a11 11 0 006 6l1-2 5 2v2a2 2 0 01-2 2h-1C9 19 3 13 3 6V5z"/></svg><span x-text="messageOuvert.tel"></span></div>
                            <div class="flex items-center gap-2"><svg class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg><span class="truncate" x-text="messageOuvert.email"></span></div>
                        </div>
                    </div>

                    <p class="font-body text-sm text-primary-deep/80 leading-relaxed bg-cream rounded-2xl p-4" x-text="messageOuvert.message"></p>
                </div>

                <div class="p-5 border-t border-mint bg-cream/70 sticky bottom-0 flex flex-col gap-2.5">
                    <a :href="'mailto:' + messageOuvert.email" class="h-11 px-4 rounded-full bg-primary text-white font-body text-sm font-bold shadow-md hover:opacity-90 transition-all flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                        Répondre par email
                    </a>
                    <form method="POST" :action="'/admin/messages/' + messageOuvert.dbId + '/statut'">
                        @csrf
                        @method('PATCH')
                        <button type="submit" name="status" value="repondu" class="w-full h-11 px-4 rounded-full bg-white text-primary border border-primary/30 font-body text-sm font-bold hover:bg-mint/30 transition-all flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Marquer comme répondu
                        </button>
                    </form>
                </div>
            </div>
        </template>
    </aside>
</div>
@endsection
