@extends('layouts.admin')

@section('title', 'Paramètres — Espace admin')

@section('content')
<x-admin-sidebar actif="parametres" />

<div class="pl-[240px] flex flex-col min-h-screen w-full bg-cream"
     x-data="{
        notifNouvellesDemandes: true,
        notifMessages: true,
        notifRecrutement: false,
        onglet: @js($errors->motDePasse->any() || $errors->emailConnexion->any() || $errors->identite->any() || request('onglet') === 'compte' ? 'compte' : 'etablissement')
     }">

    <x-admin-topbar recherchePlaceholder="Rechercher..." />

    <main class="w-full pt-16 p-8 flex-1">
        <div class="relative w-full max-w-[1136px] mx-auto pb-10">
            <div class="absolute -top-12 right-24 w-96 h-96 rounded-full bg-sky/40 blur-3xl pointer-events-none -z-10"></div>

            <div class="flex flex-col mb-8">
                <div class="flex items-center gap-2 mb-1">
                    <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 11-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 11-2.83-2.83l.06-.06A1.65 1.65 0 004.6 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 112.83-2.83l.06.06A1.65 1.65 0 009 4.6a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 112.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
                    <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold">Espace direction</span>
                </div>
                <h1 class="font-display text-3xl text-primary-deep">Paramètres</h1>
                <p class="font-body text-primary-deep/70 mt-1">Informations de l'établissement, compte administrateur et notifications.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2 mb-6">
                <button type="button" @click="onglet = 'etablissement'" class="px-4 py-1.5 rounded-full font-body text-sm transition-all" :class="onglet === 'etablissement' ? 'bg-primary text-white shadow-sm' : 'bg-white text-primary-deep hover:bg-mint/40'">Établissement</button>
                <button type="button" @click="onglet = 'compte'" class="px-4 py-1.5 rounded-full font-body text-sm transition-all" :class="onglet === 'compte' ? 'bg-primary text-white shadow-sm' : 'bg-white text-primary-deep hover:bg-mint/40'">Compte administrateur</button>
                <button type="button" @click="onglet = 'notifications'" class="px-4 py-1.5 rounded-full font-body text-sm transition-all" :class="onglet === 'notifications' ? 'bg-primary text-white shadow-sm' : 'bg-white text-primary-deep hover:bg-mint/40'">Notifications</button>
            </div>

            {{-- ÉTABLISSEMENT --}}
            <div x-show="onglet === 'etablissement'" class="bg-white rounded-3xl p-6 md:p-8 shadow-sm flex flex-col gap-6 max-w-2xl">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Le Jardin de Régina" class="h-12 w-auto object-contain">
                    <div>
                        <h2 class="font-display text-xl text-primary-deep">Le Jardin de Régina</h2>
                        <p class="font-body text-xs text-primary-deep/50">Micro-crèche · Alfortville (94140)</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="flex flex-col gap-1.5">
                        <span class="font-body text-xs font-bold uppercase text-primary-deep/50">Adresse</span>
                        <input type="text" value="4 Bis rue des Écoles, 94140 Alfortville" disabled class="h-11 px-4 bg-cream text-primary-deep/70 rounded-2xl font-body text-sm cursor-not-allowed">
                    </label>
                    <label class="flex flex-col gap-1.5">
                        <span class="font-body text-xs font-bold uppercase text-primary-deep/50">Téléphone</span>
                        <input type="text" value="07 67 64 61 84" disabled class="h-11 px-4 bg-cream text-primary-deep/70 rounded-2xl font-body text-sm cursor-not-allowed">
                    </label>
                    <label class="flex flex-col gap-1.5">
                        <span class="font-body text-xs font-bold uppercase text-primary-deep/50">Email</span>
                        <input type="text" value="jardinderegina94@gmail.com" disabled class="h-11 px-4 bg-cream text-primary-deep/70 rounded-2xl font-body text-sm cursor-not-allowed">
                    </label>
                    <label class="flex flex-col gap-1.5">
                        <span class="font-body text-xs font-bold uppercase text-primary-deep/50">Horaires</span>
                        <input type="text" value="8h30 – 18h30, du lundi au vendredi" disabled class="h-11 px-4 bg-cream text-primary-deep/70 rounded-2xl font-body text-sm cursor-not-allowed">
                    </label>
                    <label class="flex flex-col gap-1.5">
                        <span class="font-body text-xs font-bold uppercase text-primary-deep/50">Capacité d'accueil</span>
                        <input type="text" value="12 places régulières + 2 places d'urgence" disabled class="h-11 px-4 bg-cream text-primary-deep/70 rounded-2xl font-body text-sm cursor-not-allowed">
                    </label>
                    <label class="flex flex-col gap-1.5">
                        <span class="font-body text-xs font-bold uppercase text-primary-deep/50">Gestionnaire</span>
                        <input type="text" value="Fabrice TCHOUMO — TEF Group Invest" disabled class="h-11 px-4 bg-cream text-primary-deep/70 rounded-2xl font-body text-sm cursor-not-allowed">
                    </label>
                </div>
                <div class="bg-mint/40 rounded-2xl p-4 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-primary shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="font-body text-xs text-primary-deep/70">Ces informations reprennent celles affichées sur le site public. Leur modification directe depuis cet écran sera possible une fois la base de données connectée (Phase 8 du plan technique).</p>
                </div>
            </div>

            {{-- COMPTE ADMINISTRATEUR --}}
            <div x-show="onglet === 'compte'" x-cloak class="bg-white rounded-3xl p-6 md:p-8 shadow-sm flex flex-col gap-6 max-w-2xl">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-primary flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="3"/><path stroke-linecap="round" d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6"/></svg>
                    </div>
                    <div>
                        <h2 class="font-display text-xl text-primary-deep">{{ auth()->user()?->name }}</h2>
                        <p class="font-body text-xs text-primary-deep/50">Compte administrateur</p>
                    </div>
                </div>
                <label class="flex flex-col gap-1.5 sm:max-w-[calc(50%-0.5rem)]">
                    <span class="font-body text-xs font-bold uppercase text-primary-deep/50">Nom affiché</span>
                    <input type="text" value="{{ auth()->user()?->name }}" disabled class="h-11 px-4 bg-cream text-primary-deep/70 rounded-2xl font-body text-sm cursor-not-allowed">
                </label>

                @php
                    $erreursMdp = $errors->motDePasse;
                    $erreursEmail = $errors->emailConnexion;
                    $erreursIdentite = $errors->identite;
                    $champMdp = 'w-full h-11 px-4 bg-cream rounded-2xl font-body text-sm text-primary-deep focus:outline-none focus:ring-2 focus:ring-primary disabled:cursor-not-allowed';
                    $verrou = '<svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V7a4 4 0 018 0v4"/></svg>';
                @endphp

                {{-- Étape 1 : vérification de l'identité (obligatoire avant toute modification) --}}
                <div class="border-t border-mint pt-6">
                    @if (! $identiteVerifiee)
                        <form method="POST" action="{{ route('admin.parametres.verifier-identite') }}" autocomplete="off" class="bg-mint/40 rounded-2xl p-5 flex flex-col gap-3">
                            @csrf
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 3v5c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V6l7-3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
                                <h3 class="font-display text-lg text-primary-deep">Vérifier mon identité</h3>
                            </div>
                            <p class="font-body text-sm text-primary-deep/70">Pour modifier votre adresse de connexion ou votre mot de passe, tapez d'abord votre mot de passe <strong>actuel</strong>. Les modifications seront ensuite possibles pendant 10 minutes. Ce champ ne modifie pas votre mot de passe.</p>

                            @if ($erreursIdentite->any())
                                <p class="bg-coral/10 text-coral rounded-xl px-4 py-2.5 font-body text-sm" role="alert">{{ $erreursIdentite->first() }}</p>
                            @endif

                            <div class="flex flex-col sm:flex-row gap-3">
                                {{-- "new-password" + attributs data-* : empêchent le navigateur et les gestionnaires de mots de passe de remplir ce champ à votre place. --}}
                                <input type="password" name="verification_identite" required
                                       autocomplete="new-password" autocapitalize="off" spellcheck="false"
                                       data-lpignore="true" data-1p-ignore data-bwignore data-form-type="other"
                                       placeholder="Tapez votre mot de passe actuel"
                                       class="flex-1 h-11 px-4 bg-white rounded-2xl font-body text-sm text-primary-deep focus:outline-none focus:ring-2 focus:ring-primary {{ $erreursIdentite->any() ? 'ring-2 ring-coral' : '' }}">
                                <button type="submit" class="h-11 px-6 rounded-full bg-primary text-white font-body text-sm font-bold shadow-md hover:opacity-90 transition-all flex items-center justify-center gap-2 shrink-0">
                                    {!! $verrou !!}
                                    Vérifier
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="bg-mint/40 rounded-2xl p-4 flex flex-wrap items-center justify-between gap-3"
                             x-data="{ reste: {{ $verificationExpireDans }} }"
                             x-init="const minuteur = setInterval(() => { reste--; if (reste <= 0) { clearInterval(minuteur); window.location.href = '{{ route('admin.parametres', ['onglet' => 'compte']) }}'; } }, 1000)">
                            <div class="flex items-center gap-2 font-body text-sm text-primary-deep">
                                <svg class="w-5 h-5 text-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 3v5c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V6l7-3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
                                <span><strong>Identité vérifiée.</strong> Modifications possibles encore <strong x-text="Math.floor(reste / 60) + ' min ' + String(reste % 60).padStart(2, '0') + ' s'"></strong>.</span>
                            </div>
                            <form method="POST" action="{{ route('admin.parametres.verrouiller') }}">
                                @csrf
                                <button type="submit" class="px-4 py-1.5 rounded-full bg-white text-primary-deep font-body text-xs font-bold hover:bg-cream transition-all flex items-center gap-1.5">
                                    {!! $verrou !!}
                                    Verrouiller maintenant
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                {{-- Étape 2 : modifications, bloquées tant que l'identité n'est pas vérifiée --}}
                @unless ($identiteVerifiee)
                    <p class="font-body text-xs text-primary-deep/50 flex items-center gap-1.5 -mb-2">{!! $verrou !!} Les formulaires ci-dessous sont verrouillés jusqu'à la vérification de votre identité.</p>
                @endunless

                {{-- Changement de l'adresse email de connexion --}}
                <form method="POST" action="{{ route('admin.parametres.email') }}" autocomplete="off" class="border-t border-mint pt-6">
                    @csrf
                    @method('PUT')
                    <fieldset @disabled(! $identiteVerifiee) class="flex flex-col gap-4 {{ $identiteVerifiee ? '' : 'opacity-50' }}">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
                            <h3 class="font-display text-lg text-primary-deep">Adresse email de connexion</h3>
                        </div>

                        @if ($erreursEmail->any())
                            <div class="bg-coral/10 text-coral rounded-2xl p-4 font-body text-sm" role="alert">
                                <ul class="list-disc pl-5 flex flex-col gap-0.5">
                                    @foreach ($erreursEmail->all() as $erreur)
                                        <li>{{ $erreur }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <p class="font-body text-sm text-primary-deep/70">Adresse actuelle : <strong class="text-primary-deep">{{ auth()->user()?->email }}</strong></p>

                        <label class="flex flex-col gap-1.5 sm:max-w-[calc(50%-0.5rem)]">
                            <span class="font-body text-xs font-bold uppercase text-primary-deep/50">Nouvelle adresse email</span>
                            <input type="email" name="email" value="{{ $erreursEmail->any() ? old('email') : '' }}" required maxlength="190" autocomplete="off" data-lpignore="true" data-1p-ignore data-bwignore placeholder="exemple@gmail.com" class="{{ $champMdp }} {{ $erreursEmail->has('email') ? 'ring-2 ring-coral' : '' }}">
                        </label>

                        <button type="submit" class="h-11 px-6 rounded-full bg-primary text-white font-body text-sm font-bold shadow-md hover:opacity-90 transition-all w-fit flex items-center gap-2 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Enregistrer la nouvelle adresse
                        </button>

                        <p class="font-body text-xs text-primary-deep/50">C'est l'adresse à saisir sur la page de connexion de l'espace admin. Vérifiez-la bien : une faute de frappe vous empêcherait de vous reconnecter. Elle ne modifie ni l'adresse de contact affichée sur le site, ni l'adresse qui reçoit les alertes de nouveaux dossiers.</p>
                    </fieldset>
                </form>

                {{-- Changement de mot de passe --}}
                <form method="POST" action="{{ route('admin.parametres.mot-de-passe') }}" autocomplete="off"
                      class="border-t border-mint pt-6"
                      x-data="{ voir: false, mdp: '', confirmation: '' }">
                    @csrf
                    @method('PUT')
                    <fieldset @disabled(! $identiteVerifiee) class="flex flex-col gap-4 {{ $identiteVerifiee ? '' : 'opacity-50' }}">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V7a4 4 0 018 0v4"/></svg>
                            <h3 class="font-display text-lg text-primary-deep">Changer le mot de passe</h3>
                        </div>

                        @if ($erreursMdp->any())
                            <div class="bg-coral/10 text-coral rounded-2xl p-4 font-body text-sm" role="alert">
                                <ul class="list-disc pl-5 flex flex-col gap-0.5">
                                    @foreach ($erreursMdp->all() as $erreur)
                                        <li>{{ $erreur }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="flex flex-col gap-1.5">
                                <span class="font-body text-xs font-bold uppercase text-primary-deep/50">Nouveau mot de passe</span>
                                <input :type="voir ? 'text' : 'password'" name="password" x-model="mdp" required minlength="12" autocomplete="new-password" class="{{ $champMdp }} {{ $erreursMdp->has('password') ? 'ring-2 ring-coral' : '' }}">
                            </label>
                            <label class="flex flex-col gap-1.5">
                                <span class="font-body text-xs font-bold uppercase text-primary-deep/50">Confirmer le nouveau</span>
                                <input :type="voir ? 'text' : 'password'" name="password_confirmation" x-model="confirmation" required autocomplete="new-password" class="{{ $champMdp }}">
                            </label>
                        </div>

                        <label class="flex items-center gap-2 font-body text-sm text-primary-deep/70 cursor-pointer select-none w-fit">
                            <input type="checkbox" x-model="voir" class="w-4 h-4 accent-primary">
                            Afficher les mots de passe
                        </label>

                        {{-- Règles, cochées en direct pendant la saisie --}}
                        <ul class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-1.5 font-body text-xs">
                            @foreach ([
                                ['test' => 'mdp.length >= 12', 'texte' => 'Au moins 12 caractères'],
                                ['test' => '/[A-Z]/.test(mdp) && /[a-z]/.test(mdp)', 'texte' => 'Une majuscule et une minuscule'],
                                ['test' => '/[0-9]/.test(mdp)', 'texte' => 'Au moins un chiffre'],
                                ['test' => '/[^A-Za-z0-9]/.test(mdp)', 'texte' => 'Au moins un symbole (! ? @ # % …)'],
                                ['test' => 'mdp.length > 0 && mdp === confirmation', 'texte' => 'Confirmation identique'],
                            ] as $regle)
                                <li class="flex items-center gap-1.5 transition-colors" :class="{{ $regle['test'] }} ? 'text-primary font-bold' : 'text-primary-deep/50'">
                                    <svg x-show="{{ $regle['test'] }}" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <svg x-show="!({{ $regle['test'] }})" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="8"/></svg>
                                    {{ $regle['texte'] }}
                                </li>
                            @endforeach
                        </ul>

                        <button type="submit" class="h-11 px-6 rounded-full bg-primary text-white font-body text-sm font-bold shadow-md hover:opacity-90 transition-all w-fit flex items-center gap-2 disabled:cursor-not-allowed">
                            {!! $verrou !!}
                            Enregistrer le nouveau mot de passe
                        </button>

                        <p class="font-body text-xs text-primary-deep/50">Après le changement, vous restez connecté sur cet appareil ; les autres appareils (téléphone, autre ordinateur) seront déconnectés.</p>
                    </fieldset>
                </form>
            </div>

            {{-- NOTIFICATIONS --}}
            <div x-show="onglet === 'notifications'" x-cloak class="bg-white rounded-3xl p-6 md:p-8 shadow-sm flex flex-col gap-2 max-w-2xl divide-y divide-mint">
                <div class="flex items-center justify-between py-4">
                    <div class="flex flex-col pr-4">
                        <span class="font-body text-sm font-bold text-primary-deep">Nouvelles pré-inscriptions</span>
                        <span class="font-body text-xs text-primary-deep/50">Recevoir un email à chaque nouveau dossier déposé</span>
                    </div>
                    <button type="button" @click="notifNouvellesDemandes = !notifNouvellesDemandes" class="w-12 h-7 rounded-full transition-colors shrink-0 relative" :class="notifNouvellesDemandes ? 'bg-primary' : 'bg-cream border border-primary-deep/20'">
                        <span class="absolute top-1 w-5 h-5 bg-white rounded-full shadow transition-all" :class="notifNouvellesDemandes ? 'left-6' : 'left-1'"></span>
                    </button>
                </div>
                <div class="flex items-center justify-between py-4">
                    <div class="flex flex-col pr-4">
                        <span class="font-body text-sm font-bold text-primary-deep">Nouveaux messages</span>
                        <span class="font-body text-xs text-primary-deep/50">Recevoir un email pour chaque message du formulaire de contact</span>
                    </div>
                    <button type="button" @click="notifMessages = !notifMessages" class="w-12 h-7 rounded-full transition-colors shrink-0 relative" :class="notifMessages ? 'bg-primary' : 'bg-cream border border-primary-deep/20'">
                        <span class="absolute top-1 w-5 h-5 bg-white rounded-full shadow transition-all" :class="notifMessages ? 'left-6' : 'left-1'"></span>
                    </button>
                </div>
                <div class="flex items-center justify-between py-4">
                    <div class="flex flex-col pr-4">
                        <span class="font-body text-sm font-bold text-primary-deep">Nouvelles candidatures</span>
                        <span class="font-body text-xs text-primary-deep/50">Recevoir un email à chaque nouvelle candidature de recrutement</span>
                    </div>
                    <button type="button" @click="notifRecrutement = !notifRecrutement" class="w-12 h-7 rounded-full transition-colors shrink-0 relative" :class="notifRecrutement ? 'bg-primary' : 'bg-cream border border-primary-deep/20'">
                        <span class="absolute top-1 w-5 h-5 bg-white rounded-full shadow transition-all" :class="notifRecrutement ? 'left-6' : 'left-1'"></span>
                    </button>
                </div>
                <p class="pt-4 font-body text-xs text-primary-deep/40 italic">Ces réglages sont visuels pour l'instant ; l'envoi réel des emails sera activé à la Phase 7 du plan technique.</p>
            </div>
        </div>
    </main>
</div>
@endsection
