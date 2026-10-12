@extends('layouts.app')

 @section('title', "Notre projet pédagogique — Micro-crèche Alfortville")
 @section('description', "Motricité libre, inspiration Pikler et Montessori, salle Snoezelen, jardin : le projet pédagogique écoresponsable du Jardin de Régina à Alfortville.")
  


@section('content')

{{-- SECTION 1 : HERO --}}
<section class="relative bg-cream pt-10 pb-16 overflow-hidden">
    <div class="absolute -top-16 -left-20 w-96 h-96 rounded-full bg-mint blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute top-1/3 -right-24 w-80 h-80 rounded-full bg-peach blur-3xl pointer-events-none -z-10"></div>
    <x-confetti shape="etoile" class="top-20 left-[6%] w-7 h-7 text-sun animate-float" />
    <x-confetti shape="squiggle" class="bottom-10 left-[35%] w-9 h-9 text-primary animate-spin-slow" />

    <div class="max-w-[1240px] mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            <div class="lg:col-span-7 flex flex-col items-start gap-4">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-mint/60 text-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
                    <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold">Notre projet pédagogique</span>
                </div>
                <h1 class="font-display text-4xl md:text-5xl text-primary-deep leading-tight">
                    Un jardin pour grandir <span class="text-coral">en douceur</span>
                </h1>
                <p class="font-body text-lg text-primary-deep/80 max-w-xl leading-relaxed">
                    Pensé comme un cocon protecteur et stimulant au cœur d'Alfortville, Le Jardin de Régina propose un nouveau modèle de crèche à taille humaine, enraciné localement et respectueux de l'environnement. Chaque enfant s'épanouit à son propre rythme.
                </p>
                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <a href="#contact-visite" class="h-[52px] px-8 rounded-full bg-coral text-white font-body font-bold text-sm flex items-center gap-2 shadow-lg hover:-translate-y-0.5 hover:opacity-90 transition-all">
                        <span>Demander une visite privée</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="#visite-guidee" class="h-[52px] px-8 rounded-full bg-white text-primary font-body font-bold text-sm flex items-center justify-center shadow-sm hover:bg-mint/50 transition-all">
                        Découvrir nos espaces
                    </a>
                </div>
                <div class="pt-2 flex flex-wrap items-center gap-x-5 gap-y-2 text-primary-deep/80 font-body text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>Agrément PMI &amp; soutien CAF 94</span>
                    </div>
                    <span class="text-primary-deep/30">•</span>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-coral" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21s-7-4.35-9.5-8.5C.5 8.5 2.5 5 6 5c2 0 3.5 1.5 4 2.5.5-1 2-2.5 4-2.5 3.5 0 5.5 3.5 3.5 7.5C19 16.65 12 21 12 21z"/></svg>
                        <span>Approche bienveillante &amp; Pikler</span>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5 relative w-full flex justify-center">
                <div class="absolute -top-6 -left-6 w-full h-full rounded-[48%_52%_68%_32%/42%_39%_61%_58%] bg-mint -z-10 scale-105"></div>
                <div class="absolute -bottom-6 -right-6 w-full h-full rounded-[59%_41%_37%_63%/58%_52%_48%_42%] bg-peach -z-10 scale-105"></div>

                <div class="absolute -top-4 right-2 sm:right-6 z-20 bg-white/95 backdrop-blur-md px-4 py-2.5 rounded-full shadow-lg flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-coral animate-bounce-soft"></span>
                    <span class="font-body text-xs uppercase text-primary-deep font-extrabold tracking-wider">12 places + 2 urgences · 10 sem. à 3 ans et plus</span>
                </div>

                <div class="relative w-full max-w-[420px] aspect-[4/5] rounded-t-[180px] rounded-b-2xl overflow-hidden shadow-2xl">
                    <img src="{{ asset('images/accueil-bienveillant.webp') }}" alt="Enfants s'épanouissant au Jardin de Régina" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-primary-deep/25 via-transparent to-transparent pointer-events-none"></div>
                </div>
            </div>
        </div>
    </div>

    <x-hill-divider color="mint" />
</section>

{{-- SECTION 2 : NOS 4 PILIERS ZIG-ZAG --}}
<section class="bg-mint py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <x-confetti shape="coeur" class="top-16 right-[6%] w-7 h-7 text-coral animate-bounce-soft" />
    <x-confetti shape="rond" class="bottom-16 left-[6%] w-5 h-5 bg-white animate-float-slow" />

    <div class="max-w-[1240px] mx-auto px-6 reveal relative z-10" :class="{ 'reveal-shown': shown }">
        <div class="text-center max-w-2xl mx-auto flex flex-col items-center">
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-white shadow-sm">Fondations &amp; valeurs</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">Quatre piliers bienveillants pour semer la confiance</h2>
            <p class="font-body text-primary-deep/80 mt-2">Un cadre sécurisant et stimulant où les besoins du tout-petit sont écoutés avec respect.</p>
        </div>

        <div class="flex flex-col gap-16 mt-14">
            @foreach ([
                [
                    'n' => '01', 'titre' => 'Éveil naturel & matières vivantes',
                    'texte' => "Nous encourageons l'expérience sensorielle directe avec la nature : jouets en bois non traité, nattes de liège, coussins en lin et laine bio. Loin des écrans, l'enfant apprend à savourer la texture du réel.",
                    'tags' => ['Bois brut labellisé PEFC', 'Textiles naturels', 'Exploration tactile'],
                    'img' => 'salle-de-jeux.webp', 'alt' => 'Éveil naturel et motricité libre', 'inverse' => false, 'couleur' => 'text-primary',
                ],
                [
                    'n' => '02', 'titre' => 'Éducation bienveillante & motricité libre',
                    'texte' => "Inspirée par la pédagogie d'Emmi Pikler et Montessori, notre équipe pratique l'écoute active sans jugement. L'enfant conquiert ses mouvements par lui-même, chaque petite victoire renforce son estime.",
                    'tags' => ['Pédagogie Pikler', 'Sans transat coercitif', 'Sécurité affective'],
                    'img' => 'jeux-jouets.webp', 'alt' => 'Motricité libre et jeux', 'inverse' => true, 'couleur' => 'text-coral',
                ],
                [
                    'n' => '03', 'titre' => 'Communauté engagée & co-éducation',
                    'texte' => "Les parents sont les premiers éducateurs de leur enfant. Nous établissons un dialogue de confiance : échanges au dépôt et à la sortie, ateliers partagés et cahier de transmission détaillé.",
                    'tags' => ['Échanges quotidiens', 'Transmissions détaillées', 'Transparence totale'],
                    'img' => 'communaute.webp', 'alt' => 'Communauté et co-éducation', 'inverse' => false, 'couleur' => 'text-primary-deep',
                ],
                [
                    'n' => '04', 'titre' => 'Développement durable au quotidien',
                    'texte' => "De l'assiette à la couche, tout est pensé pour la santé du bébé et la sauvegarde de son environnement : repas faits maison et de saison, couches écologiques, zéro plastique et nettoyage sans détergent irritant.",
                    'tags' => ['Repas faits maison', 'Couches éco-certifiées', 'Zéro perturbateur'],
                    'img' => 'jardin.webp', 'alt' => 'Engagement écoresponsable et jardin', 'inverse' => true, 'couleur' => 'text-primary',
                ],
            ] as $pilier)
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-6 flex flex-col items-start gap-2 {{ $pilier['inverse'] ? 'lg:order-2' : '' }}">
                    <span class="font-display text-5xl {{ $pilier['couleur'] }}/25 leading-none">{{ $pilier['n'] }}</span>
                    <h3 class="font-display text-2xl text-primary-deep">{{ $pilier['titre'] }}</h3>
                    <p class="font-body text-primary-deep/80 leading-relaxed">{{ $pilier['texte'] }}</p>
                    <div class="flex flex-wrap gap-2 pt-2">
                        @foreach ($pilier['tags'] as $tag)
                        <span class="px-3 py-1.5 bg-white text-primary-deep rounded-full font-body text-sm font-semibold shadow-sm">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="lg:col-span-6 relative flex justify-center {{ $pilier['inverse'] ? 'lg:order-1' : '' }}">
                    @if ($pilier['img'])
                    <div class="w-full max-w-[460px] aspect-[4/3] rounded-t-[100px] rounded-b-3xl overflow-hidden shadow-xl">
                        <img loading="lazy" decoding="async" src="{{ asset('images/' . $pilier['img']) }}" alt="{{ $pilier['alt'] }}" class="w-full h-full object-cover">
                    </div>
                    @else
                    <div class="w-full max-w-[460px] aspect-[4/3] rounded-t-[100px] rounded-b-3xl overflow-hidden shadow-xl bg-peach/60 flex items-center justify-center">
                        <span class="font-body text-primary-deep/75 font-semibold text-center px-6">[PHOTO à venir : {{ $pilier['alt'] }}]</span>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SECTION 3 : NOTRE PÉDAGOGIE AU QUOTIDIEN --}}
<section class="bg-cream py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <x-confetti shape="fleur" class="top-10 left-[8%] w-8 h-8 text-coral animate-wiggle" />
    <x-confetti shape="etoile" class="bottom-14 right-[6%] w-7 h-7 text-sun animate-float" />

    <div class="max-w-[1240px] mx-auto px-6 reveal relative z-10" :class="{ 'reveal-shown': shown }">
        <div class="text-center max-w-2xl mx-auto flex flex-col items-center">
            <span class="font-body text-xs uppercase tracking-widest text-coral font-extrabold px-4 py-1 rounded-full bg-peach/60 shadow-sm">Au quotidien</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">Une approche attentive à chaque souffle d'enfance</h2>
            <p class="font-body text-primary-deep/80 mt-2">Six dimensions orchestrées pour accompagner l'épanouissement de chaque enfant.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-12">
            @foreach ([
                ['titre' => 'Accueil & écoute individualisés', 'texte' => "Un temps d'accueil personnalisé chaque matin, pour une séparation douce, sans empressement ni stress.", 'bg' => 'bg-mint'],
                ['titre' => 'Repères sécurisants & rythme', 'texte' => "Respect du rythme biologique individuel, rituels rassurants et référente dédiée pour un attachement sûr.", 'bg' => 'bg-peach'],
                ['titre' => 'Communication gestuelle « bébé signe »', 'texte' => "Signes associés à la parole dès 6-7 mois : l'enfant exprime ses besoins avant même de parler.", 'bg' => 'bg-sky'],
                ['titre' => 'Motricité libre et exploration', 'texte' => "Aménagements au sol sans entrave (modules en bois, plans inclinés, triangles Pikler).", 'bg' => 'bg-mint'],
                ['titre' => 'Approche sensorielle Snoezelen', 'texte' => "Un havre de sérénité, lumières tamisées et coussins enveloppants pour apaiser les fins de journée.", 'bg' => 'bg-peach'],
                ['titre' => 'Socialisation & autonomie', 'texte' => "Vivre ensemble au sein d'un petit groupe : partage, empathie et gestes d'autonomie au quotidien.", 'bg' => 'bg-sky'],
            ] as $i => $carte)
            <div class="bg-white rounded-3xl p-6 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all flex flex-col gap-3">
                <div class="w-12 h-12 rounded-2xl {{ $carte['bg'] }} flex items-center justify-center text-primary-deep">
                    @if ($i === 0)
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    @elseif ($i === 1)
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 7v5l3 3"/></svg>
                    @elseif ($i === 2)
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 11a4 4 0 118 0v5M8 11v6a2 2 0 002 2h4a2 2 0 002-2v-1"/></svg>
                    @elseif ($i === 3)
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="5" r="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l2-7-3-2 1-5 3 2 2-2 3 3-2 2 2 8-3-1-1-4-1 4z"/></svg>
                    @elseif ($i === 4)
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
                    @else
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="8" cy="9" r="3"/><circle cx="16" cy="9" r="3"/><path stroke-linecap="round" d="M2 20c0-3 3-5 6-5s6 2 6 5M10 20c0-3 3-5 6-5s6 2 6 5"/></svg>
                    @endif
                </div>
                <h3 class="font-display text-lg text-primary-deep">{{ $carte['titre'] }}</h3>
                <p class="font-body text-sm text-primary-deep/80">{{ $carte['texte'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SECTION 4 : NOS ENGAGEMENTS ÉCORESPONSABLES --}}
<section class="bg-mint py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <x-confetti shape="squiggle" class="top-10 right-[10%] w-9 h-9 text-primary-deep animate-spin-slow" />
    <x-confetti shape="rond" class="bottom-10 left-[10%] w-5 h-5 bg-sun animate-bounce-soft" />

    <div class="max-w-[1240px] mx-auto px-6 reveal relative z-10" :class="{ 'reveal-shown': shown }">
        <div class="text-center max-w-2xl mx-auto flex flex-col items-center">
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-white shadow-sm">Impact positif</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">Prendre soin de leurs premières années, préserver leur planète</h2>
            <p class="font-body text-primary-deep/80 mt-2">La santé des bébés est indissociable de la santé de leur environnement.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-12">
            @foreach ([
                ['titre' => 'Crèche basse consommation & air pur', 'texte' => 'Normes thermiques rigoureuses, peintures sans COV, éclairages LED chauds et renouvellement d\'air permanent.', 'bg' => 'bg-mint', 'col' => 'text-primary'],
                ['titre' => 'Tri, compostage & zéro déchet', 'texte' => 'Compostage des restes végétaux, suppression du plastique jetable, lingettes lavables et mini-potager pédagogique.', 'bg' => 'bg-peach', 'col' => 'text-coral'],
                ['titre' => 'Repas & mobilier écoresponsables', 'texte' => 'Repas faits maison et de saison. Mobilier ergonomique en bois certifié PEFC, sans vernis solvanté.', 'bg' => 'bg-mint', 'col' => 'text-primary'],
                ['titre' => 'Égalité des chances & mixité', 'texte' => 'Soutien à l\'entrepreneuriat féminin et à l\'équilibre vie pro/perso. Accueil solidaire, sans distinction.', 'bg' => 'bg-peach', 'col' => 'text-coral'],
            ] as $i => $tuile)
            <div class="bg-white rounded-3xl p-7 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl {{ $tuile['bg'] }} flex items-center justify-center {{ $tuile['col'] }} shrink-0">
                    @if ($i === 0)
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12h13M3 6h9M3 18h9M19 6l2 2-2 2M19 16l2 2-2 2"/></svg>
                    @elseif ($i === 1)
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
                    @elseif ($i === 2)
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 3v7a2 2 0 002 2 2 2 0 002-2V3M9 12v9M16 3c-1.3 1.3-2 3-2 5s.7 3.7 2 5c1.3-1.3 2-3 2-5s-.7-3.7-2-5z"/></svg>
                    @else
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="8" cy="9" r="3"/><circle cx="16" cy="9" r="3"/><path stroke-linecap="round" d="M2 20c0-3 3-5 6-5s6 2 6 5M10 20c0-3 3-5 6-5s6 2 6 5"/></svg>
                    @endif
                </div>
                <div class="flex flex-col gap-1.5">
                    <h3 class="font-display text-lg text-primary-deep">{{ $tuile['titre'] }}</h3>
                    <p class="font-body text-sm text-primary-deep/80">{{ $tuile['texte'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SECTION 5 : NOS ESPACES (GALERIE) --}}
<section id="visite-guidee" class="bg-cream py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <x-confetti shape="coeur" class="top-10 right-[8%] w-7 h-7 text-coral animate-float" />

    <div class="max-w-[1240px] mx-auto px-6 reveal relative z-10" :class="{ 'reveal-shown': shown }">
        <div class="text-center max-w-2xl mx-auto flex flex-col items-center">
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-mint/60 shadow-sm">Visite guidée</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">Un cocon lumineux conçu comme une maison chaleureuse</h2>
            <p class="font-body text-primary-deep/80 mt-2">Chaque pièce est agencée à hauteur d'enfant pour favoriser l'autonomie sereine.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-5 mt-12">
            {{-- Grande salle --}}
            <div class="md:col-span-7 bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-lg transition-all group">
                <div class="relative w-full h-72 sm:h-80 overflow-hidden">
                    <img loading="lazy" decoding="async" src="{{ asset('images/salle-de-jeux.webp') }}" alt="Grande salle de motricité et jeux en bois" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-4 py-1.5 rounded-full text-primary-deep font-body text-sm font-bold shadow-sm">Espace de vie principal</div>
                </div>
                <div class="p-6 flex flex-col gap-1">
                    <h3 class="font-display text-lg text-primary-deep">Grande salle d'éveil &amp; motricité</h3>
                    <p class="font-body text-sm text-primary-deep/80">Baignée de lumière naturelle. Un espace décloisonné où les tout-petits explorent structures en bois et jouets libres.</p>
                </div>
            </div>
            {{-- Snoezelen --}}
            <div class="md:col-span-5 bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-lg transition-all group">
                <div class="relative w-full h-72 sm:h-80 overflow-hidden group">
                    <img loading="lazy" decoding="async" src="{{ asset('images/snoezelen.webp') }}" alt="Coin sensoriel Snoezelen" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-4 py-1.5 rounded-full text-primary-deep font-body text-sm font-bold shadow-sm">Nid apaisant</div>
                </div>
                <div class="p-6 flex flex-col gap-1">
                    <h3 class="font-display text-lg text-primary-deep">Le coin sensoriel Snoezelen</h3>
                    <p class="font-body text-sm text-primary-deep/80">Lumières tièdes, nids de laine et ambiances sonores douces pour relâcher les émotions.</p>
                </div>
            </div>
            {{-- Repas --}}
            <div class="md:col-span-5 bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-lg transition-all group">
                <div class="relative w-full h-72 sm:h-80 overflow-hidden">
                    <img loading="lazy" decoding="async" src="{{ asset('images/activites-creatives.webp') }}" alt="Espace repas et découverte gustative" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-4 py-1.5 rounded-full text-primary-deep font-body text-sm font-bold shadow-sm">Goûts &amp; saveurs</div>
                </div>
                <div class="p-6 flex flex-col gap-1">
                    <h3 class="font-display text-lg text-primary-deep">L'espace repas &amp; découverte gustative</h3>
                    <p class="font-body text-sm text-primary-deep/80">Mobilier miniature en bois. Les repas deviennent un moment de plaisir et d'autonomie à la cuillère.</p>
                </div>
            </div>
            {{-- Dortoir --}}
            <div class="md:col-span-4 bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-lg transition-all group">
                <div class="relative w-full h-72 sm:h-80 overflow-hidden group">
                    <img loading="lazy" decoding="async" src="{{ asset('images/dortoir.webp') }}" alt="Dortoir aux berceaux naturels" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-4 py-1.5 rounded-full text-primary-deep font-body text-sm font-bold shadow-sm">Sommeil physiologique</div>
                </div>
                <div class="p-6 flex flex-col gap-1">
                    <h3 class="font-display text-lg text-primary-deep">Le dortoir aux berceaux naturels</h3>
                    <p class="font-body text-sm text-primary-deep/80">Lits en bois brut, linge en coton bio, pénombre apaisante pour un repos régénérant.</p>
                </div>
            </div>
            {{-- Coin nature --}}
            <div class="md:col-span-3 bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-lg transition-all flex flex-col group">
                <div class="relative w-full h-36 overflow-hidden">
                    <img loading="lazy" decoding="async" src="{{ asset('images/jardin.webp') }}" alt="Coin nature et mini-potager" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="p-6 flex flex-col justify-between flex-1">
                    <div class="flex flex-col gap-2">
                        <span class="font-body text-xs uppercase text-primary font-extrabold">Végétalisation</span>
                        <h3 class="font-display text-lg text-primary-deep">Le coin nature &amp; mini-potager</h3>
                        <p class="font-body text-sm text-primary-deep/80">Plantes suspendues, bacs d'herbes aromatiques à hauteur de mains pour découvrir les senteurs.</p>
                    </div>
                    <div class="mt-4 pt-3 inline-flex items-center gap-2 text-primary font-body text-sm font-bold">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>100% sécurisé et non toxique</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 6 : CTA CORAIL --}}
<section id="contact-visite" class="bg-coral text-white py-20 relative overflow-hidden">
    <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
    <x-confetti shape="etoile" class="top-10 left-[15%] w-7 h-7 text-white/70 animate-wiggle" />
    <x-confetti shape="fleur" class="bottom-14 right-[15%] w-6 h-6 text-white/70 animate-float" />

    <div class="max-w-[860px] mx-auto px-6 text-center flex flex-col items-center gap-4 relative z-10">
        <span class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-primary-deep/25 font-body text-xs uppercase tracking-widest font-extrabold">Une place privilégiée</span>
        <h2 class="font-display text-3xl md:text-4xl">Venez découvrir notre jardin</h2>
        <p class="font-body text-white/90 max-w-2xl">
            Rencontrez notre équipe pédagogique lors d'une visite sensorielle privée ou réservez dès maintenant l'une des places disponibles pour votre enfant.
        </p>
        <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
            <a href="/contact" class="h-[52px] px-8 rounded-full bg-white text-coral font-body font-bold text-sm flex items-center justify-center shadow-lg hover:-translate-y-0.5 transition-all">
                Demander une visite
            </a>
            <a href="/pre-inscription" class="h-[52px] px-8 rounded-full bg-transparent text-white font-body font-bold text-sm flex items-center justify-center hover:bg-white/15 transition-all shadow-[0_0_0_2px_#ffffff]">
                Pré-inscrire mon enfant
            </a>
        </div>
        <div class="flex items-center gap-2 pt-4 text-white/90 font-body text-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2l2 5-2 1a11 11 0 006 6l1-2 5 2v2a2 2 0 01-2 2h-1C9 19 3 13 3 6V5z"/></svg>
            <span>Une question ? Notre équipe vous répond au <a href="tel:{{ $etab->telephoneLien() }}" class="font-bold underline hover:text-cream">{{ $etab->telephone() }}</a> (lundi-vendredi, {{ $etab->horaires() }}).</span>
        </div>
    </div>
</section>

@endsection
