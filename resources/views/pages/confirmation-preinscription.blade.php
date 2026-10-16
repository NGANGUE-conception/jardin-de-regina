@extends('layouts.app')

@section('title', __('Demande envoyée — Le Jardin de Régina'))
@section('robots', 'noindex, follow')

@section('content')
<section class="relative bg-cream py-16 md:py-24 overflow-hidden" x-data="{ copied: false, hour: '', dossier: '{{ $numeroDossier ?? '' }}' }" x-init="hour = new Date().getHours().toString().padStart(2,'0') + 'h' + new Date().getMinutes().toString().padStart(2,'0')">
    <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-[780px] h-[520px] bg-gradient-to-b from-mint via-peach to-transparent rounded-full blur-3xl -z-10 opacity-70 pointer-events-none"></div>
    <div class="absolute top-[420px] -left-32 w-96 h-96 bg-mint/60 rounded-full blur-3xl -z-10 pointer-events-none"></div>
    <div class="absolute top-[520px] -right-24 w-96 h-96 bg-peach/60 rounded-full blur-3xl -z-10 pointer-events-none"></div>

    <x-confetti shape="etoile" class="top-16 left-[8%] w-7 h-7 text-sun animate-float" />
    <x-confetti shape="fleur" class="top-1/3 right-[6%] w-6 h-6 text-coral animate-wiggle" />
    <x-confetti shape="rond" class="bottom-24 left-[10%] w-5 h-5 bg-sky animate-bounce-soft" />

    <div class="max-w-[1080px] mx-auto px-6 flex flex-col items-center relative z-10">

        {{-- 1. Badge de succès --}}
        <div class="flex flex-col items-center text-center max-w-2xl mx-auto mb-10">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-mint/60 text-primary font-body text-xs uppercase tracking-widest font-extrabold mb-6 shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
                {{ __('Pré-inscription transmise avec succès') }}
            </span>

            <div class="relative w-32 h-32 flex items-center justify-center mb-6">
                <div class="absolute -top-2 -left-2 w-24 h-24 bg-peach rounded-full blur-sm -rotate-12"></div>
                <div class="absolute -bottom-1 -right-2 w-24 h-24 bg-mint rounded-full blur-sm rotate-12"></div>
                <div class="relative w-24 h-24 rounded-full bg-primary text-white flex items-center justify-center shadow-lg animate-bounce-soft">
                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div class="absolute top-1 right-1 w-3 h-3 rounded-full bg-coral animate-bounce-soft"></div>
                <div class="absolute bottom-2 left-1 w-2.5 h-2.5 rounded-full bg-sun"></div>
            </div>

            <h1 class="font-display text-3xl md:text-4xl text-primary-deep mb-3">{{ __('Merci, votre demande est bien enregistrée !') }}</h1>
            <p class="font-body text-lg text-primary-deep/80 leading-relaxed max-w-xl">
                {{ __('Nous avons bien reçu la pré-inscription de') }} <strong class="text-primary-deep">{{ __('votre tout-petit') }}</strong>{{ __('. Notre équipe étudie votre dossier et vous recontactera très prochainement par téléphone ou par e-mail.') }}
            </p>
        </div>

        {{-- 2. Carte récapitulatif --}}
        <div class="w-full max-w-[820px] bg-white rounded-3xl p-6 sm:p-8 shadow-lg mb-10">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 pb-6 border-b border-mint">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-mint flex items-center justify-center text-primary shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div class="font-body text-xs uppercase tracking-wider text-primary-deep/75">{{ __('Identifiant dossier') }}</div>
                        <div class="font-display text-xl text-primary flex items-center gap-2">
                            <span x-text="dossier || @js(__('Référence non disponible'))"></span>
                            <button type="button" x-show="dossier" @click="navigator.clipboard && navigator.clipboard.writeText(dossier); copied = true; setTimeout(() => copied = false, 2000)" class="relative text-primary-deep/40 hover:text-primary transition-colors" title="{{ __('Copier la référence') }}">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="12" height="12" rx="2"/><path d="M5 15V5a2 2 0 012-2h10"/></svg>
                                <span x-show="copied" x-cloak x-transition class="absolute -top-8 -left-4 bg-primary-deep text-white text-xs font-bold px-2 py-1 rounded-full whitespace-nowrap">{{ __('Copié !') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2 self-stretch md:self-auto justify-end">
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-cream text-primary-deep/80 font-body text-sm">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg>
                        <span>{{ __('Aujourd\'hui à') }} <span x-text="hour"></span></span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-peach text-coral font-body text-sm font-semibold">
                        <span class="w-2 h-2 rounded-full bg-coral animate-bounce-soft"></span>
                        <span>{{ __('Dossier en examen') }}</span>
                    </span>
                </div>
            </div>
            <div class="pt-5 flex items-start gap-3.5 text-primary-deep/80 font-body">
                <svg class="w-6 h-6 text-primary shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
                <p class="leading-relaxed">
                    <strong class="text-primary-deep font-semibold">{{ __('Dossier bien enregistré.') }}</strong> {{ __('Conservez cette référence : elle vous sera utile pour tout échange avec notre équipe au sujet de votre demande.') }}
                </p>
            </div>
        </div>

        {{-- 3. Et maintenant --}}
        <div class="w-full max-w-[940px] mb-12">
            <div class="text-center mb-8">
                <h2 class="font-display text-2xl md:text-3xl text-primary-deep">{{ __('Et maintenant ? Les prochaines étapes') }}</h2>
                <p class="font-body text-primary-deep/80 mt-2 max-w-lg mx-auto">{{ __('Un parcours clair et personnalisé pour préparer en douceur l\'accueil de votre enfant.') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative">
                <div class="hidden md:block absolute top-[46px] left-[18%] right-[18%] h-0.5 border-t-2 border-dashed border-primary/25 -z-0"></div>
                @foreach ([
                    ['n' => 1, 'tag' => __('Étape initiale'), 'titre' => __('Étude de votre demande'), 'texte' => __('Notre équipe analyse vos critères (date d\'entrée, amplitude horaire, âge de l\'enfant) au regard des berceaux disponibles.'), 'foot' => __('Critères équitables & transparents'), 'tagcolor' => 'text-primary-deep/75'],
                    ['n' => 2, 'tag' => __('Sous 5 à 7 jours'), 'titre' => __('Prise de contact personnalisée'), 'texte' => __('Nous échangeons par téléphone ou e-mail pour faire connaissance et vous confirmer les disponibilités réelles.'), 'foot' => __('Accompagnement bienveillant'), 'tagcolor' => 'text-coral font-bold'],
                    ['n' => 3, 'tag' => __('Visite privée'), 'titre' => __('Rendez-vous & immersion'), 'texte' => __('Rencontre à la crèche (:rue), visite des espaces d\'éveil et finalisation du contrat d\'accueil.', ['rue' => $etab->rue()]), 'foot' => __('Découverte avec bébé'), 'tagcolor' => 'text-primary-deep/75'],
                ] as $step)
                <div class="relative bg-white rounded-3xl p-6 flex flex-col items-start shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all">
                    <div class="flex items-center justify-between w-full mb-4">
                        <div class="w-12 h-12 rounded-full bg-mint text-primary font-display font-semibold flex items-center justify-center">{{ $step['n'] }}</div>
                        <span class="font-body text-xs uppercase tracking-widest {{ $step['tagcolor'] }}">{{ $step['tag'] }}</span>
                    </div>
                    <h3 class="font-display text-lg text-primary-deep mb-2">{{ $step['titre'] }}</h3>
                    <p class="font-body text-sm text-primary-deep/80 leading-relaxed">{{ $step['texte'] }}</p>
                    <div class="mt-4 pt-3 flex items-center gap-1.5 text-xs text-primary font-bold">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ $step['foot'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- 4. Liste d'attente --}}
        <div class="w-full max-w-[820px] bg-peach/50 rounded-3xl p-6 sm:p-7 mb-12 flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-coral shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
            </div>
            <div>
                <h4 class="font-display text-lg text-primary-deep mb-1">{{ __('Information importante sur l\'attribution des places') }}</h4>
                <p class="font-body text-primary-deep/80 leading-relaxed">
                    {{ __('Si aucune place immédiate ne correspond strictement à votre calendrier d\'entrée, votre dossier sera placé sur notre') }} <strong class="text-primary-deep">{{ __('liste d\'attente prioritaire') }}</strong>{{ __(', selon nos critères transparents (résidence à Alfortville, fratrie inscrite, ancienneté du dépôt).') }}
                </p>
            </div>
        </div>

        {{-- 5. Actions --}}
        <div class="w-full max-w-2xl flex flex-col items-center text-center">
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 w-full sm:w-auto mb-8">
                <a href="{{ route_langue('accueil') }}" class="w-full sm:w-auto h-[52px] px-8 rounded-full bg-coral text-white font-body font-bold text-sm inline-flex items-center justify-center shadow-lg hover:-translate-y-0.5 hover:opacity-90 transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5 5-5M6 12h13"/></svg>
                    {{ __('Retour à l\'accueil') }}
                </a>
                <a href="{{ route_langue('notre-projet') }}" class="w-full sm:w-auto h-[52px] px-8 rounded-full bg-white text-primary font-body font-bold text-sm inline-flex items-center justify-center shadow-sm hover:bg-mint/40 transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
                    {{ __('Découvrir notre projet') }}
                </a>
            </div>

            <div class="p-4 rounded-full bg-white/80 flex flex-wrap items-center justify-center gap-2 text-primary-deep/80 font-body text-sm max-w-xl shadow-sm">
                <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="3"/><path stroke-linecap="round" d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6"/></svg>
                <span>{{ __('Une question urgente sur votre pré-inscription ?') }}</span>
                <span class="hidden sm:inline text-primary-deep/30">•</span>
                <a href="tel:{{ $etab->telephoneLien() }}" class="text-primary font-bold hover:underline">{{ $etab->telephone() }}</a>
                <span class="text-primary-deep/30">•</span>
                <a href="mailto:{{ $etab->email() }}" class="text-primary font-bold hover:underline">{{ $etab->email() }}</a>
            </div>
        </div>
    </div>
</section>
@endsection
