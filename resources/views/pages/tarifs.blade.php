@extends('layouts.app')

@section('title', __('Tarifs et aides CAF — Micro-crèche Alfortville'))
@section('description', __('Tarifs du Jardin de Régina et aides possibles : complément de libre choix du mode de garde (CMG / PAJE) de la CAF et crédit d\'impôt de 50 %.'))
  


@inject('tarification', 'App\Support\Tarification')

@section('content')

{{-- SECTION 1 : HERO --}}
<section class="relative bg-cream pt-10 pb-16 overflow-hidden">
    <div class="absolute -top-20 -right-20 w-96 h-96 rounded-full bg-peach blur-3xl pointer-events-none -z-10"></div>
    <x-confetti shape="etoile" class="top-20 left-[6%] w-7 h-7 text-sun animate-float" />
    <x-confetti shape="fleur" class="bottom-14 left-[30%] w-6 h-6 text-coral animate-wiggle" />

    <div class="max-w-[1240px] mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            <div class="lg:col-span-7 flex flex-col gap-4">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-mint/60 text-primary self-start">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
                    <span class="font-body text-xs uppercase tracking-widest font-extrabold">{{ __('Transparence & équité tarifaire') }}</span>
                </div>
                <h1 class="font-display text-4xl md:text-5xl text-primary-deep">{{ __('Un tarif clair, des aides adaptées à chaque famille') }}</h1>
                <p class="font-display text-xl text-coral">{{ __('Un tarif horaire unique de :tarif, et des aides de la CAF qui s\'adaptent à vos revenus.', ['tarif' => $tarification->tarifHoraireLisible()]) }}</p>
                <p class="font-body text-lg text-primary-deep/80 leading-relaxed">
                    {{ __('Une tarification claire, juste et sans mauvaise surprise, pensée pour accompagner sereinement chaque foyer tout en garantissant un accueil sensoriel et écoresponsable à Alfortville.') }}
                </p>
                <div class="flex flex-wrap gap-2 pt-1">
                    @foreach ([
                        ['icon' => 'check', 'texte' => __('Tarif unique : :tarif / h', ['tarif' => $tarification->tarifHoraireLisible()])],
                        ['icon' => 'coin', 'texte' => __('Éligible CMG & PAJE')],
                        ['icon' => 'percent', 'texte' => __('Crédit d\'impôt 50%')],
                        ['icon' => 'leaf', 'texte' => __('Tout inclus sans surcoût')],
                    ] as $pill)
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white shadow-sm font-body text-sm text-primary-deep">
                        @if ($pill['icon'] === 'check')
                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        @elseif ($pill['icon'] === 'coin')
                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10h18"/><circle cx="16" cy="14.5" r="1" fill="currentColor"/></svg>
                        @elseif ($pill['icon'] === 'percent')
                            <svg class="w-4 h-4 text-coral" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="7" cy="7" r="2.5"/><circle cx="17" cy="17" r="2.5"/><path stroke-linecap="round" d="M18 6L6 18"/></svg>
                        @else
                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
                        @endif
                        <span>{{ $pill['texte'] }}</span>
                    </span>
                    @endforeach
                </div>
                <div class="flex flex-wrap items-center gap-4 pt-3">
                    <a href="/pre-inscription" class="h-[52px] px-8 rounded-full bg-coral text-white font-body font-bold text-sm flex items-center justify-center shadow-lg hover:-translate-y-0.5 hover:opacity-90 transition-all">
                        {{ __('Pré-inscrire mon enfant') }}
                    </a>
                    <a href="#aides-financieres" class="h-[52px] px-8 rounded-full bg-white text-primary font-body font-bold text-sm flex items-center justify-center shadow-sm hover:bg-mint/40 transition-all">
                        {{ __('Découvrir les aides financières') }}
                    </a>
                </div>
            </div>

            {{-- Carte décorative --}}
            <div class="lg:col-span-5 relative">
                <div class="absolute -top-8 -right-8 w-72 h-72 bg-peach rounded-full blur-3xl opacity-70 pointer-events-none -z-10"></div>
                <div class="relative bg-white rounded-3xl p-6 shadow-xl flex flex-col gap-4">
                    <div class="relative w-full h-48 rounded-2xl overflow-hidden bg-mint">
                        <img src="{{ asset('images/salle-de-jeux.webp') }}" alt="{{ __('La crèche accueille vos enfants') }}" class="w-full h-full object-cover">
                        <div class="absolute top-3 right-3 px-3 py-1 rounded-full bg-white/90 backdrop-blur-sm text-primary-deep font-body text-xs font-bold flex items-center gap-1.5 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-coral animate-bounce-soft"></span>
                            @if ($etab->ouvertureAVenir())
                                {{ __('Ouverture le :date · 12 places', ['date' => $etab->ouvertureLisible()]) }}
                            @else
                                {{ __('12 places · :ville', ['ville' => $etab->ville()]) }}
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-cream">
                            <div class="w-10 h-10 rounded-full bg-mint flex items-center justify-center text-primary shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m-6 4h6m-6 4h4M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-body text-sm font-bold text-primary-deep">{{ __('Devis personnalisé') }}</span>
                                <span class="font-body text-xs text-primary-deep/75">{{ __('Établi lors du rendez-vous d\'admission') }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-cream">
                            <div class="w-10 h-10 rounded-full bg-peach flex items-center justify-center text-coral shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="3"/><path stroke-linecap="round" d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6"/></svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-body text-sm font-bold text-primary-deep">{{ __('Accompagnement CAF direct') }}</span>
                                <span class="font-body text-xs text-primary-deep/75">{{ __('Assistance pas-à-pas pour votre dossier PAJE') }}</span>
                            </div>
                        </div>
                    </div>
                    <p class="font-body text-sm text-primary-deep/75 text-center italic">{{ __('« Chaque enfant est unique, le budget alloué à sa garde se doit d\'être parfaitement limpide. »') }}</p>
                </div>
            </div>
        </div>
    </div>

    <x-hill-divider color="mint" />
</section>

{{-- SECTION 2 : COMMENT EST CALCULÉ VOTRE TARIF --}}
<section class="bg-mint py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <x-confetti shape="rond" class="top-14 right-[8%] w-5 h-5 bg-white animate-float-slow" />

    <div class="max-w-[1240px] mx-auto px-6 reveal relative z-10" :class="{ 'reveal-shown': shown }">
        <div class="text-center max-w-2xl mx-auto flex flex-col items-center">
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-white shadow-sm">{{ __('Méthode de calcul') }}</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">{{ __('Comment est calculé votre tarif ?') }}</h2>
            <p class="font-body text-primary-deep/80 mt-2">{{ __('Trois étapes transparentes pour concilier vos contraintes et votre équilibre budgétaire.') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            @foreach ([
                ['n' => 1, 'titre' => __('Un tarif horaire unique'), 'texte' => __('Le même tarif pour toutes les familles : :tarif de l\'heure, repas, couches et activités compris. Il respecte le plafond fixé par la CAF pour bénéficier du CMG.', ['tarif' => $tarification->tarifHoraireLisible()]), 'tag' => __('TARIF UNIQUE'), 'sous' => __(':tarif / heure, tout compris', ['tarif' => $tarification->tarifHoraireLisible()])],
                ['n' => 2, 'titre' => __('Le volume contractualisé'), 'texte' => __('Déterminé selon vos besoins réels : accueil régulier (jours et amplitude réservés chaque semaine) ou formule adaptée à votre activité.'), 'tag' => __('VOTRE CHOIX'), 'sous' => __('Amplitude horaire hebdomadaire choisie')],
                ['n' => 3, 'titre' => __('La mensualisation lissée'), 'texte' => __('Une facture mensuelle identique lissée sur l\'année pour faciliter votre budget, avec régularisations claires en cas d\'absences justifiées.'), 'tag' => __('BÉNÉFICE PARENT'), 'sous' => __('Zéro mauvaise surprise en fin de mois')],
            ] as $etape)
            <div class="bg-white rounded-3xl p-6 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="w-12 h-12 rounded-full bg-primary text-white font-display flex items-center justify-center">{{ $etape['n'] }}</span>
                        @if ($etape['n'] === 1)
                            <svg class="w-6 h-6 text-primary/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M4 21V10l8-6 8 6v11M9 21v-6h6v6"/></svg>
                        @elseif ($etape['n'] === 2)
                            <svg class="w-6 h-6 text-primary/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg>
                        @else
                            <svg class="w-6 h-6 text-primary/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 3h7l4 4v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z"/><path stroke-linecap="round" d="M9 12h6M9 16h6M14 3v4h4"/></svg>
                        @endif
                    </div>
                    <h3 class="font-display text-lg text-primary-deep mb-2">{{ $etape['titre'] }}</h3>
                    <p class="font-body text-sm text-primary-deep/80">{{ $etape['texte'] }}</p>
                </div>
                <div class="mt-4 pt-3 bg-cream rounded-2xl p-3">
                    <span class="font-body text-xs uppercase text-primary font-extrabold block">{{ $etape['tag'] }}</span>
                    <span class="font-body text-sm text-primary-deep">{{ $etape['sous'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SECTION 3 : TARIF UNIQUE ET EXEMPLE DE RESTE À CHARGE (calculé depuis config/creche.php) --}}
<section class="bg-cream py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <x-confetti shape="squiggle" class="top-10 left-[6%] w-8 h-8 text-primary animate-spin-slow" />

    <div class="max-w-[1240px] mx-auto px-6 reveal relative z-10" :class="{ 'reveal-shown': shown }">
        <div class="text-center max-w-3xl mx-auto flex flex-col items-center">
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-mint/60 shadow-sm">{{ __('Exemple chiffré') }}</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">{{ __('Combien coûte une place ?') }}</h2>
            <p class="font-body text-primary-deep/80 mt-2">{{ __('Un seul tarif pour toutes les familles, tout compris. L\'aide de la CAF (CMG) et le crédit d\'impôt réduisent ensuite fortement votre reste à charge.') }}</p>
        </div>

        <div class="mt-12 grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            {{-- Tarif horaire unique --}}
            <div class="lg:col-span-4 bg-primary text-white rounded-3xl p-7 shadow-lg flex flex-col gap-4">
                <span class="font-body text-xs uppercase tracking-widest font-extrabold text-white">{{ __('Tarif horaire unique') }}</span>
                <p class="flex items-baseline gap-2">
                    <span class="font-display text-6xl">{{ $tarification->tarifHoraireLisible() }}</span>
                    <span class="font-body text-lg">{{ __('/ heure') }}</span>
                </p>
                <ul class="flex flex-col gap-2 font-body text-sm">
                    @foreach ([
                        __('Le même tarif pour toutes les familles'),
                        __('Repas, couches et activités compris'),
                        __('Mensualisé selon les jours et horaires réservés'),
                        __('Accueil occasionnel ou d\'urgence : facturé à l\'heure effective'),
                    ] as $point)
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ $point }}</span>
                    </li>
                    @endforeach
                </ul>
                <p class="font-body text-xs text-white/90 border-t border-white/20 pt-3">{{ __('Ce tarif respecte le plafond de 10 € de l\'heure fixé par la CAF : votre famille peut donc bénéficier du complément de mode de garde (CMG).') }}</p>
            </div>

            {{-- Exemple de reste à charge selon les revenus --}}
            <div class="lg:col-span-8 bg-white rounded-3xl shadow-lg overflow-hidden">
                <div class="p-6 pb-4 flex flex-col gap-1">
                    <h3 class="font-display text-xl text-primary-deep">{{ __('Exemple : accueil à temps plein') }}</h3>
                    <p class="font-body text-sm text-primary-deep/80">
                        {{ __('Enfant de moins de 3 ans accueilli 5 jours par semaine, :heuresjour h par jour (environ :heuresmois h par mois), couple avec un enfant. Coût avant aides : :cout par mois.', [
                            'heuresjour' => Illuminate\Support\Number::format($tarification->heuresParJour(), maxPrecision: 1, locale: app()->getLocale()),
                            'heuresmois' => Illuminate\Support\Number::format(round($tarification->heuresMensuellesTempsPlein()), locale: app()->getLocale()),
                            'cout' => App\Support\Tarification::euros($tarification->coutMensuelTempsPlein()),
                        ]) }}
                    </p>
                </div>
                <div class="w-full overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[560px]">
                        <thead>
                            <tr class="bg-mint/50 text-primary-deep">
                                <th scope="col" class="py-4 px-6 font-display text-base font-medium">{{ __('Revenus annuels du foyer') }}</th>
                                <th scope="col" class="py-4 px-6 font-display text-base font-medium text-right">{{ __('Aide CAF (CMG) / mois') }}</th>
                                <th scope="col" class="py-4 px-6 font-display text-base font-medium text-right">{{ __('Reste à charge / mois *') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-mint/40">
                            @foreach ($tarification->exemplesTempsPlein() as $exemple)
                            <tr class="hover:bg-cream/60 transition-colors">
                                <th scope="row" class="py-4 px-6 font-body font-bold text-primary-deep">
                                    @if ($exemple['revenus_min'] === null)
                                        {{ __('Jusqu\'à :max', ['max' => App\Support\Tarification::euros($exemple['revenus_max'])]) }}
                                    @elseif ($exemple['revenus_max'] === null)
                                        {{ __('Plus de :min', ['min' => App\Support\Tarification::euros($exemple['revenus_min'])]) }}
                                    @else
                                        {{ __('De :min à :max', ['min' => App\Support\Tarification::euros($exemple['revenus_min']), 'max' => App\Support\Tarification::euros($exemple['revenus_max'])]) }}
                                    @endif
                                </th>
                                <td class="py-4 px-6 font-body text-primary-deep text-right">{{ App\Support\Tarification::euros($exemple['cmg']) }}</td>
                                <td class="py-4 px-6 text-right">
                                    <span class="inline-flex px-3 py-1 rounded-full bg-mint font-display text-lg text-primary-deep">{{ __('≈ :montant', ['montant' => App\Support\Tarification::euros($exemple['reste'])]) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-6 bg-mint/30 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center shrink-0" aria-hidden="true">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 8h.01M11 12h1v4h1"/></svg>
                    </div>
                    <p class="font-body text-sm text-primary-deep/80">
                        {{ __('* Après déduction du CMG et du crédit d\'impôt pour frais de garde (50 % des dépenses, dans la limite de 3 500 € par an). Montants 2026 indicatifs : le montant exact de votre aide est calculé par la CAF (simulateur sur caf.fr). Parent isolé : aide de la CAF majorée de 40 %. Enfant de 3 à 6 ans : aide divisée par deux. Votre mensualité exacte vous est confirmée lors du rendez-vous avec la direction, selon les jours et horaires choisis.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 4 : CE QUI EST INCLUS --}}
<section class="bg-mint py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <x-confetti shape="coeur" class="top-10 right-[6%] w-6 h-6 text-coral animate-bounce-soft" />
    <x-confetti shape="etoile" class="bottom-14 left-[8%] w-7 h-7 text-sun animate-float" />

    <div class="max-w-[1240px] mx-auto px-6 reveal relative z-10" :class="{ 'reveal-shown': shown }">
        <div class="text-center max-w-3xl mx-auto flex flex-col items-center">
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-white shadow-sm">{{ __('Tranquillité absolue') }}</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">{{ __('Une formule tout inclus, sans mauvaise surprise') }}</h2>
            <p class="font-body text-primary-deep/80 mt-2">{{ __('Tous les essentiels du bien-être, de l\'éveil et de la nutrition sont compris dans nos forfaits.') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mt-12">
            @foreach ([
                ['titre' => __('Repas faits maison et de saison'), 'texte' => __('Petits déjeuners, déjeuners et goûters préparés avec des ingrédients locaux et de saison. Menus équilibrés.'), 'icon' => 'meal'],
                ['titre' => __('Soins & couches écologiques'), 'texte' => __('Couches écologiques sans chlore ni perturbateurs endocriniens, liniment et soins hypoallergéniques fournis chaque jour.'), 'icon' => 'drop'],
                ['titre' => __('Matériel d\'éveil d\'exception'), 'texte' => __('Jeux sensoriels en bois non traité, modules de motricité libre Pikler, livres tactiles et matériel Montessori.'), 'icon' => 'toy'],
                ['titre' => __('Activités & intervenants'), 'texte' => __('Éveil musical, anglais immersif, communication gestuelle « bébé signe », parcours Snoezelen et potager.'), 'icon' => 'palette'],
            ] as $carte)
            <div class="bg-white rounded-3xl p-6 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all flex flex-col gap-3">
                <div class="w-14 h-14 rounded-2xl bg-mint flex items-center justify-center text-primary">
                    @if ($carte['icon'] === 'meal')
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 3v7a2 2 0 002 2 2 2 0 002-2V3M9 12v9M16 3c-1.3 1.3-2 3-2 5s.7 3.7 2 5c1.3-1.3 2-3 2-5s-.7-3.7-2-5z"/></svg>
                    @elseif ($carte['icon'] === 'drop')
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 5-7 9-7 12.5a7 7 0 0014 0C19 12 16 8 12 3z"/></svg>
                    @elseif ($carte['icon'] === 'toy')
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="6" r="3"/><path stroke-linecap="round" stroke-linejoin="round" d="M4 21v-4a4 4 0 014-4h8a4 4 0 014 4v4"/></svg>
                    @else
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><circle cx="9" cy="10" r="1" fill="currentColor"/><circle cx="14" cy="9" r="1" fill="currentColor"/><circle cx="15" cy="14" r="1" fill="currentColor"/><path stroke-linecap="round" d="M12 21a9 9 0 010-18"/></svg>
                    @endif
                </div>
                <h3 class="font-display text-lg text-primary-deep">{{ $carte['titre'] }}</h3>
                <p class="font-body text-sm text-primary-deep/80">{{ $carte['texte'] }}</p>
                <span class="mt-auto pt-2 inline-flex items-center gap-1.5 font-body text-sm text-primary font-bold">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    {{ __('Inclus au forfait') }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SECTION 5 : LES AIDES FINANCIÈRES --}}
<section id="aides-financieres" class="bg-cream py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <x-confetti shape="fleur" class="top-10 left-[6%] w-7 h-7 text-coral animate-wiggle" />

    <div class="max-w-[1240px] mx-auto px-6 reveal relative z-10" :class="{ 'reveal-shown': shown }">
        <div class="text-center max-w-3xl mx-auto flex flex-col items-center">
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-mint/60 shadow-sm">{{ __('Dispositifs d\'aide d\'État') }}</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">{{ __('Un reste à charge réduit grâce aux aides publiques') }}</h2>
            <p class="font-body text-primary-deep/80 mt-2">{{ __('La micro-crèche ouvre droit à deux dispositifs cumulables qui allègent la charge mensuelle des familles.') }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-12">
            {{-- CMG --}}
            <div class="bg-white rounded-3xl p-8 shadow-lg flex flex-col gap-5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-mint/60 rounded-full blur-2xl pointer-events-none"></div>
                <div class="relative flex flex-col gap-3">
                    <span class="inline-flex items-center gap-1.5 self-start px-3.5 py-1.5 rounded-full bg-primary text-white font-body text-xs uppercase font-extrabold">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10h18"/></svg>
                        {{ __('Versé chaque mois par la CAF') }}
                    </span>
                    <h3 class="font-display text-2xl text-primary-deep">{{ __('Le Complément de Mode de Garde (CMG - PAJE)') }}</h3>
                    <p class="font-body text-primary-deep/80">{{ __('Une aide accordée directement aux familles par la CAF, sous conditions d\'activité et de ressources, pour tout enfant de moins de 6 ans accueilli au moins 16 heures par mois.') }}</p>
                    <ul class="flex flex-col gap-3 pt-2">
                        @foreach ([
                            'Prise en charge jusqu\'à 85% des frais engagés selon votre tranche de ressources.',
                            'Un minimum légal de 15% du coût reste obligatoirement à la charge de la famille.',
                            'Versement direct sur votre compte après déclaration de présence en un clic.',
                            'Prestation totalement non imposable sur le revenu.',
                        ] as $point)
                        <li class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-mint flex items-center justify-center text-primary shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="font-body text-sm text-primary-deep">{{ __($point) }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="p-4 rounded-2xl bg-cream flex items-center justify-between">
                    <span class="font-body text-sm text-primary-deep/75">{{ __('Éligibilité automatique dès 16h/mois') }}</span>
                    <span class="font-body text-sm text-primary font-bold">{{ __('CAF Val-de-Marne') }}</span>
                </div>
            </div>

            {{-- Crédit d'impôt --}}
            <div class="bg-white rounded-3xl p-8 shadow-lg flex flex-col gap-5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-peach/70 rounded-full blur-2xl pointer-events-none"></div>
                <div class="relative flex flex-col gap-3">
                    <span class="inline-flex items-center gap-1.5 self-start px-3.5 py-1.5 rounded-full bg-coral text-white font-body text-xs uppercase font-extrabold">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="7" cy="7" r="2.5"/><circle cx="17" cy="17" r="2.5"/><path stroke-linecap="round" d="M18 6L6 18"/></svg>
                        {{ __('50% des dépenses déductibles') }}
                    </span>
                    <h3 class="font-display text-2xl text-primary-deep">{{ __('Le Crédit d\'Impôt Frais de Garde') }}</h3>
                    <p class="font-body text-primary-deep/80">{{ __('Un dispositif fiscal accordé à tous les parents exerçant une activité professionnelle ou inscrits comme demandeurs d\'emploi, divisant de moitié le coût résiduel.') }}</p>
                    <ul class="flex flex-col gap-3 pt-2">
                        @foreach ([
                            "Crédit d'impôt de 50% calculé sur les sommes restant à charge (après CMG), hors frais de repas.",
                            'Plafond légal de 3 500 € par an et par enfant, soit jusqu\'à 1 750 € d\'économie annuelle.',
                            'Remboursé par chèque ou virement du Trésor Public si vous n\'êtes pas imposable.',
                            'Attestation fiscale annuelle fournie par nos soins.',
                        ] as $point)
                        <li class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-peach flex items-center justify-center text-coral shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="font-body text-sm text-primary-deep">{{ __($point) }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="p-4 rounded-2xl bg-cream flex items-center justify-between">
                    <span class="font-body text-sm text-primary-deep/75">{{ __('Article 200 quater B du CGI') }}</span>
                    <span class="font-body text-sm text-coral font-bold">{{ __('Jusqu\'à 1 750 €/an') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 6 : SIMULATEUR (bientôt disponible) --}}
<section class="bg-mint py-20 relative overflow-hidden">
    <x-confetti shape="squiggle" class="top-10 right-[8%] w-8 h-8 text-primary-deep animate-spin-slow" />

    <div class="max-w-[1240px] mx-auto px-6 relative z-10">
        <div class="bg-white rounded-[40px] p-8 lg:p-10 shadow-lg max-w-4xl mx-auto flex flex-col gap-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3">
                <div class="flex flex-col gap-1">
                    <span class="font-display text-xl text-primary-deep">{{ __('Simulez votre reste à charge mensuel') }}</span>
                    <p class="font-body text-primary-deep/80">{{ __('Obtenez un premier aperçu de votre budget garde en quelques secondes.') }}</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-peach text-coral font-body text-xs font-extrabold uppercase shrink-0 self-start sm:self-center">
                    <span class="w-2 h-2 rounded-full bg-coral"></span>
                    {{ __('Bientôt disponible') }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pointer-events-none">
                <div class="flex flex-col gap-2">
                    <label class="font-body text-sm font-bold text-primary-deep">{{ __('Revenus mensuels du foyer (€)') }}</label>
                    <div class="h-[52px] px-5 rounded-full bg-cream flex items-center text-primary-deep/75 font-body text-sm">{{ __('Ex. 3 800 €') }}</div>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-body text-sm font-bold text-primary-deep">{{ __('Nombre d\'enfants à charge') }}</label>
                    <div class="h-[52px] px-5 rounded-full bg-cream flex items-center justify-between text-primary-deep font-body text-sm">
                        <span>{{ __('1 enfant') }}</span>
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-body text-sm font-bold text-primary-deep">{{ __('Rythme de garde envisagé') }}</label>
                    <div class="h-[52px] px-5 rounded-full bg-cream flex items-center justify-between text-primary-deep font-body text-sm">
                        <span>{{ __('Temps plein (5 jours)') }}</span>
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-1">
                <button type="button" disabled class="h-[52px] px-8 rounded-full bg-cream text-primary-deep/75 font-body font-bold text-sm inline-flex items-center justify-center gap-2 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 7v5l3 3"/></svg>
                    {{ __('Calculer mon estimation (en cours de finalisation)') }}
                </button>
                <span class="font-body text-sm text-primary-deep/75 text-center sm:text-right">{{ __('Conforme aux barèmes CAF en vigueur') }}</span>
            </div>

            <div class="p-5 rounded-2xl bg-peach/50 flex items-start gap-3">
                <svg class="w-6 h-6 text-coral shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-7.58 7-12A7 7 0 105 9c0 4.42 7 12 7 12z"/><circle cx="12" cy="9" r="2.3"/></svg>
                <p class="font-body text-sm text-primary-deep">
                    <strong>{{ __('Besoin d\'un calcul immédiat ?') }}</strong> {{ __('En attendant le simulateur en ligne, notre direction établit une simulation financière personnalisée et confidentielle sous 48h lors de votre demande de pré-inscription.') }}
                </p>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 7 : CTA FINAL --}}
<section class="bg-coral text-white py-20 relative overflow-hidden">
    <div class="absolute -bottom-16 -right-16 w-80 h-80 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
    <x-confetti shape="etoile" class="top-10 left-[15%] w-7 h-7 text-white/70 animate-wiggle" />

    <div class="max-w-[860px] mx-auto px-6 text-center flex flex-col items-center gap-4 relative z-10">
        <span class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-primary-deep/25 font-body text-xs uppercase tracking-widest font-extrabold">{{ __('Équipe à votre écoute') }}</span>
        <h2 class="font-display text-3xl md:text-4xl">{{ __('Une question sur les tarifs ou votre prise en charge ?') }}</h2>
        <p class="font-body text-white/90 max-w-xl">{{ __('Notre équipe vous conseille et vous guide pas à pas dans vos démarches CAF pour optimiser votre budget de garde.') }}</p>
        <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
            <a href="/pre-inscription" class="h-[52px] px-8 rounded-full bg-white text-coral font-body font-bold text-sm flex items-center justify-center shadow-lg hover:-translate-y-0.5 transition-all">
                {{ __('Pré-inscrire mon enfant') }}
            </a>
            <a href="tel:{{ $etab->telephoneLien() }}" class="h-[52px] px-8 rounded-full bg-primary-deep/25 text-white font-body font-bold text-sm flex items-center gap-2 hover:bg-primary-deep/40 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2l2 5-2 1a11 11 0 006 6l1-2 5 2v2a2 2 0 01-2 2h-1C9 19 3 13 3 6V5z"/></svg>
                {{ $etab->telephone() }}
            </a>
        </div>
        <span class="flex items-center gap-2 font-body text-sm text-white/80 pt-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 7v5l3 3"/></svg>
            {{ __('Réponse bienveillante sous 48h · Étude gratuite sans engagement') }}
        </span>
    </div>
</section>

@endsection
