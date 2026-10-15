@extends('layouts.app')

@section('title', __('Pré-inscription en ligne — Micro-crèche Alfortville'))
@section('description', __('Pré-inscrivez votre enfant en quelques minutes au Jardin de Régina, micro-crèche à Alfortville : accueil régulier, occasionnel ou d\'urgence dès 10 semaines.'))
  


@section('content')
@php
    // Après un envoi refusé : on rouvre l'étape qui contient la première erreur, et le bloc « second parent » s'il est concerné.
    $champsEtape1 = ['child_last_name', 'child_first_name', 'child_gender', 'child_birthdate', 'entry_date', 'care_type', 'care_days', 'special_needs', 'special_needs_details'];
    $champsEnErreur = collect($errors->keys())->map(fn ($cle) => \Illuminate\Support\Str::before($cle, '.'));
    $renvoi = session()->hasOldInput();

    $configPreInscription = [
        'etape' => $errors->any() && $champsEnErreur->intersect($champsEtape1)->isEmpty() ? 2 : 1,
        'careType' => old('care_type', 'regulier'),
        'jours' => $renvoi ? array_values((array) old('care_days', [])) : ['lundi', 'mardi', 'mercredi', 'jeudi'],
        'besoins' => (bool) old('special_needs'),
        'p2Ouvert' => collect(['p2_nom', 'p2_prenom', 'p2_email', 'p2_tel', 'p2_profession', 'p2_employeur'])->contains(fn ($champ) => filled(old($champ)))
            || $champsEnErreur->contains(fn ($champ) => str_starts_with($champ, 'p2_')),
    ];
@endphp

<script>
    function preInscription(config) {
        return {
            step: config.etape,
            careType: config.careType,
            days: config.jours,
            specialNeeds: config.besoins,
            p2Open: config.p2Ouvert,
            joursManquants: false,

            toggleDay(d) {
                this.days.includes(d) ? this.days = this.days.filter(x => x !== d) : this.days.push(d);
                if (this.days.length) this.joursManquants = false;
            },

            // Premier champ de l'étape qui ne respecte pas les règles du navigateur (obligatoire, format e-mail...).
            premierChampInvalide(etape) {
                const champs = this.$root.querySelectorAll('[data-etape="' + etape + '"] :is(input, select, textarea)');
                return [...champs].find(champ => champ.type !== 'hidden' && !champ.disabled && !champ.checkValidity()) || null;
            },

            // Affiche l'étape du champ, le fait défiler au centre de l'écran et montre la bulle d'aide du navigateur.
            signaler(champ) {
                this.$nextTick(() => {
                    champ.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    champ.reportValidity();
                });
            },

            etape1Valide() {
                const champ = this.premierChampInvalide(1);
                if (champ) {
                    this.step = 1;
                    this.signaler(champ);
                    return false;
                }
                if (this.careType !== 'urgence' && this.days.length === 0) {
                    this.step = 1;
                    this.joursManquants = true;
                    this.$nextTick(() => document.getElementById('jours-presence').scrollIntoView({ behavior: 'smooth', block: 'center' }));
                    return false;
                }
                return true;
            },

            allerEtape2() {
                if (!this.etape1Valide()) return;
                this.step = 2;
                this.$nextTick(() => this.$refs.formulaire.scrollIntoView({ behavior: 'smooth', block: 'start' }));
            },

            // Clic sur « Envoyer » : un champ vide de l'étape 1 (masquée) bloquerait l'envoi sans aucun message.
            // On revient donc à l'étape 1 et on montre le champ concerné.
            verifierAvantEnvoi(evenement) {
                if (!this.etape1Valide()) evenement.preventDefault();
            },

            // Lien du résumé des erreurs : ouvre la bonne étape (et le bloc « second parent ») puis place le curseur dans le champ.
            allerAuChamp(id) {
                const champ = document.getElementById(id);
                if (!champ) return;
                const etape = champ.closest('[data-etape]');
                if (etape) this.step = Number(etape.dataset.etape);
                if (id.startsWith('p2_')) this.p2Open = true;
                this.$nextTick(() => setTimeout(() => {
                    champ.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    champ.focus({ preventScroll: true });
                }, 50));
            },
        };
    }
</script>

<div x-data="preInscription(@js($configPreInscription))">

{{-- SECTION 1 : HERO COMPACT --}}
<section class="relative bg-mint pt-10 pb-16 overflow-hidden">
    <x-confetti shape="fleur" class="top-10 right-[8%] w-9 h-9 text-coral animate-wiggle" />
    <x-confetti shape="etoile" class="bottom-10 left-[8%] w-7 h-7 text-sun animate-float" />

    <div class="max-w-[860px] mx-auto px-6 text-center relative z-10">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/90 shadow-sm text-primary mb-4">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold">{{ __('Micro-crèche écoresponsable · Alfortville') }}</span>
        </div>
        <h1 class="font-display text-4xl md:text-5xl text-primary-deep">{{ __('Pré-inscrire mon enfant') }}</h1>
        <p class="font-body text-lg text-primary-deep/80 mt-4 max-w-xl mx-auto leading-relaxed">
            {{ __('Complétez votre demande en 2 étapes. Si aucune place n\'est disponible immédiatement, votre dossier sera automatiquement placé sur notre liste d\'attente prioritaire.') }}
        </p>
    </div>

    <x-hill-divider color="cream" />
</section>

{{-- SECTION 2 : CARTE FORMULAIRE --}}
<section class="bg-cream pb-20 px-6 -mt-4 relative z-20">
    <div class="max-w-[860px] mx-auto">
        <div class="bg-white rounded-[32px] shadow-xl p-6 md:p-12">

            {{-- Formulaire --}}
            <div>
                <x-formulaire.resume-erreurs alpine :champs="['child_last_name' => 'child-lastname', 'child_first_name' => 'child-firstname', 'child_gender' => 'child-gender', 'child_birthdate' => 'child-birthdate', 'entry_date' => 'entry-date', 'care_type' => 'type-accueil', 'care_days' => 'jours-presence', 'special_needs_details' => 'special-details', 'p1_nom' => 'p1_nom', 'p1_prenom' => 'p1_prenom', 'p1_email' => 'p1_email', 'p1_tel' => 'p1_tel', 'p1_fixe' => 'p1_fixe', 'p1_profession' => 'p1_profession', 'p1_employeur' => 'p1_employeur', 'p1_cp' => 'p1_cp', 'p1_ville' => 'p1_ville', 'p1_adresse' => 'p1_adresse', 'p2_nom' => 'p2_nom', 'p2_prenom' => 'p2_prenom', 'p2_email' => 'p2_email', 'p2_tel' => 'p2_tel', 'p2_profession' => 'p2_profession', 'p2_employeur' => 'p2_employeur', 'message' => 'message_libre', 'rgpd_consent' => 'pi-rgpd']" />

                <form method="POST" action="{{ route('pre-inscription.store') }}" x-ref="formulaire">
                @csrf
                <x-anti-spam />
                {{-- STEPPER --}}
                <div class="flex items-center justify-between max-w-lg mx-auto mb-10">
                    <button type="button" @click="step = 1" class="group flex flex-col items-center flex-1">
                        <div class="w-11 h-11 rounded-full flex items-center justify-center shadow-md transition-all"
                             :class="step >= 1 ? 'bg-primary text-white' : 'bg-mint text-primary-deep'">
                            <template x-if="step > 1"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></template>
                            <template x-if="step === 1"><span class="font-display">1</span></template>
                        </div>
                        <div class="mt-2 text-center">
                            <span class="font-body text-sm font-bold text-primary-deep block">{{ __('1 · Votre enfant') }}</span>
                        </div>
                    </button>
                    <div class="h-[2.5px] flex-1 -mt-6 mx-2 rounded-full bg-mint">
                        <div class="h-full bg-primary rounded-full transition-all duration-500" :style="step >= 2 ? 'width:100%' : 'width:0%'"></div>
                    </div>
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-11 h-11 rounded-full flex items-center justify-center font-display shadow-md transition-all"
                             :class="step >= 2 ? 'bg-primary text-white ring-4 ring-mint' : 'bg-mint text-primary-deep'">
                            2
                        </div>
                        <div class="mt-2 text-center">
                            <span class="font-body text-sm font-bold text-primary-deep block">{{ __('2 · Vos informations') }}</span>
                        </div>
                    </div>
                </div>

                {{-- ÉTAPE 1 : VOTRE ENFANT --}}
                <div x-show="step === 1" data-etape="1">
                    <div class="flex items-center gap-2 text-primary mb-1">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="6" r="3"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 21v-4a6 6 0 0112 0v4"/></svg>
                        <span class="font-body text-xs uppercase tracking-widest font-extrabold">{{ __('Dossier d\'accueil') }}</span>
                    </div>
                    <h2 class="font-display text-2xl text-primary-deep mb-1">{{ __('Informations concernant votre enfant') }}</h2>
                    <p class="font-body text-sm text-primary-deep/80 mb-6">{{ __('Ces éléments nous permettent d\'évaluer nos disponibilités et d\'adapter le groupe d\'âges.') }}</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="flex flex-col gap-1.5">
                            <label for="child-lastname" class="font-body text-sm font-bold text-primary-deep">{{ __('Nom de l\'enfant *') }}</label>
                            <input id="child-lastname" name="child_last_name" value="{{ old('child_last_name') }}" required type="text" placeholder="{{ __('ex. Martin') }}" class="h-[52px] px-5 rounded-full bg-cream text-primary-deep placeholder:text-primary-deep/40 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all @error('child_last_name') ring-2 ring-coral @enderror" @error('child_last_name') aria-invalid="true" aria-describedby="child-lastname-erreur" @enderror>
                            <x-formulaire.erreur champ="child_last_name" id="child-lastname" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="child-firstname" class="font-body text-sm font-bold text-primary-deep">{{ __('Prénom de l\'enfant *') }}</label>
                            <input id="child-firstname" name="child_first_name" value="{{ old('child_first_name') }}" required type="text" placeholder="{{ __('ex. Louise') }}" class="h-[52px] px-5 rounded-full bg-cream text-primary-deep placeholder:text-primary-deep/40 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all @error('child_first_name') ring-2 ring-coral @enderror" @error('child_first_name') aria-invalid="true" aria-describedby="child-firstname-erreur" @enderror>
                            <x-formulaire.erreur champ="child_first_name" id="child-firstname" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="child-gender" class="font-body text-sm font-bold text-primary-deep">{{ __('Sexe de l\'enfant *') }}</label>
                            <div class="relative">
                                <select id="child-gender" name="child_gender" required class="w-full h-[52px] pl-5 pr-14 rounded-full bg-cream text-primary-deep shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all appearance-none @error('child_gender') ring-2 ring-coral @enderror" @error('child_gender') aria-invalid="true" aria-describedby="child-gender-erreur" @enderror>
                                    <option disabled {{ old('child_gender') ? '' : 'selected' }} value="">{{ __('Sélectionner') }}</option>
                                    @foreach (['Fille', 'Garçon', 'Autre / Non précisé'] as $genre)
                                    <option value="{{ $genre }}" {{ old('child_gender') === $genre ? 'selected' : '' }}>{{ __($genre) }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-mint flex items-center justify-center text-primary pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                            <x-formulaire.erreur champ="child_gender" id="child-gender" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <div class="flex justify-between items-center">
                                <label for="child-birthdate" class="font-body text-sm font-bold text-primary-deep">{{ __('Date de naissance *') }}</label>
                                <span class="font-body text-xs text-primary-deep/75">{{ __('ou terme prévu') }}</span>
                            </div>
                            <input id="child-birthdate" name="child_birthdate" value="{{ old('child_birthdate') }}" required type="date" class="h-[52px] px-5 rounded-full bg-cream text-primary-deep shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all @error('child_birthdate') ring-2 ring-coral @enderror" @error('child_birthdate') aria-invalid="true" aria-describedby="child-birthdate-erreur" @enderror>
                            <x-formulaire.erreur champ="child_birthdate" id="child-birthdate" />
                        </div>
                        <div class="flex flex-col gap-1.5 md:col-span-2">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                                <label for="entry-date" class="font-body text-sm font-bold text-primary-deep">{{ __('Date d\'entrée souhaitée *') }}</label>
                                <span class="font-body text-xs text-coral">{{ __('Idéalement 2 mois avant la reprise d\'activité') }}</span>
                            </div>
                            <input id="entry-date" name="entry_date" value="{{ old('entry_date') }}" required type="date" class="h-[52px] px-5 rounded-full bg-cream text-primary-deep shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all @error('entry_date') ring-2 ring-coral @enderror" @error('entry_date') aria-invalid="true" aria-describedby="entry-date-erreur" @enderror>
                            <x-formulaire.erreur champ="entry_date" id="entry-date" />
                        </div>
                    </div>

                    {{-- Type d'accueil --}}
                    <div id="type-accueil" tabindex="-1" class="flex flex-col gap-3 mt-6 scroll-mt-28 focus:outline-none">
                        <input type="hidden" name="care_type" :value="careType">
                        <span class="font-body text-sm font-bold text-primary-deep">{{ __('Type d\'accueil souhaité *') }}</span>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            @foreach ([
                                ['v' => 'regulier', 'titre' => __('Accueil régulier'), 'texte' => __('1 à 5 jours par semaine, planning fixe.')],
                                ['v' => 'occasionnel', 'titre' => __('Accueil occasionnel'), 'texte' => __('Besoins ponctuels selon créneaux.')],
                                ['v' => 'urgence', 'titre' => __('Accueil d\'urgence'), 'texte' => __('Dépannage imprévu (2 places dédiées).')],
                            ] as $opt)
                            <div @click="careType = '{{ $opt['v'] }}'" class="cursor-pointer flex flex-col justify-between p-5 rounded-2xl shadow-sm transition-all"
                                 :class="careType === '{{ $opt['v'] }}' ? 'bg-mint ring-2 ring-primary' : 'bg-cream hover:bg-mint/40'">
                                <div class="w-5 h-5 rounded-full border-2 self-end flex items-center justify-center transition-all"
                                     :class="careType === '{{ $opt['v'] }}' ? 'border-primary bg-primary' : 'border-primary-deep/30'">
                                    <div class="w-2 h-2 rounded-full bg-white" x-show="careType === '{{ $opt['v'] }}'"></div>
                                </div>
                                <div class="mt-3">
                                    <span class="font-body font-bold text-primary-deep block">{{ $opt['titre'] }}</span>
                                    <span class="font-body text-sm text-primary-deep/80 block mt-1">{{ $opt['texte'] }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <x-formulaire.erreur champ="care_type" id="type-accueil" />
                    </div>

                    {{-- Jours de présence --}}
                    <div id="jours-presence" tabindex="-1" class="flex flex-col gap-3 mt-6 scroll-mt-28 focus:outline-none">
                        <div class="flex items-center justify-between">
                            <label class="font-body text-sm font-bold text-primary-deep">{{ __('Jours de présence souhaités *') }}</label>
                            <span class="font-body text-xs text-primary-deep/75">{{ __('Sélectionnez au moins un jour (sauf accueil d\'urgence)') }}</span>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <template x-for="d in days" :key="d"><input type="hidden" name="care_days[]" :value="d"></template>
                            @foreach (['lundi' => __('Lundi'), 'mardi' => __('Mardi'), 'mercredi' => __('Mercredi'), 'jeudi' => __('Jeudi'), 'vendredi' => __('Vendredi')] as $val => $label)
                            <button type="button" @click="toggleDay('{{ $val }}')"
                                    class="h-11 px-5 rounded-full flex items-center gap-2 font-body text-sm font-bold transition-all"
                                    :class="days.includes('{{ $val }}') ? 'bg-primary text-white shadow-sm' : 'bg-cream text-primary-deep hover:bg-mint/50'">
                                <svg x-show="days.includes('{{ $val }}')" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ $label }}</span>
                            </button>
                            @endforeach
                        </div>
                        <x-formulaire.erreur champ="care_days" id="jours-presence" />
                        <p x-show="joursManquants" x-cloak class="flex items-start gap-1.5 font-body text-sm font-semibold text-coral">{{ __('Merci de sélectionner au moins un jour de présence.') }}</p>
                    </div>

                    {{-- Besoins spécifiques --}}
                    <div class="bg-peach/40 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 mt-6">
                        <div class="flex items-start gap-3 max-w-xl">
                            <div class="w-9 h-9 rounded-full bg-coral text-white flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 6v12M6 12h12"/></svg>
                            </div>
                            <div class="flex flex-col">
                                <span id="special-needs-titre" class="font-body font-bold text-primary-deep">{{ __('Situation particulière, PAI ou besoins spécifiques') }}</span>
                                <span id="special-needs-aide" class="font-body text-sm text-primary-deep/80 mt-0.5">{{ __('Allergies, handicap, suivi particulier. Notre équipe est formée à l\'inclusion bienveillante.') }}</span>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="checkbox" name="special_needs" value="1" x-model="specialNeeds" aria-labelledby="special-needs-titre" aria-describedby="special-needs-aide" class="sr-only peer">
                            <div class="w-14 h-8 bg-white rounded-full peer peer-checked:bg-primary transition-all relative">
                                <div class="absolute top-1 left-1 bg-white w-6 h-6 rounded-full shadow transition-transform peer-checked:translate-x-6" :class="specialNeeds ? 'translate-x-6' : ''"></div>
                            </div>
                        </label>
                    </div>
                    <div x-show="specialNeeds" x-collapse class="flex flex-col gap-1.5 mt-3">
                        <label for="special-details" class="font-body text-sm font-bold text-primary-deep">{{ __('Précisions (facultatif)') }}</label>
                        <textarea id="special-details" name="special_needs_details" rows="3" placeholder="{{ __('Allergie alimentaire, régime particulier ou protocole médical...') }}" class="p-4 rounded-2xl bg-cream text-primary-deep placeholder:text-primary-deep/40 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all resize-none @error('special_needs_details') ring-2 ring-coral @enderror" @error('special_needs_details') aria-invalid="true" aria-describedby="special-details-erreur" @enderror>{{ old('special_needs_details') }}</textarea>
                        <x-formulaire.erreur champ="special_needs_details" id="special-details" />
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-8">
                        <div class="flex items-center gap-2 text-primary-deep/80 font-body text-sm">
                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V7a4 4 0 018 0v4"/></svg>
                            <span>{{ __('Données strictement confidentielles & conformes RGPD') }}</span>
                        </div>
                        <button type="button" @click="allerEtape2()" class="w-full sm:w-auto h-[52px] px-8 rounded-full bg-coral text-white font-body font-bold text-sm flex items-center justify-center gap-2 shadow-lg hover:-translate-y-0.5 hover:opacity-90 transition-all">
                            <span>{{ __('Continuer vers l\'étape 2') }}</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </button>
                    </div>
                </div>

                {{-- ÉTAPE 2 : VOS INFORMATIONS --}}
                <div x-show="step === 2" x-cloak data-etape="2">
                    {{-- Parent 1 --}}
                    <div class="flex items-center gap-2 mb-5">
                        <div class="w-8 h-8 rounded-full bg-mint text-primary flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="3"/><path stroke-linecap="round" d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6"/></svg>
                        </div>
                        <h2 class="font-display text-xl text-primary-deep">{{ __('Parent 1 — Référent principal') }}</h2>
                    </div>

                    <div class="mb-5">
                        <label class="block font-body text-sm font-bold text-primary-deep mb-2">{{ __('Civilité *') }}</label>
                        <div class="inline-flex flex-wrap p-1.5 rounded-full bg-cream gap-1.5">
                            @foreach (['Madame', 'Monsieur', 'Autre'] as $civ)
                            <label class="cursor-pointer">
                                <input type="radio" name="p1_civilite" value="{{ $civ }}" class="peer sr-only" {{ old('p1_civilite', 'Madame') === $civ ? 'checked' : '' }}>
                                <span class="px-4 py-2 rounded-full font-body text-sm text-primary-deep block select-none transition-all peer-checked:bg-primary peer-checked:text-white peer-checked:shadow-sm">{{ __($civ) }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label for="p1_nom" class="font-body text-sm font-bold text-primary-deep">{{ __('Nom de famille *') }}</label>
                            <input id="p1_nom" name="p1_nom" value="{{ old('p1_nom') }}" required type="text" placeholder="{{ __('ex. Dupont') }}" class="h-[52px] px-5 rounded-2xl bg-cream text-primary-deep placeholder:text-primary-deep/40 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all @error('p1_nom') ring-2 ring-coral @enderror" @error('p1_nom') aria-invalid="true" aria-describedby="p1_nom-erreur" @enderror>
                            <x-formulaire.erreur champ="p1_nom" id="p1_nom" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="p1_prenom" class="font-body text-sm font-bold text-primary-deep">{{ __('Prénom *') }}</label>
                            <input id="p1_prenom" name="p1_prenom" value="{{ old('p1_prenom') }}" required type="text" placeholder="{{ __('ex. Camille') }}" class="h-[52px] px-5 rounded-2xl bg-cream text-primary-deep placeholder:text-primary-deep/40 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all @error('p1_prenom') ring-2 ring-coral @enderror" @error('p1_prenom') aria-invalid="true" aria-describedby="p1_prenom-erreur" @enderror>
                            <x-formulaire.erreur champ="p1_prenom" id="p1_prenom" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="p1_email" class="font-body text-sm font-bold text-primary-deep">{{ __('Adresse email *') }}</label>
                            <input id="p1_email" name="p1_email" value="{{ old('p1_email') }}" required type="email" placeholder="{{ __('camille.dupont@exemple.fr') }}" class="h-[52px] px-5 rounded-2xl bg-cream text-primary-deep placeholder:text-primary-deep/40 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all @error('p1_email') ring-2 ring-coral @enderror" @error('p1_email') aria-invalid="true" aria-describedby="p1_email-erreur" @enderror>
                            <x-formulaire.erreur champ="p1_email" id="p1_email" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="p1_tel" class="font-body text-sm font-bold text-primary-deep">{{ __('Téléphone portable *') }}</label>
                            <input id="p1_tel" name="p1_tel" value="{{ old('p1_tel') }}" required type="tel" placeholder="06 12 34 56 78" class="h-[52px] px-5 rounded-2xl bg-cream text-primary-deep placeholder:text-primary-deep/40 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all @error('p1_tel') ring-2 ring-coral @enderror" @error('p1_tel') aria-invalid="true" aria-describedby="p1_tel-erreur" @enderror>
                            <x-formulaire.erreur champ="p1_tel" id="p1_tel" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="p1_fixe" class="font-body text-sm font-bold text-primary-deep">{{ __('Téléphone secondaire') }} <span class="font-normal text-primary-deep/75">{{ __('(facultatif)') }}</span></label>
                            <input id="p1_fixe" name="p1_fixe" value="{{ old('p1_fixe') }}" type="tel" placeholder="01 43 ..." class="h-[52px] px-5 rounded-2xl bg-cream text-primary-deep placeholder:text-primary-deep/40 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all @error('p1_fixe') ring-2 ring-coral @enderror" @error('p1_fixe') aria-invalid="true" aria-describedby="p1_fixe-erreur" @enderror>
                            <x-formulaire.erreur champ="p1_fixe" id="p1_fixe" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="p1_profession" class="font-body text-sm font-bold text-primary-deep">{{ __('Profession *') }}</label>
                            <input id="p1_profession" name="p1_profession" value="{{ old('p1_profession') }}" type="text" placeholder="{{ __('ex. Professeure des écoles') }}" class="h-[52px] px-5 rounded-2xl bg-cream text-primary-deep placeholder:text-primary-deep/40 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all @error('p1_profession') ring-2 ring-coral @enderror" @error('p1_profession') aria-invalid="true" aria-describedby="p1_profession-erreur" @enderror>
                            <x-formulaire.erreur champ="p1_profession" id="p1_profession" />
                        </div>
                        <div class="md:col-span-2 flex flex-col gap-1.5">
                            <div class="flex items-center justify-between">
                                <label for="p1_employeur" class="font-body text-sm font-bold text-primary-deep">{{ __('Employeur / Société') }}</label>
                                <a href="/berceaux-entreprise" class="inline-flex items-center gap-1 font-body text-primary text-xs bg-mint/50 px-2 py-0.5 rounded-full font-bold hover:bg-mint transition-colors">{{ __('Réservation berceau entreprise') }}</a>
                            </div>
                            <input id="p1_employeur" name="p1_employeur" value="{{ old('p1_employeur') }}" type="text" placeholder="{{ __('Nom de l\'entreprise ou structure') }}" class="h-[52px] px-5 rounded-2xl bg-cream text-primary-deep placeholder:text-primary-deep/40 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all @error('p1_employeur') ring-2 ring-coral @enderror" @error('p1_employeur') aria-invalid="true" aria-describedby="p1_employeur-erreur" @enderror>
                            <p class="font-body text-xs text-primary-deep/75">{{ __('Votre employeur peut, dans certains cas, financer une partie de la place.') }}</p>
                            <x-formulaire.erreur champ="p1_employeur" id="p1_employeur" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="p1_cp" class="font-body text-sm font-bold text-primary-deep">{{ __('Code postal *') }}</label>
                            <input id="p1_cp" name="p1_cp" value="{{ old('p1_cp', $etab->codePostal()) }}" type="text" class="h-[52px] px-5 rounded-2xl bg-cream text-primary-deep shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all @error('p1_cp') ring-2 ring-coral @enderror" @error('p1_cp') aria-invalid="true" aria-describedby="p1_cp-erreur" @enderror>
                            <x-formulaire.erreur champ="p1_cp" id="p1_cp" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="p1_ville" class="font-body text-sm font-bold text-primary-deep">{{ __('Ville *') }}</label>
                            <input id="p1_ville" name="p1_ville" value="{{ old('p1_ville', 'Alfortville') }}" type="text" class="h-[52px] px-5 rounded-2xl bg-cream text-primary-deep shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all @error('p1_ville') ring-2 ring-coral @enderror" @error('p1_ville') aria-invalid="true" aria-describedby="p1_ville-erreur" @enderror>
                            <x-formulaire.erreur champ="p1_ville" id="p1_ville" />
                        </div>
                        <div class="md:col-span-2 flex flex-col gap-1.5">
                            <label for="p1_adresse" class="font-body text-sm font-bold text-primary-deep">{{ __('Adresse postale *') }}</label>
                            <input id="p1_adresse" name="p1_adresse" value="{{ old('p1_adresse') }}" type="text" placeholder="{{ __('Numéro et nom de voie') }}" class="h-[52px] px-5 rounded-2xl bg-cream text-primary-deep placeholder:text-primary-deep/40 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all @error('p1_adresse') ring-2 ring-coral @enderror" @error('p1_adresse') aria-invalid="true" aria-describedby="p1_adresse-erreur" @enderror>
                            <x-formulaire.erreur champ="p1_adresse" id="p1_adresse" />
                        </div>
                    </div>

                    <label class="mt-4 inline-flex items-center gap-2 p-3 rounded-xl hover:bg-mint/30 transition-colors cursor-pointer">
                        <input type="checkbox" name="single_parent" value="1" {{ old('single_parent') ? 'checked' : '' }} class="w-5 h-5 rounded text-primary accent-primary">
                        <span class="font-body text-primary-deep font-semibold">{{ __('Situation de parent isolé / famille monoparentale') }}</span>
                    </label>

                    {{-- Parent 2 accordéon --}}
                    <div class="rounded-2xl bg-mint/30 overflow-hidden mt-4">
                        <button type="button" @click="p2Open = !p2Open" class="w-full px-5 py-4 flex items-center justify-between text-left hover:bg-mint/40 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-white text-primary flex items-center justify-center shadow-sm">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="9" cy="8" r="3"/><path stroke-linecap="round" d="M3 20c0-3 2.5-5 6-5s6 2 6 5M17 8h4M19 6v4"/></svg>
                                </div>
                                <div>
                                    <span class="font-display text-base text-primary-deep block">{{ __('Ajouter un second parent / responsable légal') }}</span>
                                    <span class="font-body text-xs text-primary-deep/75">{{ __('Recommandé pour faciliter le suivi et les autorisations') }}</span>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-primary transition-transform duration-200" :class="p2Open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="p2Open" x-collapse class="px-5 pb-5">
                            <div class="mb-4">
                                <label class="block font-body text-sm font-bold text-primary-deep mb-2">{{ __('Civilité') }}</label>
                                <div class="inline-flex flex-wrap p-1.5 rounded-full bg-white gap-1.5">
                                    @foreach (['Madame', 'Monsieur', 'Autre'] as $civ)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="p2_civilite" value="{{ $civ }}" class="peer sr-only" {{ old('p2_civilite', 'Monsieur') === $civ ? 'checked' : '' }}>
                                        <span class="px-4 py-1.5 rounded-full font-body text-sm text-primary-deep block transition-all peer-checked:bg-primary peer-checked:text-white">{{ __($civ) }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex flex-col gap-1.5">
                                    <label for="p2_nom" class="font-body text-sm font-bold text-primary-deep">{{ __('Nom de famille') }}</label>
                                    <input id="p2_nom" name="p2_nom" value="{{ old('p2_nom') }}" type="text" placeholder="{{ __('ex. Dupont') }}" class="h-[52px] px-5 rounded-2xl bg-white text-primary-deep shadow-sm focus:outline-none focus:ring-2 focus:ring-primary @error('p2_nom') ring-2 ring-coral @enderror" @error('p2_nom') aria-invalid="true" aria-describedby="p2_nom-erreur" @enderror>
                                    <x-formulaire.erreur champ="p2_nom" id="p2_nom" />
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label for="p2_prenom" class="font-body text-sm font-bold text-primary-deep">{{ __('Prénom') }}</label>
                                    <input id="p2_prenom" name="p2_prenom" value="{{ old('p2_prenom') }}" type="text" placeholder="{{ __('ex. Julien') }}" class="h-[52px] px-5 rounded-2xl bg-white text-primary-deep shadow-sm focus:outline-none focus:ring-2 focus:ring-primary @error('p2_prenom') ring-2 ring-coral @enderror" @error('p2_prenom') aria-invalid="true" aria-describedby="p2_prenom-erreur" @enderror>
                                    <x-formulaire.erreur champ="p2_prenom" id="p2_prenom" />
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label for="p2_email" class="font-body text-sm font-bold text-primary-deep">{{ __('Email') }}</label>
                                    <input id="p2_email" name="p2_email" value="{{ old('p2_email') }}" type="email" placeholder="{{ __('julien.dupont@exemple.fr') }}" class="h-[52px] px-5 rounded-2xl bg-white text-primary-deep shadow-sm focus:outline-none focus:ring-2 focus:ring-primary @error('p2_email') ring-2 ring-coral @enderror" @error('p2_email') aria-invalid="true" aria-describedby="p2_email-erreur" @enderror>
                                    <x-formulaire.erreur champ="p2_email" id="p2_email" />
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label for="p2_tel" class="font-body text-sm font-bold text-primary-deep">{{ __('Téléphone mobile') }}</label>
                                    <input id="p2_tel" name="p2_tel" value="{{ old('p2_tel') }}" type="tel" placeholder="06 98 76 54 32" class="h-[52px] px-5 rounded-2xl bg-white text-primary-deep shadow-sm focus:outline-none focus:ring-2 focus:ring-primary @error('p2_tel') ring-2 ring-coral @enderror" @error('p2_tel') aria-invalid="true" aria-describedby="p2_tel-erreur" @enderror>
                                    <x-formulaire.erreur champ="p2_tel" id="p2_tel" />
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label for="p2_profession" class="font-body text-sm font-bold text-primary-deep">{{ __('Profession') }}</label>
                                    <input id="p2_profession" name="p2_profession" value="{{ old('p2_profession') }}" type="text" placeholder="{{ __('ex. Comptable') }}" class="h-[52px] px-5 rounded-2xl bg-white text-primary-deep shadow-sm focus:outline-none focus:ring-2 focus:ring-primary @error('p2_profession') ring-2 ring-coral @enderror" @error('p2_profession') aria-invalid="true" aria-describedby="p2_profession-erreur" @enderror>
                                    <x-formulaire.erreur champ="p2_profession" id="p2_profession" />
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label for="p2_employeur" class="font-body text-sm font-bold text-primary-deep">{{ __('Employeur / Société') }}</label>
                                    <input id="p2_employeur" name="p2_employeur" value="{{ old('p2_employeur') }}" type="text" placeholder="{{ __('Entreprise') }}" class="h-[52px] px-5 rounded-2xl bg-white text-primary-deep shadow-sm focus:outline-none focus:ring-2 focus:ring-primary @error('p2_employeur') ring-2 ring-coral @enderror" @error('p2_employeur') aria-invalid="true" aria-describedby="p2_employeur-erreur" @enderror>
                                    <x-formulaire.erreur champ="p2_employeur" id="p2_employeur" />
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Message libre --}}
                    <div class="mt-6">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 20l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg>
                            <label for="message_libre" class="font-display text-lg text-primary-deep">{{ __('Un message pour notre équipe ?') }} <span class="font-body text-sm text-primary-deep/75 font-normal">{{ __('(facultatif)') }}</span></label>
                        </div>
                        <p class="font-body text-sm text-primary-deep/80 mb-2">{{ __('Rythme particulier, allaitement, allergies, adaptation progressive, ou toute autre attention utile.') }}</p>
                        <textarea id="message_libre" name="message" rows="4" placeholder="{{ __('Partagez les détails importants pour accueillir votre enfant en douceur...') }}" class="w-full p-4 rounded-2xl bg-cream text-primary-deep placeholder:text-primary-deep/40 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all resize-y min-h-[110px] @error('message') ring-2 ring-coral @enderror" @error('message') aria-invalid="true" aria-describedby="message_libre-erreur" @enderror>{{ old('message') }}</textarea>
                        <x-formulaire.erreur champ="message" id="message_libre" />
                    </div>

                    {{-- RGPD --}}
                    <div class="flex flex-col gap-3 mt-6">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input id="pi-rgpd" type="checkbox" name="rgpd_consent" value="1" {{ old('rgpd_consent') ? 'checked' : '' }} required class="w-5 h-5 mt-1 rounded text-primary accent-primary shrink-0 @error('rgpd_consent') outline outline-2 outline-offset-2 outline-coral @enderror" @error('rgpd_consent') aria-invalid="true" aria-describedby="pi-rgpd-erreur" @enderror>
                            <span class="font-body text-sm text-primary-deep leading-snug">{{ __('J\'accepte que mes données personnelles soient traitées dans le cadre strict de ma demande de pré-inscription, conformément à la politique de confidentialité. *') }}</span>
                        </label>
                        <x-formulaire.erreur champ="rgpd_consent" id="pi-rgpd" />
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="newsletter_opt_in" value="1" {{ old('newsletter_opt_in') ? 'checked' : '' }} class="w-5 h-5 mt-1 rounded text-primary accent-primary shrink-0">
                            <span class="font-body text-sm text-primary-deep/80 leading-snug">{{ __('Je souhaite recevoir les actualités du Jardin de Régina (ateliers parents-enfants, conseils éveil & nature).') }}</span>
                        </label>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-8">
                        <button type="button" @click="step = 1" class="w-full sm:w-auto h-[52px] px-6 rounded-full bg-cream text-primary hover:bg-mint/50 transition-all font-body font-bold text-sm flex items-center justify-center gap-2 shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5 5-5M6 12h13"/></svg>
                            <span>{{ __('Retour à l\'étape 1') }}</span>
                        </button>
                        <button type="submit" @click="verifierAvantEnvoi($event)" class="w-full sm:w-auto h-[52px] px-8 rounded-full bg-coral text-white font-body font-bold text-sm flex items-center justify-center gap-2 shadow-lg hover:-translate-y-0.5 hover:opacity-90 transition-all">
                            <span>{{ __('Envoyer ma demande de pré-inscription') }}</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </div>

        {{-- Bloc de réassurance --}}
        <div class="mt-8 bg-peach/50 rounded-[32px] p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-full bg-coral text-white flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21s-7-4.35-9.5-8.5C.5 8.5 2.5 5 6 5c2 0 3.5 1.5 4 2.5.5-1 2-2.5 4-2.5 3.5 0 5.5 3.5 3.5 7.5C19 16.65 12 21 12 21z"/></svg>
                </div>
                <div>
                    <h3 class="font-display text-lg text-primary-deep">{{ __('Que se passe-t-il après l\'envoi de votre demande ?') }}</h3>
                    <p class="font-body text-xs text-primary-deep/75">{{ __('Un accompagnement transparent, sans mauvaise surprise') }}</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ([
                    ['n' => 1, 'titre' => __('Accusé de réception'), 'texte' => __('Un e-mail récapitulatif avec votre numéro de dossier vous est transmis immédiatement.')],
                    ['n' => 2, 'titre' => __('Étude de disponibilité'), 'texte' => __('Notre équipe étudie votre calendrier et nos disponibilités sous 48h ouvrées.')],
                    ['n' => 3, 'titre' => __('Échange & visite'), 'texte' => __('Nous convenons d\'un rendez-vous sur place à Alfortville pour découvrir les espaces.')],
                ] as $step)
                <div class="bg-white/80 rounded-2xl p-5 flex flex-col gap-1.5 shadow-sm">
                    <span class="w-7 h-7 rounded-full bg-coral text-white font-body text-sm font-bold flex items-center justify-center">{{ $step['n'] }}</span>
                    <span class="font-display text-base text-primary-deep mt-1">{{ $step['titre'] }}</span>
                    <p class="font-body text-xs text-primary-deep/80">{{ $step['texte'] }}</p>
                </div>
                @endforeach
            </div>
            <div class="mt-6 pt-4 border-t border-white/60 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="inline-flex items-center gap-2 text-primary font-body text-sm font-bold">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ __('Pré-inscription 100% gratuite et sans engagement') }}</span>
                </div>
                <div class="inline-flex items-center gap-2 text-primary-deep/80 font-body text-sm">
                    <svg class="w-4 h-4 text-coral" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2l2 5-2 1a11 11 0 006 6l1-2 5 2v2a2 2 0 01-2 2h-1C9 19 3 13 3 6V5z"/></svg>
                    <span>{{ __('Besoin d\'aide ? :telephone', ['telephone' => $etab->telephone()]) }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

</div>
@endsection
