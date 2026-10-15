@extends('layouts.app')

@section('title', "Horaires et fonctionnement — Le Jardin de Régina")
@section('description', "Ouvert du lundi au vendredi {$etab->horairesTexte()} : accueil régulier, occasionnel ou d'urgence, journée type, repas faits maison et adaptation en douceur.")

  

@section('content')

{{-- SECTION 1 : HERO COMPACT --}}
<section class="relative bg-cream pt-10 pb-16 overflow-hidden">
    <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-peach blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute top-1/2 -left-20 w-80 h-80 rounded-full bg-mint blur-3xl pointer-events-none -z-10"></div>
    <x-confetti shape="etoile" class="top-16 left-[8%] w-7 h-7 text-sun animate-float" />
    <x-confetti shape="coeur" class="bottom-14 right-[10%] w-6 h-6 text-coral animate-bounce-soft" />

    <div class="max-w-[1240px] mx-auto px-6">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-10">
            <div class="flex-1 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-mint/60 text-primary mb-4">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 7v5l3 3"/></svg>
                    <span class="font-body text-xs uppercase tracking-widest font-extrabold">Organisation au quotidien</span>
                </div>
                <h1 class="font-display text-4xl md:text-5xl text-primary-deep mb-2">Comment fonctionne la crèche ?</h1>
                <p class="font-display text-2xl text-coral mb-4">Horaires, types d'accueil et admission, en toute clarté.</p>
                <p class="font-body text-lg text-primary-deep/80 mb-6 leading-relaxed">
                    Conçue comme une extension bienveillante de la maison, notre micro-crèche accueille vos tout-petits au sein d'un cocon végétalisé pensé pour respecter leurs rythmes physiologiques.
                </p>
                <div class="flex flex-wrap items-center gap-3 mb-8">
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white shadow-sm font-body text-sm font-semibold text-primary-deep">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="8" cy="9" r="3"/><circle cx="16" cy="9" r="3"/><path stroke-linecap="round" d="M2 20c0-3 3-5 6-5s6 2 6 5M10 20c0-3 3-5 6-5s6 2 6 5"/></svg>
                        12 places + 2 urgences
                    </span>
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white shadow-sm font-body text-sm font-semibold text-primary-deep">
                        <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21s-7-4.35-9.5-8.5C.5 8.5 2.5 5 6 5c2 0 3.5 1.5 4 2.5.5-1 2-2.5 4-2.5 3.5 0 5.5 3.5 3.5 7.5C19 16.65 12 21 12 21z"/></svg>
                        Une équipe bienveillante
                    </span>
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white shadow-sm font-body text-sm font-semibold text-primary-deep">
                        <svg class="w-4 h-4 text-coral" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-7.58 7-12A7 7 0 105 9c0 4.42 7 12 7 12z"/><circle cx="12" cy="9" r="2.3"/></svg>
                        {{ $etab->ville() }} ({{ $etab->codePostal() }})
                    </span>
                </div>
                <div class="flex flex-wrap items-center gap-4">
                    <a href="/pre-inscription" class="h-[52px] px-8 rounded-full bg-coral text-white font-body font-bold text-sm flex items-center gap-2 shadow-lg hover:-translate-y-0.5 hover:opacity-90 transition-all">
                        Pré-inscrire mon enfant
                    </a>
                    <a href="#journee-type" class="h-[52px] px-8 rounded-full bg-transparent text-primary font-body font-bold text-sm flex items-center gap-2 hover:bg-mint/40 transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m0 0l-6-6m6 6l6-6"/></svg>
                        Découvrir la journée type
                    </a>
                </div>
            </div>

            {{-- Cachet décoratif --}}
            <div class="relative w-full max-w-sm lg:max-w-md aspect-square flex items-center justify-center shrink-0">
                <div class="absolute inset-0 bg-mint/50 rounded-full blur-2xl"></div>
                <div class="relative w-full h-full rounded-[40px] bg-white p-8 shadow-xl flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold">Harmonie &amp; nature</span>
                        <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
                    </div>
                    <div class="space-y-3 my-auto">
                        <div class="flex items-baseline gap-2">
                            <span class="font-display text-4xl text-primary">12</span>
                            <span class="font-display text-xl text-primary-deep/80">places d'éveil</span>
                        </div>
                        <p class="font-body text-sm text-primary-deep/80">Pour préserver le rythme individuel et garantir une attention continue dans un environnement calme.</p>
                    </div>
                    <div class="pt-4 flex items-center justify-between font-body text-sm text-primary">
                        <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-coral inline-block"></span> Places 2026/2027</span>
                        <span class="font-bold text-coral">Inscriptions ouvertes</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-hill-divider color="mint" />
</section>

{{-- SECTION 2 : HORAIRES ET ORGANISATION --}}
<section class="bg-mint py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <x-confetti shape="rond" class="top-14 right-[8%] w-5 h-5 bg-white animate-float-slow" />

    <div class="max-w-[1240px] mx-auto px-6 reveal relative z-10" :class="{ 'reveal-shown': shown }">
        <div class="max-w-2xl mb-12">
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-white shadow-sm">Cadre temporel</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">Horaires et rythme de présence</h2>
            <p class="font-body text-primary-deep/80 mt-2">Une amplitude étendue pensée pour concilier sérénité familiale et contraintes professionnelles.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-6">
            @foreach ([
                ['icon' => 'calendar', 'eyebrow' => 'Du lundi au vendredi', 'titre' => $etab->horaires(), 'texte' => "Amplitude continue de 10 heures par jour. Fermé les week-ends et jours fériés pour permettre le repos de l'équipe et des enfants.", 'span' => false],
                ['icon' => 'clock', 'eyebrow' => 'Formules de garde', 'titre' => 'Temps plein ou partiel', 'texte' => "Flexibilité d'accueil de 1 à 5 jours par semaine, adaptée selon vos semaines de travail et besoins spécifiques.", 'span' => false],
                ['icon' => 'login', 'eyebrow' => "Fenêtres d'arrivée", 'titre' => "Jusqu'à 9h30", 'texte' => "Arrivées le matin jusqu'à 9h30, et entre 12h00 et 13h00 l'après-midi, pour garantir le calme des ateliers et des repas.", 'span' => false],
                ['icon' => 'logout', 'eyebrow' => 'Créneaux de départ', 'titre' => 'Dès 13h00 & dès 16h00', 'texte' => "Départs possibles dès 13h00 après le déjeuner, ou dès 16h00 après le goûter et le réveil.", 'span' => false],
            ] as $carte)
            <div class="bg-white rounded-3xl p-6 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all flex flex-col gap-2">
                <div class="w-12 h-12 rounded-2xl bg-mint flex items-center justify-center text-primary mb-2">
                    @if ($carte['icon'] === 'calendar')
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg>
                    @elseif ($carte['icon'] === 'clock')
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 7v5l3 3"/></svg>
                    @elseif ($carte['icon'] === 'login')
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l4-4m0 0l-4-4m4 4H3m8-9h6a2 2 0 012 2v14a2 2 0 01-2 2h-6"/></svg>
                    @else
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 8l-4 4m0 0l4 4m-4-4h11M14 5V3a2 2 0 00-2-2H6a2 2 0 00-2 2v14a2 2 0 002 2h6a2 2 0 002-2v-2"/></svg>
                    @endif
                </div>
                <span class="font-body text-xs uppercase tracking-wider text-primary-deep/75">{{ $carte['eyebrow'] }}</span>
                <h3 class="font-display text-2xl text-primary">{{ $carte['titre'] }}</h3>
                <p class="font-body text-sm text-primary-deep/80">{{ $carte['texte'] }}</p>
            </div>
            @endforeach

            <div class="md:col-span-2 lg:col-span-2 bg-white rounded-3xl p-6 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all flex flex-col gap-2">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-1">
                    <div class="w-12 h-12 rounded-2xl bg-mint flex items-center justify-center text-primary">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 8v4l2.5 2.5"/></svg>
                    </div>
                    <span class="inline-flex items-center gap-1 font-body text-xs text-coral uppercase bg-peach/60 px-3 py-1 rounded-full font-bold">Recommandation pédagogique</span>
                </div>
                <span class="font-body text-xs uppercase tracking-wider text-primary-deep/75">Stabilité &amp; repères</span>
                <h3 class="font-display text-2xl text-primary">8 heures consécutives recommandées</h3>
                <p class="font-body text-sm text-primary-deep/80">Pour permettre à l'enfant de vivre les transitions essentielles (jeu, calme, atelier sensoriel, sommeil) sans sentiment d'urgence.</p>
            </div>
        </div>

        <div class="rounded-3xl bg-peach/60 p-6 flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-coral flex items-center justify-center text-white shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.5a8.38 8.38 0 01-4.8 7.6 8.5 8.5 0 01-9.3-1.8L3 20l1.9-5.7a8.5 8.5 0 018.4-11 8.38 8.38 0 017.7 6.7z"/></svg>
            </div>
            <div>
                <h4 class="font-display text-lg text-primary-deep mb-1">Bon à savoir pour les transmissions</h4>
                <p class="font-body text-primary-deep/80">
                    Afin de préserver la qualité de nos échanges, nous invitons chaleureusement les parents à arriver <strong>10 minutes avant l'heure prévue</strong>, tant le matin que pour le compte-rendu du soir.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 3 : TROIS TYPES D'ACCUEIL --}}
<section class="bg-cream py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <x-confetti shape="fleur" class="top-10 left-[6%] w-8 h-8 text-coral animate-wiggle" />
    <x-confetti shape="etoile" class="bottom-14 right-[6%] w-7 h-7 text-sun animate-float" />

    <div class="max-w-[1240px] mx-auto px-6 reveal relative z-10" :class="{ 'reveal-shown': shown }">
        <div class="text-center max-w-2xl mx-auto flex flex-col items-center">
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-mint/60 shadow-sm">Flexibilité &amp; sérénité</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">Trois formules adaptées à vos réalités</h2>
            <p class="font-body text-primary-deep/80 mt-2">Chaque famille a son propre équilibre de vie.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-12">
            @foreach ([
                [
                    'badge' => 'Formule principale', 'titre' => 'Accueil régulier', 'bg' => 'bg-mint',
                    'texte' => "Contrat mensualisé avec planning et horaires définis à l'avance, de 1 à 5 jours par semaine.",
                    'benefices' => ["Berceau garanti à l'année sans interruption", 'Mensualités lissées pour une gestion budgétaire fluide', "Période d'adaptation progressive et personnalisée"],
                    'ideal' => 'Les parents en reprise d\'activité professionnelle ou en recherche d\'une solution pérenne.',
                ],
                [
                    'badge' => 'Flexibilité & halte-garderie', 'titre' => 'Accueil occasionnel', 'bg' => 'bg-sky',
                    'texte' => 'Accueil ponctuel selon les disponibilités réelles de berceaux, réservable à l\'avance.',
                    'benefices' => ["Liberté totale d'utilisation sans engagement mensuel fixe", 'Idéal pour les temps partiels variables et le télétravail', "Intégration douce de l'enfant avant l'entrée à l'école"],
                    'ideal' => 'Besoins ponctuels de garde, rendez-vous médicaux, ou répit parental.',
                ],
                [
                    'badge' => 'Solidarité & réactivité', 'titre' => "Accueil d'urgence", 'bg' => 'bg-peach',
                    'texte' => "2 places réservées aux imprévus de la vie. Contrat temporaire d'un mois renouvelable.",
                    'benefices' => ['Prise en charge express en moins de 48 heures ouvrées', 'Sécurise l\'emploi et évite les ruptures professionnelles', 'Accompagnement administratif réactif et simplifié'],
                    'ideal' => 'Assistante maternelle souffrante, hospitalisation d\'un proche, embauche inattendue.',
                ],
            ] as $formule)
            <div class="bg-white rounded-3xl p-7 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 {{ $formule['bg'] }} rounded-bl-full -z-0"></div>
                <div class="relative z-10">
                    <span class="inline-flex items-center gap-1 font-body text-xs text-primary-deep uppercase bg-cream px-3 py-1 rounded-full font-bold mb-4">{{ $formule['badge'] }}</span>
                    <h3 class="font-display text-2xl text-primary-deep mb-2">{{ $formule['titre'] }}</h3>
                    <p class="font-body text-primary-deep/80 mb-4">{{ $formule['texte'] }}</p>
                    <h4 class="font-display text-lg text-primary mb-2">Les bénéfices :</h4>
                    <ul class="space-y-2 font-body text-sm text-primary-deep/80 mb-4">
                        @foreach ($formule['benefices'] as $b)
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-primary shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ $b }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="relative z-10 pt-3 bg-cream rounded-2xl p-4">
                    <span class="font-body text-xs uppercase tracking-wider text-primary-deep/75 block mb-1">Idéal pour</span>
                    <p class="font-body text-sm text-primary-deep font-medium">{{ $formule['ideal'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SECTION 4 : UNE JOURNÉE TYPE --}}
<section id="journee-type" class="bg-mint py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <x-confetti shape="squiggle" class="top-10 right-[8%] w-9 h-9 text-primary-deep animate-spin-slow" />
    <x-confetti shape="rond" class="bottom-10 left-[6%] w-5 h-5 bg-sun animate-bounce-soft" />

    <div class="max-w-[1240px] mx-auto px-6 reveal relative z-10" :class="{ 'reveal-shown': shown }">
        <div class="text-center max-w-2xl mx-auto flex flex-col items-center">
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-white shadow-sm">Harmonie sensorielle</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">Une journée type au Jardin de Régina</h2>
            <p class="font-body text-primary-deep/80 mt-2">Au rythme naturel de l'enfant, entre découvertes, repas savoureux et repos réparateur.</p>
        </div>

        <div class="relative space-y-10 mt-14">
            <div class="hidden lg:block absolute left-1/2 top-6 bottom-6 w-0.5 border-l-2 border-dashed border-primary/30 -translate-x-1/2"></div>

            {{-- Étape 1 --}}
            <div class="relative flex flex-col lg:flex-row items-center gap-8">
                <div class="lg:w-1/2 lg:text-right order-2 lg:order-1">
                    <span class="inline-block px-3 py-1 rounded-full bg-white text-primary font-body text-xs uppercase font-bold mb-2">[8h30 – 9h30]</span>
                    <h3 class="font-display text-xl text-primary-deep mb-1">Accueil &amp; transmissions bienveillantes</h3>
                    <p class="font-body text-sm text-primary-deep/80">Arrivée échelonnée, échange individuel avec chaque parent sur la nuit passée, et rituels d'entrée rassurants dans un espace baigné de lumière douce.</p>
                </div>
                <div class="order-1 lg:order-2 w-12 h-12 rounded-full bg-coral text-white flex items-center justify-center font-display text-lg shadow-lg shrink-0 z-10">1</div>
                <div class="lg:w-1/2 order-3">
                    <div class="bg-white p-4 rounded-2xl shadow-sm flex items-center gap-4">
                        <svg class="w-9 h-9 text-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 4v16M3 20V6a2 2 0 012-2h8v16H5a2 2 0 01-2-2z"/></svg>
                        <p class="font-body text-sm text-primary-deep/80">Un rituel d'accueil personnalisé pour respecter la séparation et sécuriser l'enfant.</p>
                    </div>
                </div>
            </div>

            {{-- Étape 2 --}}
            <div class="relative flex flex-col lg:flex-row items-center gap-8">
                <div class="lg:w-1/2 order-3 lg:order-1">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="overflow-hidden rounded-2xl shadow-sm">
                            <img loading="lazy" decoding="async" src="{{ asset('images/salle-de-jeux.webp') }}" alt="Atelier de motricité libre" class="w-full h-44 object-cover hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="overflow-hidden rounded-2xl shadow-sm">
                            <img loading="lazy" decoding="async" src="{{ asset('images/snoezelen.webp') }}" alt="Espace sensoriel Snoezelen" class="w-full h-44 object-cover hover:scale-105 transition-transform duration-500">
                        </div>
                    </div>
                </div>
                <div class="order-1 lg:order-2 w-12 h-12 rounded-full bg-coral text-white flex items-center justify-center font-display text-lg shadow-lg shrink-0 z-10">2</div>
                <div class="lg:w-1/2 order-2 lg:order-3">
                    <span class="inline-block px-3 py-1 rounded-full bg-white text-primary font-body text-xs uppercase font-bold mb-2">[9h45 – 11h30]</span>
                    <h3 class="font-display text-xl text-primary-deep mb-1">Ateliers d'éveil sensoriel &amp; motricité libre</h3>
                    <p class="font-body text-sm text-primary-deep/80">Inspirés de la pédagogie Pikler-Loczy : motricité libre sur modules en bois brut, parcours sensoriel Snoezelen, en petits groupes de 3 à 4 enfants.</p>
                </div>
            </div>

            {{-- Étape 3 --}}
            <div class="relative flex flex-col lg:flex-row items-center gap-8">
                <div class="lg:w-1/2 lg:text-right order-2 lg:order-1">
                    <span class="inline-block px-3 py-1 rounded-full bg-white text-primary font-body text-xs uppercase font-bold mb-2">[11h30 – 12h45]</span>
                    <h3 class="font-display text-xl text-primary-deep mb-1">Repas &amp; découverte gustative</h3>
                    <p class="font-body text-sm text-primary-deep/80">Déjeuner fait maison et de saison. Autour de petites tables à hauteur d'enfant, la vaisselle ergonomique favorise l'autonomie et le plaisir du partage.</p>
                </div>
                <div class="order-1 lg:order-2 w-12 h-12 rounded-full bg-coral text-white flex items-center justify-center font-display text-lg shadow-lg shrink-0 z-10">3</div>
                <div class="lg:w-1/2 order-3">
                    <div class="overflow-hidden rounded-2xl shadow-sm">
                        <img loading="lazy" decoding="async" src="{{ asset('images/repas.webp') }}" alt="Moment de repas partagé" class="w-full h-56 object-cover hover:scale-105 transition-transform duration-500">
                    </div>
                </div>
            </div>

            {{-- Étape 4 --}}
            <div class="relative flex flex-col lg:flex-row items-center gap-8">
                <div class="lg:w-1/2 order-3 lg:order-1">
                    <div class="overflow-hidden rounded-2xl shadow-sm">
                        <img loading="lazy" decoding="async" src="{{ asset('images/dortoir.webp') }}" alt="Dortoir cocon avec lits en bois naturel" class="w-full h-56 object-cover hover:scale-105 transition-transform duration-500">
                    </div>
                </div>
                <div class="order-1 lg:order-2 w-12 h-12 rounded-full bg-coral text-white flex items-center justify-center font-display text-lg shadow-lg shrink-0 z-10">4</div>
                <div class="lg:w-1/2 order-2 lg:order-3">
                    <span class="inline-block px-3 py-1 rounded-full bg-white text-primary font-body text-xs uppercase font-bold mb-2">[13h00 – 15h15]</span>
                    <h3 class="font-display text-xl text-primary-deep mb-1">Sieste &amp; sommeil physiologique</h3>
                    <p class="font-body text-sm text-primary-deep/80">Dortoir cocon tamisé, berceaux en bois non traité et literie en coton biologique. Chaque enfant est accompagné selon ses rituels propres, sans réveil imposé.</p>
                </div>
            </div>

            {{-- Étape 5 --}}
            <div class="relative flex flex-col lg:flex-row items-center gap-8">
                <div class="lg:w-1/2 lg:text-right order-2 lg:order-1">
                    <span class="inline-block px-3 py-1 rounded-full bg-white text-primary font-body text-xs uppercase font-bold mb-2">[15h30 – 16h30]</span>
                    <h3 class="font-display text-xl text-primary-deep mb-1">Goûter vitaminé &amp; jeux calmes</h3>
                    <p class="font-body text-sm text-primary-deep/80">Fruits frais, compotes maison sans sucre ajouté et hydratation. Contes sensoriels et puzzles en feutrine naturelle.</p>
                </div>
                <div class="order-1 lg:order-2 w-12 h-12 rounded-full bg-coral text-white flex items-center justify-center font-display text-lg shadow-lg shrink-0 z-10">5</div>
                <div class="lg:w-1/2 order-3">
                    <div class="bg-white p-4 rounded-2xl shadow-sm flex items-center gap-4">
                        <svg class="w-9 h-9 text-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
                        <p class="font-body text-sm text-primary-deep/80">Un goûter sain pour recharger les réserves d'énergie tout en prolongeant l'éducation nutritionnelle.</p>
                    </div>
                </div>
            </div>

            {{-- Étape 6 --}}
            <div class="relative flex flex-col lg:flex-row items-center gap-8">
                <div class="lg:w-1/2 order-3 lg:order-1">
                    <div class="bg-white p-4 rounded-2xl shadow-sm flex items-center gap-4">
                        <svg class="w-9 h-9 text-coral shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21s-7-4.35-9.5-8.5C.5 8.5 2.5 5 6 5c2 0 3.5 1.5 4 2.5.5-1 2-2.5 4-2.5 3.5 0 5.5 3.5 3.5 7.5C19 16.65 12 21 12 21z"/></svg>
                        <p class="font-body text-sm text-primary-deep/80">Chaque anecdote ou progrès moteur est partagé en toute transparence, de vive voix.</p>
                    </div>
                </div>
                <div class="order-1 lg:order-2 w-12 h-12 rounded-full bg-coral text-white flex items-center justify-center font-display text-lg shadow-lg shrink-0 z-10">6</div>
                <div class="lg:w-1/2 order-2 lg:order-3">
                    <span class="inline-block px-3 py-1 rounded-full bg-white text-primary font-body text-xs uppercase font-bold mb-2">[16h30 – 18h30]</span>
                    <h3 class="font-display text-xl text-primary-deep mb-1">Retrouvailles &amp; transmissions du soir</h3>
                    <p class="font-body text-sm text-primary-deep/80">Retour progressif des familles. Temps d'échange privilégié pour retracer le fil de la journée : sommeil, repas, observations d'éveil.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 5 : COMMENT S'INSCRIRE --}}
<section class="bg-cream py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <x-confetti shape="etoile" class="top-10 left-[10%] w-7 h-7 text-sun animate-float" />

    <div class="max-w-[1240px] mx-auto px-6 reveal relative z-10" :class="{ 'reveal-shown': shown }">
        <div class="text-center max-w-2xl mx-auto flex flex-col items-center">
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-mint/60 shadow-sm">Processus transparent</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">Le parcours d'admission en 4 étapes</h2>
            <p class="font-body text-primary-deep/80 mt-2">De votre première visite à l'entrée effective de votre enfant, un accompagnement clair.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-12">
            @foreach ([
                ['n' => '01', 'titre' => 'Pré-inscription en ligne', 'texte' => "Remplissez notre formulaire confidentiel : coordonnées, date d'entrée envisagée et rythme de garde désiré.", 'tag' => '3 min chrono', 'bg' => 'bg-primary'],
                ['n' => '02', 'titre' => 'Étude de la demande', 'texte' => "Analyse de votre dossier selon nos critères d'attribution et les plannings de berceaux disponibles.", 'tag' => 'Réponse sous 7 jours', 'bg' => 'bg-primary'],
                ['n' => '03', 'titre' => "Rendez-vous avec l'équipe", 'texte' => 'Visite privée à Alfortville : rencontre de la directrice, découverte des espaces et échanges sur vos attentes.', 'tag' => 'Visite sur place', 'bg' => 'bg-primary'],
                ['n' => '04', 'titre' => 'Dossier final & bienvenue', 'texte' => 'Signature du contrat, remise des documents de santé et planification de la semaine d\'adaptation.', 'tag' => 'Berceau confirmé', 'bg' => 'bg-coral'],
            ] as $etape)
            <div class="bg-white rounded-3xl p-6 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-full {{ $etape['bg'] }} text-white flex items-center justify-center font-display text-sm mb-3 shadow-sm">{{ $etape['n'] }}</div>
                    <h3 class="font-display text-lg text-primary-deep mb-1">{{ $etape['titre'] }}</h3>
                    <p class="font-body text-sm text-primary-deep/80 mb-3">{{ $etape['texte'] }}</p>
                </div>
                <div class="pt-2 font-body text-xs uppercase font-bold {{ $etape['n'] === '04' ? 'text-coral' : 'text-primary' }}">{{ $etape['tag'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SECTION 6 : CRITÈRES D'ATTRIBUTION --}}
<section class="bg-mint py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <x-confetti shape="coeur" class="top-10 right-[6%] w-6 h-6 text-coral animate-bounce-soft" />

    <div class="max-w-[1240px] mx-auto px-6 reveal relative z-10" :class="{ 'reveal-shown': shown }">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
            <div class="lg:col-span-5 flex flex-col gap-4">
                <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold">Équité &amp; ancrage local</span>
                <h2 class="font-display text-3xl md:text-4xl text-primary-deep">Critères d'attribution des berceaux</h2>
                <p class="font-body text-primary-deep/80">Afin de garantir un accès équitable, l'attribution de nos places respecte une charte transparente concertée avec les acteurs de la petite enfance.</p>
                <div class="p-5 rounded-2xl bg-white shadow-sm mt-2">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-7.58 7-12A7 7 0 105 9c0 4.42 7 12 7 12z"/><circle cx="12" cy="9" r="2.3"/></svg>
                        <h3 class="font-display text-lg text-primary">Priorité territoriale</h3>
                    </div>
                    <p class="font-body text-sm text-primary-deep/80">Les familles résidant à <strong>Alfortville</strong> bénéficient d'une priorité naturelle d'ancrage local.</p>
                </div>
            </div>

            <div class="lg:col-span-7 bg-white rounded-3xl p-8 shadow-sm">
                <h3 class="font-display text-xl text-primary-deep mb-5">Ordre d'examen des demandes :</h3>
                <div class="flex flex-col gap-5">
                    @foreach ([
                        ['titre' => 'Enfants non scolarisés', 'texte' => "Accueil de la fin du congé maternité/paternité (dès 10 semaines) jusqu'à l'entrée à l'école maternelle.", 'bg' => 'bg-mint'],
                        ['titre' => 'Fratries & continuité familiale', 'texte' => "Familles ayant déjà un aîné accueilli, afin de préserver les habitudes de la fratrie.", 'bg' => 'bg-mint'],
                        ['titre' => 'Besoins spécifiques & inclusion', 'texte' => "Enfants porteurs de handicap ou nécessitant un protocole d'accueil individualisé (PAI).", 'bg' => 'bg-peach'],
                        ['titre' => 'Situations d\'urgence & partenaires sociaux', 'texte' => 'Dossiers orientés par la PMI, la CAF du Val-de-Marne ou les travailleurs sociaux.', 'bg' => 'bg-mint'],
                        ['titre' => "Berceaux d'entreprise partenaires", 'texte' => 'Salariés d\'entreprises ayant réservé des places pour soutenir la parentalité de leurs collaborateurs.', 'bg' => 'bg-mint'],
                    ] as $critere)
                    <div class="flex items-start gap-4">
                        <div class="w-9 h-9 rounded-full {{ $critere['bg'] }} flex items-center justify-center text-primary shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <h4 class="font-body font-bold text-primary-deep">{{ $critere['titre'] }}</h4>
                            <p class="font-body text-sm text-primary-deep/80">{{ $critere['texte'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 7 : TOUT INCLUS + CTA --}}
<section class="bg-cream py-20 relative overflow-hidden">
    <div class="max-w-[1240px] mx-auto px-6 flex flex-col gap-10">
        <div class="bg-peach/50 rounded-[40px] p-8 lg:p-10 flex flex-col lg:flex-row items-center justify-between gap-8">
            <div class="max-w-2xl">
                <span class="inline-flex items-center gap-2 font-body text-xs text-coral uppercase bg-white px-3 py-1 rounded-full font-bold mb-3">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Tout inclus sans surcoût
                </span>
                <h3 class="font-display text-2xl text-primary-deep mb-2">Une formule claire, respectueuse et sans mauvaise surprise</h3>
                <p class="font-body text-primary-deep/80 mb-4">
                    Repas faits maison et de saison, produits d'hygiène écologiques certifiés, matériel pédagogique en bois brut et ateliers d'éveil sensoriel sont <strong>intégralement inclus</strong> dans nos forfaits mensuels.
                </p>
                <div class="flex flex-wrap items-center gap-4 font-body text-sm text-primary-deep font-semibold">
                    <span class="flex items-center gap-1"><svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Couches écologiques fournies</span>
                    <span class="flex items-center gap-1"><svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Déjeuners et goûters faits maison</span>
                    <span class="flex items-center gap-1"><svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Éligible au CMG (CAF 94)</span>
                </div>
            </div>
            <div class="w-full lg:w-auto flex flex-col items-center justify-center p-6 rounded-3xl bg-white shadow-sm text-center shrink-0">
                <span class="font-body text-xs uppercase tracking-wider text-primary-deep/75 mb-1">Aide financière CAF</span>
                <span class="font-display text-3xl text-primary mb-1">Jusqu'à 85%</span>
                <span class="font-body text-sm text-primary-deep/80 max-w-[220px]">de prise en charge selon vos revenus via le complément CMG.</span>
            </div>
        </div>

        <div class="relative rounded-[40px] bg-coral text-white p-8 lg:p-12 overflow-hidden shadow-2xl">
            <div class="absolute -right-16 -bottom-16 w-80 h-80 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
            <x-confetti shape="etoile" class="top-6 left-[15%] w-7 h-7 text-white/70 animate-wiggle" />
            <div class="relative z-10 max-w-3xl flex flex-col gap-3">
                <span class="font-body text-xs uppercase tracking-widest text-white font-extrabold">Anticipez la rentrée</span>
                <h2 class="font-display text-3xl md:text-4xl">Envie d'offrir à votre enfant un cocon bienveillant à Alfortville ?</h2>
                <p class="font-body text-white/90">Les places étant limitées à 12 berceaux pour garantir une qualité d'attention absolue, nous vous invitons à déposer votre demande dès à présent.</p>
                <div class="pt-3 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <a href="/pre-inscription" class="h-[52px] px-8 rounded-full bg-white text-coral font-body font-bold text-sm flex items-center gap-2 shadow-md hover:scale-105 transition-transform">
                        Pré-inscrire mon enfant
                    </a>
                    <div class="flex items-center gap-2 font-body text-sm">
                        <svg class="w-6 h-6 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2l2 5-2 1a11 11 0 006 6l1-2 5 2v2a2 2 0 01-2 2h-1C9 19 3 13 3 6V5z"/></svg>
                        <div>
                            <a href="tel:{{ $etab->telephoneLien() }}" class="font-bold hover:underline block">{{ $etab->telephone() }}</a>
                            <span class="text-white/80 text-xs">{{ $etab->adresse() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
