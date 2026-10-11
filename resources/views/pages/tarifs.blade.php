@extends('layouts.app')

@section('title', 'Tarifs et aides — Le Jardin de Régina')

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
                    <span class="font-body text-xs uppercase tracking-widest font-extrabold">Transparence &amp; équité tarifaire</span>
                </div>
                <h1 class="font-display text-4xl md:text-5xl text-primary-deep">Des tarifs adaptés à chaque famille</h1>
                <p class="font-display text-xl text-coral">Calculés selon vos ressources, en adéquation avec le barème de référence de la CAF.</p>
                <p class="font-body text-lg text-primary-deep/80 leading-relaxed">
                    Une tarification claire, juste et sans mauvaise surprise, pensée pour accompagner sereinement chaque foyer tout en garantissant un accueil sensoriel et écoresponsable à Alfortville.
                </p>
                <div class="flex flex-wrap gap-2 pt-1">
                    @foreach ([
                        ['icon' => 'check', 'texte' => 'Barème national CAF'],
                        ['icon' => 'coin', 'texte' => 'Éligible CMG & PAJE'],
                        ['icon' => 'percent', 'texte' => "Crédit d'impôt 50%"],
                        ['icon' => 'leaf', 'texte' => 'Tout inclus sans surcoût'],
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
                        Pré-inscrire mon enfant
                    </a>
                    <a href="#aides-financieres" class="h-[52px] px-8 rounded-full bg-white text-primary font-body font-bold text-sm flex items-center justify-center shadow-sm hover:bg-mint/40 transition-all">
                        Découvrir les aides financières
                    </a>
                </div>
            </div>

            {{-- Carte décorative --}}
            <div class="lg:col-span-5 relative">
                <div class="absolute -top-8 -right-8 w-72 h-72 bg-peach rounded-full blur-3xl opacity-70 pointer-events-none -z-10"></div>
                <div class="relative bg-white rounded-3xl p-6 shadow-xl flex flex-col gap-4">
                    <div class="relative w-full h-48 rounded-2xl overflow-hidden bg-mint">
                        <img src="{{ asset('images/salle-de-jeux.jpg') }}" alt="La crèche accueille vos enfants" class="w-full h-full object-cover">
                        <div class="absolute top-3 right-3 px-3 py-1 rounded-full bg-white/90 backdrop-blur-sm text-primary-deep font-body text-xs font-bold flex items-center gap-1.5 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-coral animate-bounce-soft"></span>
                            Ouverture 2026 · 12 places
                        </div>
                    </div>
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-cream">
                            <div class="w-10 h-10 rounded-full bg-mint flex items-center justify-center text-primary shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m-6 4h6m-6 4h4M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-body text-sm font-bold text-primary-deep">Devis personnalisé</span>
                                <span class="font-body text-xs text-primary-deep/60">Établi lors du rendez-vous d'admission</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-cream">
                            <div class="w-10 h-10 rounded-full bg-peach flex items-center justify-center text-coral shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="3"/><path stroke-linecap="round" d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6"/></svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-body text-sm font-bold text-primary-deep">Accompagnement CAF direct</span>
                                <span class="font-body text-xs text-primary-deep/60">Assistance pas-à-pas pour votre dossier PAJE</span>
                            </div>
                        </div>
                    </div>
                    <p class="font-body text-sm text-primary-deep/60 text-center italic">« Chaque enfant est unique, le budget alloué à sa garde se doit d'être parfaitement limpide. »</p>
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
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-white shadow-sm">Méthode de calcul</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">Comment est calculé votre tarif ?</h2>
            <p class="font-body text-primary-deep/70 mt-2">Trois étapes transparentes pour concilier vos contraintes et votre équilibre budgétaire.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            @foreach ([
                ['n' => 1, 'titre' => 'Le taux horaire personnalisé', 'texte' => "Basé sur vos revenus nets imposables de l'année N-2 et la composition de votre foyer, en application des critères de référence de la CAF.", 'tag' => 'CRITÈRE MAJEUR', 'sous' => 'Revenus fiscaux de référence N-2'],
                ['n' => 2, 'titre' => 'Le volume contractualisé', 'texte' => 'Déterminé selon vos besoins réels : accueil régulier (jours et amplitude réservés chaque semaine) ou formule adaptée à votre activité.', 'tag' => 'CRITÈRE MAJEUR', 'sous' => 'Amplitude horaire hebdomadaire choisie'],
                ['n' => 3, 'titre' => 'La mensualisation lissée', 'texte' => "Une facture mensuelle identique lissée sur l'année pour faciliter votre budget, avec régularisations claires en cas d'absences justifiées.", 'tag' => 'BÉNÉFICE PARENT', 'sous' => 'Zéro mauvaise surprise en fin de mois'],
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
                    <p class="font-body text-sm text-primary-deep/70">{{ $etape['texte'] }}</p>
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

{{-- SECTION 3 : GRILLE ET STRUCTURE DU BARÈME --}}
<section class="bg-cream py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <x-confetti shape="squiggle" class="top-10 left-[6%] w-8 h-8 text-primary animate-spin-slow" />

    <div class="max-w-[1240px] mx-auto px-6 reveal relative z-10" :class="{ 'reveal-shown': shown }">
        <div class="text-center max-w-3xl mx-auto flex flex-col items-center">
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-mint/60 shadow-sm">Structure tarifaire</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">Repères indicatifs du barème horaire</h2>
            <p class="font-body text-primary-deep/70 mt-2">Chaque situation fait l'objet d'un calcul individualisé. Les valeurs ci-dessous illustrent la mécanique de dégressivité avec aides.</p>
        </div>

        <div class="mt-12 bg-white rounded-3xl shadow-lg overflow-hidden">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr class="bg-mint/50 text-primary-deep">
                            <th class="py-5 px-6 font-display text-lg font-medium">Tranches de ressources du foyer (N-2)</th>
                            <th class="py-5 px-6 font-display text-lg font-medium text-center">Taux horaire indicatif</th>
                            <th class="py-5 px-6 font-display text-lg font-medium">Reste à charge estimé</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-mint/40">
                        @foreach ([
                            ['t' => 'Tranche 1', 'sous' => 'Revenus inférieurs au plafond CAF tranche basse', 'badge' => 'Reste à charge minime après CMG', 'bg' => 'bg-mint text-primary-deep'],
                            ['t' => 'Tranche 2', 'sous' => 'Revenus médians - barème intermédiaire CAF', 'badge' => 'Accompagnement modulé CAF', 'bg' => 'bg-sky text-primary-deep'],
                            ['t' => 'Tranche 3', 'sous' => 'Revenus supérieurs au plafond intermédiaire', 'badge' => "Bénéfice du crédit d'impôt 50%", 'bg' => 'bg-peach text-coral'],
                            ['t' => "Accueil d'urgence ou occasionnel", 'sous' => 'Sous réserve de place disponible le jour J', 'badge' => "Facturation à l'heure effective", 'bg' => 'bg-cream text-primary-deep'],
                        ] as $row)
                        <tr class="hover:bg-cream/60 transition-colors">
                            <td class="py-5 px-6">
                                <div class="flex flex-col">
                                    <span class="font-body font-bold text-primary">{{ $row['t'] }}</span>
                                    <span class="font-body text-sm text-primary-deep/60">{{ $row['sous'] }}</span>
                                </div>
                            </td>
                            <td class="py-5 px-6 text-center">
                                <span class="font-display text-lg text-primary-deep bg-cream px-3 py-1 rounded-full">[xx,xx €] / h</span>
                            </td>
                            <td class="py-5 px-6">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $row['bg'] }} font-body text-sm font-semibold">{{ $row['badge'] }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-6 bg-mint/30 flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 8h.01M11 12h1v4h1"/></svg>
                </div>
                <div>
                    <span class="font-body font-bold text-primary-deep block">Précision déontologique &amp; équité</span>
                    <p class="font-body text-sm text-primary-deep/70">Le barème détaillé et le montant exact de votre mensualité vous seront communiqués lors de votre rendez-vous avec la direction, sur présentation de votre avis d'imposition et de votre attestation de droits CAF.</p>
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
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-white shadow-sm">Tranquillité absolue</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">Une formule tout inclus, sans mauvaise surprise</h2>
            <p class="font-body text-primary-deep/70 mt-2">Tous les essentiels du bien-être, de l'éveil et de la nutrition sont compris dans nos forfaits.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mt-12">
            @foreach ([
                ['titre' => 'Repas faits maison et de saison', 'texte' => 'Petits déjeuners, déjeuners et goûters préparés avec des ingrédients locaux et de saison. Menus équilibrés.', 'icon' => 'meal'],
                ['titre' => 'Soins & couches écologiques', 'texte' => 'Couches écologiques sans chlore ni perturbateurs endocriniens, liniment et soins hypoallergéniques fournis chaque jour.', 'icon' => 'drop'],
                ['titre' => "Matériel d'éveil d'exception", 'texte' => 'Jeux sensoriels en bois non traité, modules de motricité libre Pikler, livres tactiles et matériel Montessori.', 'icon' => 'toy'],
                ['titre' => 'Activités & intervenants', 'texte' => "Éveil musical, anglais immersif, communication gestuelle « bébé signe », parcours Snoezelen et potager.", 'icon' => 'palette'],
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
                <p class="font-body text-sm text-primary-deep/70">{{ $carte['texte'] }}</p>
                <span class="mt-auto pt-2 inline-flex items-center gap-1.5 font-body text-sm text-primary font-bold">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Inclus au forfait
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
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-mint/60 shadow-sm">Dispositifs d'aide d'État</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">Un reste à charge réduit grâce aux aides publiques</h2>
            <p class="font-body text-primary-deep/70 mt-2">La micro-crèche ouvre droit à deux dispositifs cumulables qui allègent la charge mensuelle des familles.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-12">
            {{-- CMG --}}
            <div class="bg-white rounded-3xl p-8 shadow-lg flex flex-col gap-5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-mint/60 rounded-full blur-2xl pointer-events-none"></div>
                <div class="relative flex flex-col gap-3">
                    <span class="inline-flex items-center gap-1.5 self-start px-3.5 py-1.5 rounded-full bg-primary text-white font-body text-xs uppercase font-extrabold">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10h18"/></svg>
                        Versé chaque mois par la CAF
                    </span>
                    <h3 class="font-display text-2xl text-primary-deep">Le Complément de Mode de Garde (CMG - PAJE)</h3>
                    <p class="font-body text-primary-deep/80">Une aide accordée directement aux familles par la CAF, sous conditions d'activité et de ressources, pour tout enfant de moins de 6 ans accueilli au moins 16 heures par mois.</p>
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
                            <span class="font-body text-sm text-primary-deep">{{ $point }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="p-4 rounded-2xl bg-cream flex items-center justify-between">
                    <span class="font-body text-sm text-primary-deep/60">Éligibilité automatique dès 16h/mois</span>
                    <span class="font-body text-sm text-primary font-bold">CAF Val-de-Marne</span>
                </div>
            </div>

            {{-- Crédit d'impôt --}}
            <div class="bg-white rounded-3xl p-8 shadow-lg flex flex-col gap-5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-peach/70 rounded-full blur-2xl pointer-events-none"></div>
                <div class="relative flex flex-col gap-3">
                    <span class="inline-flex items-center gap-1.5 self-start px-3.5 py-1.5 rounded-full bg-coral text-white font-body text-xs uppercase font-extrabold">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="7" cy="7" r="2.5"/><circle cx="17" cy="17" r="2.5"/><path stroke-linecap="round" d="M18 6L6 18"/></svg>
                        50% des dépenses déductibles
                    </span>
                    <h3 class="font-display text-2xl text-primary-deep">Le Crédit d'Impôt Frais de Garde</h3>
                    <p class="font-body text-primary-deep/80">Un dispositif fiscal accordé à tous les parents exerçant une activité professionnelle ou inscrits comme demandeurs d'emploi, divisant de moitié le coût résiduel.</p>
                    <ul class="flex flex-col gap-3 pt-2">
                        @foreach ([
                            "Crédit d'impôt de 50% calculé sur les sommes restant à charge (après CMG).",
                            'Plafond légal de 3 500 € par an et par enfant, soit jusqu\'à 1 750 € d\'économie annuelle.',
                            'Remboursé par chèque ou virement du Trésor Public si vous n\'êtes pas imposable.',
                            'Attestation fiscale annuelle fournie par nos soins.',
                        ] as $point)
                        <li class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-peach flex items-center justify-center text-coral shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="font-body text-sm text-primary-deep">{{ $point }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="p-4 rounded-2xl bg-cream flex items-center justify-between">
                    <span class="font-body text-sm text-primary-deep/60">Article 200 quater B du CGI</span>
                    <span class="font-body text-sm text-coral font-bold">Jusqu'à 1 750 €/an</span>
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
                    <span class="font-display text-xl text-primary-deep">Simulez votre reste à charge mensuel</span>
                    <p class="font-body text-primary-deep/70">Obtenez un premier aperçu de votre budget garde en quelques secondes.</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-peach text-coral font-body text-xs font-extrabold uppercase shrink-0 self-start sm:self-center">
                    <span class="w-2 h-2 rounded-full bg-coral"></span>
                    Bientôt disponible
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 opacity-70 pointer-events-none">
                <div class="flex flex-col gap-2">
                    <label class="font-body text-sm font-bold text-primary-deep">Revenus mensuels du foyer (€)</label>
                    <div class="h-[52px] px-5 rounded-full bg-cream flex items-center text-primary-deep/50 font-body text-sm">Ex. 3 800 €</div>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-body text-sm font-bold text-primary-deep">Nombre d'enfants à charge</label>
                    <div class="h-[52px] px-5 rounded-full bg-cream flex items-center justify-between text-primary-deep font-body text-sm">
                        <span>1 enfant</span>
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-body text-sm font-bold text-primary-deep">Rythme de garde envisagé</label>
                    <div class="h-[52px] px-5 rounded-full bg-cream flex items-center justify-between text-primary-deep font-body text-sm">
                        <span>Temps plein (5 jours)</span>
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-1">
                <button type="button" disabled class="h-[52px] px-8 rounded-full bg-cream text-primary-deep/50 font-body font-bold text-sm inline-flex items-center justify-center gap-2 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 7v5l3 3"/></svg>
                    Calculer mon estimation (en cours de finalisation)
                </button>
                <span class="font-body text-sm text-primary-deep/60 text-center sm:text-right">Conforme aux barèmes CAF en vigueur</span>
            </div>

            <div class="p-5 rounded-2xl bg-peach/50 flex items-start gap-3">
                <svg class="w-6 h-6 text-coral shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-7.58 7-12A7 7 0 105 9c0 4.42 7 12 7 12z"/><circle cx="12" cy="9" r="2.3"/></svg>
                <p class="font-body text-sm text-primary-deep">
                    <strong>Besoin d'un calcul immédiat ?</strong> En attendant le simulateur en ligne, notre direction établit une simulation financière personnalisée et confidentielle sous 48h lors de votre demande de pré-inscription.
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
        <span class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-white/20 font-body text-xs uppercase tracking-widest font-extrabold">Équipe à votre écoute</span>
        <h2 class="font-display text-3xl md:text-4xl">Une question sur les tarifs ou votre prise en charge ?</h2>
        <p class="font-body text-white/90 max-w-xl">Notre équipe vous conseille et vous guide pas à pas dans vos démarches CAF pour optimiser votre budget de garde.</p>
        <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
            <a href="/pre-inscription" class="h-[52px] px-8 rounded-full bg-white text-coral font-body font-bold text-sm flex items-center justify-center shadow-lg hover:-translate-y-0.5 transition-all">
                Pré-inscrire mon enfant
            </a>
            <a href="tel:0767646184" class="h-[52px] px-8 rounded-full bg-white/15 text-white font-body font-bold text-sm flex items-center gap-2 hover:bg-white/25 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2l2 5-2 1a11 11 0 006 6l1-2 5 2v2a2 2 0 01-2 2h-1C9 19 3 13 3 6V5z"/></svg>
                07 67 64 61 84
            </a>
        </div>
        <span class="flex items-center gap-2 font-body text-sm text-white/80 pt-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 7v5l3 3"/></svg>
            Réponse bienveillante sous 48h · Étude gratuite sans engagement
        </span>
    </div>
</section>

@endsection
