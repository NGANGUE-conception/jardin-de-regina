@extends('layouts.app')

@section('title', __('Berceaux entreprise à Alfortville — Le Jardin de Régina'))
@section('description', __('Réservez des places en crèche pour vos salariés près d\'Alfortville : un atout pour attirer et fidéliser vos équipes. Demandez un devis personnalisé.'))
  


@section('content')

{{-- SECTION 1 : HERO --}}
<section class="relative bg-cream pt-10 pb-16 overflow-hidden">
    <div class="absolute -top-24 -left-20 w-[500px] h-[500px] rounded-full bg-mint blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute top-40 -right-24 w-[420px] h-[420px] rounded-full bg-peach blur-3xl pointer-events-none -z-10"></div>

    <x-confetti shape="etoile" class="top-20 left-[6%] w-8 h-8 text-sun animate-float" />
    <x-confetti shape="squiggle" class="bottom-10 left-[40%] w-9 h-9 text-primary animate-float-slower" />
    <x-confetti shape="rond" class="top-1/2 right-[4%] w-4 h-4 bg-sky animate-bounce-soft" />

    <div class="max-w-[1240px] mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">

            <div class="lg:col-span-7 flex flex-col items-start gap-4">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-mint/60 text-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
                    <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold">{{ __('Solution employeur · Berceaux d\'entreprise') }}</span>
                </div>

                <h1 class="font-display text-4xl md:text-5xl text-primary-deep leading-[1.15]">
                    {{ __('Offrez à vos salariés une place en crèche,') }} <span class="text-coral">{{ __('à deux pas') }}</span> {{ __('de leur quotidien') }}
                </h1>

                <p class="font-body text-lg text-primary-deep/80 max-w-xl leading-relaxed">
                    {{ __('Réservez des berceaux au') }} <strong class="text-primary-deep">{{ __('Jardin de Régina') }}</strong> {{ __('et facilitez la vie de vos collaborateurs parents. Une solution de garde fiable, écoresponsable et chaleureuse au cœur d\'Alfortville.') }}
                </p>

                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <a href="#devis-form" class="h-[52px] px-8 rounded-full bg-coral text-white font-body font-bold text-sm flex items-center gap-2 shadow-lg hover:-translate-y-0.5 hover:opacity-90 transition-all">
                        <span>{{ __('Demander un devis') }}</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="#fonctionnement-b2b" class="h-[52px] px-8 rounded-full bg-white text-primary font-body font-bold text-sm flex items-center justify-center shadow-sm hover:bg-mint/50 transition-all">
                        {{ __('Comment ça marche ?') }}
                    </a>
                </div>

                <div class="pt-4 flex flex-wrap items-center gap-x-6 gap-y-2 text-primary-deep/80 font-body text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ __('Places réservées prioritaires') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M4 21V10l8-6 8 6v11M9 21v-6h6v6"/></svg>
                        <span>{{ __('Co-financement possible') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-7.58 7-12A7 7 0 105 9c0 4.42 7 12 7 12z"/><circle cx="12" cy="9" r="2.3"/></svg>
                        <span>{{ $etab->ville() }} ({{ $etab->codePostal() }})</span>
                    </div>
                </div>
            </div>

            {{-- Colonne visuelle en arche --}}
            <div class="lg:col-span-5 relative flex justify-center items-center">
                <div class="absolute -top-6 -right-6 w-72 h-80 bg-mint/70 rounded-[4rem] rotate-6 -z-10"></div>
                <div class="absolute -bottom-8 -left-6 w-64 h-64 bg-peach/80 rounded-[3rem] -rotate-12 -z-10"></div>

                <div class="relative w-full max-w-[420px] aspect-[4/5] rounded-t-[10rem] rounded-b-[3rem] overflow-hidden shadow-2xl bg-peach">
                    <img src="{{ asset('images/accueil-bienveillant.webp') }}" alt="{{ __('Un parent confie son enfant à l\'équipe du Jardin de Régina') }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-primary-deep/40 via-transparent to-transparent"></div>
                </div>

                <div class="absolute bottom-6 -left-4 sm:left-4 bg-white/95 backdrop-blur-md px-4 py-3 rounded-full shadow-lg flex items-center gap-3 z-20">
                    <div class="w-9 h-9 rounded-full bg-mint flex items-center justify-center text-primary shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="8" cy="9" r="3"/><circle cx="16" cy="9" r="3"/><path stroke-linecap="round" d="M2 20c0-3 3-5 6-5s6 2 6 5M10 20c0-3 3-5 6-5s6 2 6 5"/></svg>
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="font-body text-sm font-bold text-primary-deep">{{ __('12 places exclusives') }}</span>
                        <span class="font-body text-xs text-primary-deep/80">{{ __('Dès 10 semaines jusqu\'à 3 ans et plus') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-hill-divider color="mint" />
</section>

{{-- SECTION 2 : POURQUOI RÉSERVER DES BERCEAUX --}}
<section class="bg-mint py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <x-confetti shape="fleur" class="top-14 right-[8%] w-8 h-8 text-coral animate-wiggle" />
    <x-confetti shape="rond" class="bottom-14 left-[10%] w-5 h-5 bg-white animate-float-slow" />

    <div class="max-w-[1240px] mx-auto px-6 reveal relative z-10" :class="{ 'reveal-shown': shown }">
        <div class="text-center max-w-2xl mx-auto flex flex-col items-center">
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-white shadow-sm">{{ __('Avantages entreprise & RH') }}</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">{{ __('Un levier de performance et d\'attractivité pour votre organisation') }}</h2>
            <p class="font-body text-primary-deep/80 mt-2">{{ __('La réservation de berceaux transforme le bien-être de vos équipes en un atout durable et mesurable.') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-12">
            <div class="bg-white rounded-3xl p-7 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all flex flex-col gap-3">
                <div class="w-14 h-14 rounded-2xl bg-mint flex items-center justify-center text-primary">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21s-7-4.35-9.5-8.5C.5 8.5 2.5 5 6 5c2 0 3.5 1.5 4 2.5.5-1 2-2.5 4-2.5 3.5 0 5.5 3.5 3.5 7.5C19 16.65 12 21 12 21z"/></svg>
                </div>
                <h3 class="font-display text-xl text-primary-deep">{{ __('Fidéliser et attirer les meilleurs talents') }}</h3>
                <p class="font-body text-sm text-primary-deep/80">{{ __('Facilitez le retour serein de congé maternité et paternité. Un mode de garde de qualité est un argument décisif pour les jeunes parents.') }}</p>
                <div class="mt-1 flex items-center gap-2 text-primary font-body text-sm font-bold">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ __('Reprises d\'activité facilitées') }}</span>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-7 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all flex flex-col gap-3">
                <div class="w-14 h-14 rounded-2xl bg-peach flex items-center justify-center text-coral">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 7v5l3 3"/></svg>
                </div>
                <h3 class="font-display text-xl text-primary-deep">{{ __('Réduire l\'absentéisme et le stress') }}</h3>
                <p class="font-body text-sm text-primary-deep/80">{{ __('Fini les désistements de dernière minute. Des horaires étendus allègent la charge mentale des parents actifs.') }}</p>
                <div class="mt-1 flex items-center gap-2 text-coral font-body text-sm font-bold">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
                    <span>{{ __('Sérénité retrouvée') }}</span>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-7 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all flex flex-col gap-3">
                <div class="w-14 h-14 rounded-2xl bg-sky flex items-center justify-center text-primary-deep">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10h18"/><circle cx="16" cy="14.5" r="1" fill="currentColor"/></svg>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h3 class="font-display text-xl text-primary-deep">{{ __('Avantages fiscaux employeur') }}</h3>
                </div>
                <p class="font-body text-sm text-primary-deep/80">{{ __('Le Crédit d\'Impôt Famille (50 % des dépenses) et la déduction de l\'impôt sur les sociétés peuvent couvrir jusqu\'à 75 % du coût d\'un berceau, à confirmer avec votre expert-comptable.') }}</p>
                <div class="mt-1 flex items-center gap-2 text-primary-deep font-body text-sm font-bold">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 17l6-6 4 4 8-8M21 7v6"/></svg>
                    <span>{{ __('Simulation personnalisée sur demande') }}</span>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-7 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all flex flex-col gap-3">
                <div class="w-14 h-14 rounded-2xl bg-mint flex items-center justify-center text-primary">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
                </div>
                <h3 class="font-display text-xl text-primary-deep">{{ __('Politique RSE concrète et locale') }}</h3>
                <p class="font-body text-sm text-primary-deep/80">{{ __('Soutenez un lieu écoresponsable : repas faits maison et de saison, jouets durables en bois brut, empreinte environnementale réduite.') }}</p>
                <div class="mt-1 flex items-center gap-2 text-primary font-body text-sm font-bold">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
                    <span>{{ __('Une démarche valorisable') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 3 : ET POUR VOS SALARIÉS --}}
<section class="bg-cream py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <x-confetti shape="etoile" class="top-10 left-[8%] w-7 h-7 text-primary animate-float" />
    <x-confetti shape="coeur" class="bottom-10 right-[6%] w-6 h-6 text-coral animate-bounce-soft" />

    <div class="max-w-[1240px] mx-auto px-6 reveal relative z-10" :class="{ 'reveal-shown': shown }">
        <div class="text-center max-w-2xl mx-auto flex flex-col items-center">
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-mint/60 shadow-sm">{{ __('Bénéfices collaborateurs') }}</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">{{ __('Un cocon bienveillant pour la sérénité de vos équipes') }}</h2>
            <p class="font-body text-primary-deep/80 mt-2">{{ __('Ce que vous apportez concrètement au quotidien des jeunes parents.') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mt-12">
            @foreach ([
                ['titre' => __('Garde de proximité'), 'texte' => __('Au cœur d\'Alfortville, idéalement située pour fluidifier les trajets domicile-travail.'), 'tag' => __('Accès RER & Métro'), 'bg' => 'bg-mint', 'col' => 'text-primary'],
                ['titre' => __('Horaires :horaires', ['horaires' => $etab->horaires()]), 'texte' => __('Une belle amplitude du lundi au vendredi pour concilier réunions et vie de famille.'), 'tag' => __('10h d\'amplitude continue'), 'bg' => 'bg-peach', 'col' => 'text-coral'],
                ['titre' => __('Accueil sur-mesure'), 'texte' => __('Taux d\'encadrement élevé pour 12 enfants, plus 2 places d\'urgence, seulement.'), 'tag' => __('Micro-crèche intimiste'), 'bg' => 'bg-sky', 'col' => 'text-primary-deep'],
                ['titre' => __('Repas faits maison inclus'), 'texte' => __('Alimentation équilibrée et de saison, pensée pour chaque âge.'), 'tag' => __('Zéro perturbateurs'), 'bg' => 'bg-mint', 'col' => 'text-primary'],
            ] as $i => $tuile)
            <div class="bg-white rounded-3xl p-6 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all flex flex-col gap-3">
                <div class="w-12 h-12 rounded-2xl {{ $tuile['bg'] }} flex items-center justify-center {{ $tuile['col'] }}">
                    @if ($i === 0)
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-7.58 7-12A7 7 0 105 9c0 4.42 7 12 7 12z"/><circle cx="12" cy="9" r="2.3"/></svg>
                    @elseif ($i === 1)
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 7v5l3 3"/></svg>
                    @elseif ($i === 2)
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="6" r="3"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 21v-4a6 6 0 0112 0v4"/></svg>
                    @else
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
                    @endif
                </div>
                <h3 class="font-display text-lg text-primary-deep">{{ $tuile['titre'] }}</h3>
                <p class="font-body text-sm text-primary-deep/80">{{ $tuile['texte'] }}</p>
                <span class="mt-auto self-start px-3 py-1 rounded-full bg-cream font-body text-[11px] {{ $tuile['col'] }} font-bold">{{ $tuile['tag'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SECTION 4 : COMMENT ÇA MARCHE --}}
<section id="fonctionnement-b2b" class="bg-mint py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <x-confetti shape="squiggle" class="top-8 right-[10%] w-9 h-9 text-primary-deep animate-spin-slow" />
    <x-confetti shape="rond" class="bottom-8 left-[8%] w-5 h-5 bg-sun animate-bounce-soft" />

    <div class="max-w-[1240px] mx-auto px-6 reveal relative z-10" :class="{ 'reveal-shown': shown }">
        <div class="text-center max-w-2xl mx-auto flex flex-col items-center">
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-white shadow-sm">{{ __('Parcours simple & fluide') }}</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">{{ __('Comment réserver un ou plusieurs berceaux ?') }}</h2>
            <p class="font-body text-primary-deep/80 mt-2">{{ __('Un accompagnement personnalisé, de la prise de contact jusqu\'à l\'accueil des enfants.') }}</p>
        </div>

        <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 relative">
            <div class="hidden lg:block absolute top-7 left-[12%] right-[12%] h-0.5 border-b-2 border-dashed border-primary/30 -z-0"></div>
            @foreach ([
                ['n' => 1, 'titre' => __('Échange sur vos besoins'), 'texte' => __('Nombre de collaborateurs concernés, plannings de reprise et objectifs RH prioritaires.')],
                ['n' => 2, 'titre' => __('Devis personnalisé'), 'texte' => __('Coût, déductions fiscales estimées et calendrier d\'entrée.')],
                ['n' => 3, 'titre' => __('Convention entreprise'), 'texte' => __('Signature du partenariat réservant vos berceaux, avec priorité pour vos salariés.')],
                ['n' => 4, 'titre' => __('Accueil des tout-petits'), 'texte' => __('Pré-inscription simplifiée et période de familiarisation douce avec notre équipe.')],
            ] as $etape)
            <div class="relative z-10 flex flex-col items-center text-center bg-white rounded-3xl p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">
                <div class="w-14 h-14 rounded-full bg-coral text-white font-display text-xl flex items-center justify-center shadow-md mb-3 ring-8 ring-mint">{{ $etape['n'] }}</div>
                <h3 class="font-display text-lg text-primary-deep mb-1">{{ $etape['titre'] }}</h3>
                <p class="font-body text-sm text-primary-deep/80">{{ $etape['texte'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <x-hill-divider color="cream" />
</section>

{{-- SECTION 5 : BANDE DE CONFIANCE --}}
<section class="bg-primary-deep text-white py-20 relative overflow-hidden">
    <x-confetti shape="etoile" class="top-10 left-[10%] w-8 h-8 text-sun animate-wiggle" />
    <x-confetti shape="fleur" class="bottom-14 right-[12%] w-6 h-6 text-mint animate-float" />
    <x-confetti shape="rond" class="top-1/2 right-[3%] w-4 h-4 bg-white/60 animate-bounce-soft" />

    <div class="max-w-[1240px] mx-auto px-6 relative z-10">
        <div class="text-center max-w-2xl mx-auto flex flex-col items-center">
            <span class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-white/10 font-body text-xs uppercase tracking-widest font-extrabold backdrop-blur-sm">{{ __('Gage de sérénité') }}</span>
            <h2 class="font-display text-3xl md:text-4xl mt-3">{{ __('Une micro-crèche à taille humaine, engagée pour l\'avenir') }}</h2>
            <p class="font-body text-white/85 mt-2">{{ __('Exigence professionnelle, bien-être des tout-petits et démarche écoresponsable, sans compromis.') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            <div class="bg-white/10 backdrop-blur-md rounded-3xl p-8 flex flex-col items-center text-center">
                <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center text-sun mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="8" cy="9" r="3"/><circle cx="16" cy="9" r="3"/><path stroke-linecap="round" d="M2 20c0-3 3-5 6-5s6 2 6 5M10 20c0-3 3-5 6-5s6 2 6 5"/></svg>
                </div>
                <h3 class="font-display text-xl mb-1">{{ __('12 berceaux exclusifs') }}</h3>
                <p class="font-body text-sm text-white/75">{{ __('Une structure intime garantissant une prise en charge sur-mesure et un lien chaleureux avec chaque famille.') }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur-md rounded-3xl p-8 flex flex-col items-center text-center">
                <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center text-mint mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
                </div>
                <h3 class="font-display text-xl mb-1">{{ __('Engagement écoresponsable') }}</h3>
                <p class="font-body text-sm text-white/75">{{ __('Mobilier en bois certifié, peintures sans COV, repas faits maison et produits d\'hygiène naturels.') }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur-md rounded-3xl p-8 flex flex-col items-center text-center">
                <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center text-sun mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><circle cx="5" cy="6" r="2"/><circle cx="19" cy="6" r="2"/><circle cx="5" cy="18" r="2"/><circle cx="19" cy="18" r="2"/><path stroke-linecap="round" d="M9.5 10.5L6.5 7M14.5 10.5l3-3.5M9.5 13.5L6.5 17M14.5 13.5l3 3.5"/></svg>
                </div>
                <h3 class="font-display text-xl mb-1">{{ __('Réseau :gestionnaire', ['gestionnaire' => $etab->gestionnaire()]) }}</h3>
                <p class="font-body text-sm text-white/75">{{ __('La force d\'un réseau assurant une gestion rigoureuse, des protocoles sanitaires stricts et une gouvernance pérenne.') }}</p>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 6 : DEMANDER UN DEVIS --}}
<section id="devis-form" class="bg-cream py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <x-confetti shape="etoile" class="top-10 right-[6%] w-7 h-7 text-sun animate-float" />

    <div class="max-w-[1240px] mx-auto px-6 reveal relative z-10" :class="{ 'reveal-shown': shown }">
        <div class="text-center max-w-2xl mx-auto flex flex-col items-center">
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-mint/60 shadow-sm">{{ __('Contact entreprises & partenariats') }}</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">{{ __('Simulez votre projet de berceaux d\'entreprise') }}</h2>
            <p class="font-body text-primary-deep/80 mt-2">{{ __('Remplissez ce formulaire confidentiel pour obtenir une étude et un devis personnalisé.') }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start mt-12">
            {{-- Formulaire --}}
            <div class="lg:col-span-8 bg-white p-6 sm:p-8 rounded-[32px] shadow-lg">
                @if (session('success'))
                <div class="flex flex-col items-center text-center gap-4 py-10">
                    <div class="w-16 h-16 rounded-full bg-mint flex items-center justify-center text-primary animate-bounce-soft">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h3 class="font-display text-2xl text-primary-deep">{{ __('Merci, votre demande est bien envoyée !') }}</h3>
                    <p class="font-body text-primary-deep/80 max-w-sm">{{ session('success') }}</p>
                </div>
                @else
                <x-formulaire.resume-erreurs :champs="['company_name' => 'company_name', 'sector' => 'sector', 'employees_count' => 'employees_count', 'berceaux_count' => 'berceaux_count', 'contact_name' => 'contact_name', 'contact_role' => 'contact_role', 'contact_email' => 'contact_email', 'contact_tel' => 'contact_tel', 'message' => 'message', 'rgpd_consent' => 'devis-rgpd']" />
                <form method="POST" action="{{ route_langue('berceaux-entreprise.store') }}" class="flex flex-col gap-4">
                    @csrf
                    <x-anti-spam />
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label for="company_name" class="font-body text-sm font-bold text-primary-deep">{{ __('Nom de l\'entreprise *') }}</label>
                            <input id="company_name" name="company_name" value="{{ old('company_name') }}" type="text" required placeholder="{{ __('ex. Atelier Val-de-Marne SAS') }}" class="h-[52px] px-5 rounded-full bg-cream text-primary-deep placeholder:text-primary-deep/40 focus:outline-none focus:ring-2 focus:ring-primary transition-all @error('company_name') ring-2 ring-coral @enderror" @error('company_name') aria-invalid="true" aria-describedby="company_name-erreur" @enderror>
                            <x-formulaire.erreur champ="company_name" id="company_name" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="sector" class="font-body text-sm font-bold text-primary-deep">{{ __('Secteur d\'activité') }}</label>
                            <input id="sector" name="sector" value="{{ old('sector') }}" type="text" placeholder="{{ __('ex. Conseil, Santé, Industrie...') }}" class="h-[52px] px-5 rounded-full bg-cream text-primary-deep placeholder:text-primary-deep/40 focus:outline-none focus:ring-2 focus:ring-primary transition-all @error('sector') ring-2 ring-coral @enderror" @error('sector') aria-invalid="true" aria-describedby="sector-erreur" @enderror>
                            <x-formulaire.erreur champ="sector" id="sector" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label for="employees_count" class="font-body text-sm font-bold text-primary-deep">{{ __('Nombre de salariés') }}</label>
                            <div class="relative">
                                <select id="employees_count" name="employees_count" class="w-full h-[52px] pl-5 pr-14 rounded-full bg-cream text-primary-deep focus:outline-none focus:ring-2 focus:ring-primary transition-all appearance-none @error('employees_count') ring-2 ring-coral @enderror" @error('employees_count') aria-invalid="true" aria-describedby="employees_count-erreur" @enderror>
                                    @foreach (['Moins de 20 salariés', '20 à 50 salariés', '50 à 250 salariés', 'Plus de 250 salariés'] as $tranche)
                                    <option value="{{ $tranche }}" {{ old('employees_count', '50 à 250 salariés') === $tranche ? 'selected' : '' }}>{{ __($tranche) }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-mint flex items-center justify-center text-primary pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                            <x-formulaire.erreur champ="employees_count" id="employees_count" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="berceaux_count" class="font-body text-sm font-bold text-primary-deep">{{ __('Berceaux souhaités') }}</label>
                            <div class="relative">
                                <select id="berceaux_count" name="berceaux_count" class="w-full h-[52px] pl-5 pr-14 rounded-full bg-cream text-primary-deep focus:outline-none focus:ring-2 focus:ring-primary transition-all appearance-none @error('berceaux_count') ring-2 ring-coral @enderror" @error('berceaux_count') aria-invalid="true" aria-describedby="berceaux_count-erreur" @enderror>
                                    @foreach (['1 berceau réservé', '2 à 3 berceaux', '4 berceaux ou plus', 'Étude exploratoire'] as $tranche)
                                    <option value="{{ $tranche }}" {{ old('berceaux_count', '2 à 3 berceaux') === $tranche ? 'selected' : '' }}>{{ __($tranche) }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-mint flex items-center justify-center text-primary pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                            <x-formulaire.erreur champ="berceaux_count" id="berceaux_count" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label for="contact_name" class="font-body text-sm font-bold text-primary-deep">{{ __('Nom & prénom du contact *') }}</label>
                            <input id="contact_name" name="contact_name" value="{{ old('contact_name') }}" type="text" required placeholder="{{ __('Sophie Martin') }}" class="h-[52px] px-5 rounded-full bg-cream text-primary-deep placeholder:text-primary-deep/40 focus:outline-none focus:ring-2 focus:ring-primary transition-all @error('contact_name') ring-2 ring-coral @enderror" @error('contact_name') aria-invalid="true" aria-describedby="contact_name-erreur" @enderror>
                            <x-formulaire.erreur champ="contact_name" id="contact_name" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="contact_role" class="font-body text-sm font-bold text-primary-deep">{{ __('Fonction / Rôle RH') }}</label>
                            <input id="contact_role" name="contact_role" value="{{ old('contact_role') }}" type="text" placeholder="{{ __('DRH, Dirigeant, Responsable RSE...') }}" class="h-[52px] px-5 rounded-full bg-cream text-primary-deep placeholder:text-primary-deep/40 focus:outline-none focus:ring-2 focus:ring-primary transition-all @error('contact_role') ring-2 ring-coral @enderror" @error('contact_role') aria-invalid="true" aria-describedby="contact_role-erreur" @enderror>
                            <x-formulaire.erreur champ="contact_role" id="contact_role" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label for="contact_email" class="font-body text-sm font-bold text-primary-deep">{{ __('Email professionnel *') }}</label>
                            <input id="contact_email" name="contact_email" value="{{ old('contact_email') }}" type="email" required placeholder="{{ __('smartin@entreprise.fr') }}" class="h-[52px] px-5 rounded-full bg-cream text-primary-deep placeholder:text-primary-deep/40 focus:outline-none focus:ring-2 focus:ring-primary transition-all @error('contact_email') ring-2 ring-coral @enderror" @error('contact_email') aria-invalid="true" aria-describedby="contact_email-erreur" @enderror>
                            <x-formulaire.erreur champ="contact_email" id="contact_email" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="contact_tel" class="font-body text-sm font-bold text-primary-deep">{{ __('Téléphone direct *') }}</label>
                            <input id="contact_tel" name="contact_tel" value="{{ old('contact_tel') }}" type="tel" required placeholder="06 12 34 56 78" class="h-[52px] px-5 rounded-full bg-cream text-primary-deep placeholder:text-primary-deep/40 focus:outline-none focus:ring-2 focus:ring-primary transition-all @error('contact_tel') ring-2 ring-coral @enderror" @error('contact_tel') aria-invalid="true" aria-describedby="contact_tel-erreur" @enderror>
                            <x-formulaire.erreur champ="contact_tel" id="contact_tel" />
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label for="message" class="font-body text-sm font-bold text-primary-deep">{{ __('Précisions sur vos attentes ou calendrier souhaité') }}</label>
                        <textarea id="message" name="message" rows="3" placeholder="{{ __('Dates cibles de rentrée, questions sur le crédit d\'impôt...') }}" class="p-4 rounded-2xl bg-cream text-primary-deep placeholder:text-primary-deep/40 focus:outline-none focus:ring-2 focus:ring-primary transition-all resize-none @error('message') ring-2 ring-coral @enderror" @error('message') aria-invalid="true" aria-describedby="message-erreur" @enderror>{{ old('message') }}</textarea>
                        <x-formulaire.erreur champ="message" id="message" />
                    </div>
                    <label class="flex items-start gap-3 mt-1 cursor-pointer">
                        <input id="devis-rgpd" type="checkbox" name="rgpd_consent" value="1" {{ old('rgpd_consent') ? 'checked' : '' }} required class="mt-1 h-5 w-5 rounded border-primary/40 text-primary focus:ring-primary cursor-pointer accent-primary @error('rgpd_consent') outline outline-2 outline-offset-2 outline-coral @enderror" @error('rgpd_consent') aria-invalid="true" aria-describedby="devis-rgpd-erreur" @enderror>
                        <span class="font-body text-sm text-primary-deep/80 leading-relaxed">{{ __('J\'accepte que mes données soient utilisées pour traiter ma demande (politique de confidentialité).') }}</span>
                    </label>
                    <x-formulaire.erreur champ="rgpd_consent" id="devis-rgpd" />
                    <button type="submit" class="mt-2 w-full sm:w-auto self-start h-[52px] px-8 rounded-full bg-coral text-white font-body font-bold text-sm flex items-center justify-center gap-2 shadow-lg hover:-translate-y-0.5 hover:opacity-90 transition-all">
                        <span>{{ __('Envoyer ma demande de devis') }}</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </button>
                </form>
                @endif
            </div>

            {{-- Carte contact --}}
            <div class="lg:col-span-4 flex flex-col gap-6">
                <div class="bg-peach/60 p-7 rounded-[32px] shadow-sm flex flex-col gap-4 border border-peach">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 rounded-full bg-coral text-white flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="3"/><path stroke-linecap="round" d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6"/><path stroke-linecap="round" d="M19 8c0-1-.5-2-1.5-2"/></svg>
                        </div>
                        <span class="font-body text-xs uppercase tracking-widest text-coral font-extrabold">{{ __('Interlocuteur dédié') }}</span>
                    </div>
                    <h3 class="font-display text-xl text-primary-deep">{{ __('Nous contacter') }}</h3>
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white text-coral font-body text-xs font-bold shadow-sm w-fit">
                        {{ __('Réponse sous 48h ouvrées') }}
                    </span>
                    <p class="font-body text-sm text-primary-deep/80">{{ __('Une question sur vos déductions fiscales ou les places disponibles ? Notre direction est directement joignable :') }}</p>
                    <div class="flex flex-col gap-3 pt-1 font-body text-sm">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-primary shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-7.58 7-12A7 7 0 105 9c0 4.42 7 12 7 12z"/><circle cx="12" cy="9" r="2.3"/></svg>
                            <div class="flex flex-col"><span class="font-bold text-primary-deep">{{ __('Adresse') }}</span><span class="text-primary-deep/80">{{ $etab->adresse() }}</span></div>
                        </div>
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-primary shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2l2 5-2 1a11 11 0 006 6l1-2 5 2v2a2 2 0 01-2 2h-1C9 19 3 13 3 6V5z"/></svg>
                            <div class="flex flex-col"><span class="font-bold text-primary-deep">{{ __('Téléphone direct') }}</span><a href="tel:{{ $etab->telephoneLien() }}" class="text-primary font-semibold hover:underline">{{ $etab->telephone() }}</a></div>
                        </div>
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-primary shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
                            <div class="flex flex-col"><span class="font-bold text-primary-deep">{{ __('Courriel') }}</span><a href="mailto:{{ $etab->email() }}" class="text-primary font-semibold hover:underline break-all">{{ $etab->email() }}</a></div>
                        </div>
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-primary shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 7v5l3 3"/></svg>
                            <div class="flex flex-col"><span class="font-bold text-primary-deep">{{ __('Horaires d\'accueil') }}</span><span class="text-primary-deep/80">{{ __('Lundi – vendredi, :horaires', ['horaires' => $etab->horaires()]) }}</span></div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[28px] shadow-sm flex items-center gap-4 border border-mint">
                    <div class="w-12 h-12 rounded-full bg-mint text-primary flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="8" cy="9" r="3"/><circle cx="16" cy="9" r="3"/><path stroke-linecap="round" d="M2 20c0-3 3-5 6-5s6 2 6 5M10 20c0-3 3-5 6-5s6 2 6 5"/></svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-display text-base text-primary-deep">{{ __('Convention employeur') }}</span>
                        <span class="font-body text-xs text-primary-deep/80 leading-tight">{{ __('Mise en place simple, sans complexité administrative pour vos équipes RH.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 7 : FAQ --}}
<section class="bg-mint py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <x-confetti shape="etoile" class="top-14 left-[8%] w-7 h-7 text-sun animate-wiggle" />
    <x-confetti shape="coeur" class="bottom-16 right-[8%] w-6 h-6 text-coral animate-float" />

    <div class="max-w-[860px] mx-auto px-6 reveal relative z-10" :class="{ 'reveal-shown': shown }">
        <div class="text-center max-w-2xl mx-auto flex flex-col items-center">
            <div class="w-14 h-14 rounded-full bg-white shadow-md flex items-center justify-center text-coral animate-float-slow">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.5 9a2.5 2.5 0 015 0c0 1.5-1.5 2-2.5 3"/><circle cx="12" cy="16.5" r="0.6" fill="currentColor"/><circle cx="12" cy="12" r="9"/></svg>
            </div>
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-white shadow-sm mt-4">{{ __('Foire aux questions') }}</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">{{ __('Questions fréquentes') }}</h2>
            <p class="font-body text-primary-deep/80 mt-2">{{ __('Tout ce qu\'il faut savoir sur la mise en place de berceaux d\'entreprise.') }}</p>
        </div>

        <div class="mt-12 flex flex-col gap-4" x-data="{ open: 1 }">
            @foreach ([
                1 => ['q' => __('Comment fonctionne la réservation de berceaux ?'), 'r' => __('L\'entreprise signe une convention de réservation avec Le Jardin de Régina pour un ou plusieurs berceaux. Ces places sont alors réservées en priorité à vos collaborateurs parents, sans passer par les listes d\'attente classiques.')],
                2 => ['q' => __('Quels sont les avantages pour mon entreprise ?'), 'r' => __('Un vecteur d\'attractivité et de fidélisation lors des retours de congés parentaux, avec moins de stress et d\'absentéisme pour vos salariés. Sur le plan financier, le dispositif peut être optimisé via le Crédit d\'Impôt Famille et la déductibilité de l\'IS, taux à confirmer avec votre expert-comptable.')],
                3 => ['q' => __('Combien de berceaux puis-je réserver ?'), 'r' => __('Pas de minimum imposé : de un seul berceau pour un collaborateur clé, à plusieurs places pour accompagner vos équipes. Notre structure intime (12 places + 2 d\'urgence) reste flexible, pour les TPE comme pour les grands groupes.')],
                4 => ['q' => __('Comment sont fixés les tarifs ?'), 'r' => __('Le montant dépend du nombre de berceaux réservés et de la durée d\'engagement. Nous réalisons une simulation personnalisée et sans engagement, intégrant le coût brut et les déductions fiscales estimées.')],
            ] as $id => $item)
            <div class="rounded-2xl shadow-sm hover:shadow-lg transition-all overflow-hidden border-2"
                 :class="open === {{ $id }} ? 'bg-white border-coral/30' : 'bg-white border-transparent'">
                <button @click="open = (open === {{ $id }} ? null : {{ $id }})" class="w-full p-5 flex items-center justify-between text-left">
                    <span class="font-display text-lg text-primary-deep">{{ $item['q'] }}</span>
                    <svg class="w-5 h-5 text-primary shrink-0 transition-transform duration-200" :class="open === {{ $id }} ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open === {{ $id }}" x-collapse class="px-5 pb-5 font-body text-sm text-primary-deep/80 leading-relaxed">
                    {{ $item['r'] }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SECTION 8 : CTA FINAL --}}
<section class="bg-cream py-16">
    <div class="max-w-[1240px] mx-auto px-6">
        <div class="bg-coral rounded-[40px] p-10 lg:p-14 text-white relative overflow-hidden shadow-2xl flex flex-col lg:flex-row items-center justify-between gap-8">
            <div class="absolute -right-16 -bottom-16 w-80 h-80 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
            <x-confetti shape="etoile" class="top-6 left-[20%] w-7 h-7 text-white/70 animate-wiggle" />

            <div class="flex flex-col items-start gap-3 max-w-xl relative z-10">
                <span class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-primary-deep/25 font-body text-xs uppercase tracking-widest font-extrabold">{{ __('Engagement & sérénité RH') }}</span>
                <h2 class="font-display text-3xl md:text-4xl leading-tight">{{ __('Prêt à accompagner vos collaborateurs parents ?') }}</h2>
                <p class="font-body text-white/90">{{ __('Nos conseillers sont à votre écoute pour concevoir un accueil sur-mesure pour votre entreprise.') }}</p>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-4 relative z-10 shrink-0">
                <a href="#devis-form" class="h-[54px] px-8 rounded-full bg-white text-coral font-body font-bold text-sm flex items-center gap-2 shadow-md hover:-translate-y-0.5 transition-all">
                    <span>{{ __('Demander un devis') }}</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="tel:{{ $etab->telephoneLien() }}" class="h-[54px] px-6 rounded-full bg-primary-deep/25 text-white font-body font-bold text-sm flex items-center gap-2 hover:bg-primary-deep/40 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2l2 5-2 1a11 11 0 006 6l1-2 5 2v2a2 2 0 01-2 2h-1C9 19 3 13 3 6V5z"/></svg>
                    <span>{{ $etab->telephone() }}</span>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
