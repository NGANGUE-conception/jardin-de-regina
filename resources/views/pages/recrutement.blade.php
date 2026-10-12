@extends('layouts.app')

@section('title', "Recrutement petite enfance — Crèche Alfortville")
@section('description', "Professionnel·le de la petite enfance ? Rejoignez l'équipe bienveillante du Jardin de Régina, micro-crèche à Alfortville. Candidature en ligne.")

  

@section('content')
<div x-data="{
        poste: '{{ old('poste', '') }}',
        fichiers: [],
        selectRole(nom) { this.poste = nom },
        handleFiles(files) { this.fichiers = Array.from(files).map(f => f.name) }
    }">

{{-- SECTION 1 : HERO --}}
<section class="relative bg-cream pt-10 pb-16 overflow-hidden">
    <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-mint blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute top-1/2 -left-20 w-80 h-80 rounded-full bg-peach blur-3xl pointer-events-none -z-10"></div>
    <x-confetti shape="etoile" class="top-20 left-[6%] w-7 h-7 text-sun animate-float" />
    <x-confetti shape="fleur" class="bottom-10 left-[32%] w-6 h-6 text-coral animate-wiggle" />

    <div class="max-w-[1240px] mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            <div class="lg:col-span-7 flex flex-col items-start gap-4">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-mint/60 text-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
                    <span class="font-body text-xs uppercase tracking-widest font-extrabold">Rejoignez Le Jardin de Régina · Carrières</span>
                </div>
                <h1 class="font-display text-4xl md:text-5xl text-primary-deep leading-tight">
                    Épanouissez-vous au cœur d'un <span class="text-coral">cocon bienveillant</span>
                </h1>
                <p class="font-body text-lg text-primary-deep/80 max-w-xl leading-relaxed">
                    Rejoignez une crèche pas comme les autres : une micro-structure écoresponsable à taille humaine (12 berceaux + 2 d'urgence), où chaque professionnel·le dispose du temps nécessaire pour accompagner l'éveil des tout-petits.
                </p>
                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <a href="#offres" class="h-[52px] px-8 rounded-full bg-coral text-white font-body font-bold text-sm flex items-center justify-center shadow-lg hover:-translate-y-0.5 hover:opacity-90 transition-all">
                        Découvrir nos offres
                    </a>
                    <a href="#candidature" class="h-[52px] px-8 rounded-full bg-white text-primary font-body font-bold text-sm flex items-center justify-center shadow-sm hover:bg-mint/40 transition-all">
                        Candidature spontanée
                    </a>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-4 w-full">
                    @foreach ([
                        ['icon' => 'groups', 'texte' => '4 professionnelles'],
                        ['icon' => 'leaf', 'texte' => 'Repas faits maison'],
                        ['icon' => 'heart', 'texte' => 'Pikler & Snoezelen'],
                        ['icon' => 'pin', 'texte' => 'Alfortville (94140)'],
                    ] as $badge)
                    <div class="flex items-center gap-2 p-2.5 rounded-2xl bg-white shadow-sm">
                        @if ($badge['icon'] === 'groups')
                            <svg class="w-5 h-5 text-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="8" cy="9" r="3"/><circle cx="16" cy="9" r="3"/><path stroke-linecap="round" d="M2 20c0-3 3-5 6-5s6 2 6 5M10 20c0-3 3-5 6-5s6 2 6 5"/></svg>
                        @elseif ($badge['icon'] === 'leaf')
                            <svg class="w-5 h-5 text-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
                        @elseif ($badge['icon'] === 'heart')
                            <svg class="w-5 h-5 text-coral shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21s-7-4.35-9.5-8.5C.5 8.5 2.5 5 6 5c2 0 3.5 1.5 4 2.5.5-1 2-2.5 4-2.5 3.5 0 5.5 3.5 3.5 7.5C19 16.65 12 21 12 21z"/></svg>
                        @else
                            <svg class="w-5 h-5 text-coral shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-7.58 7-12A7 7 0 105 9c0 4.42 7 12 7 12z"/><circle cx="12" cy="9" r="2.3"/></svg>
                        @endif
                        <span class="font-body text-xs font-bold text-primary-deep">{{ $badge['texte'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Photo en arche --}}
            <div class="lg:col-span-5 relative flex justify-center lg:justify-end">
                <div class="absolute -top-6 -left-6 w-72 h-80 bg-mint/60 rounded-[60%_40%_70%_30%/50%_60%_40%_50%] blur-xl -z-10"></div>
                <div class="absolute -bottom-8 -right-6 w-64 h-64 bg-peach/70 rounded-[40%_60%_30%_70%/60%_40%_60%_40%] blur-lg -z-10"></div>

                <div class="relative w-full max-w-[390px] aspect-[4/5] rounded-t-[160px] rounded-b-[40px] overflow-hidden shadow-2xl bg-peach">
                    <img src="{{ asset('images/jeux-jouets.webp') }}" alt="Une éducatrice joue avec les enfants au Jardin de Régina" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-primary-deep/40 via-transparent to-transparent pointer-events-none"></div>

                    <div class="absolute bottom-5 left-4 right-4 bg-white/95 backdrop-blur-md px-4 py-3 rounded-2xl shadow-lg flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-mint flex items-center justify-center shrink-0 text-primary">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21s-7-4.35-9.5-8.5C.5 8.5 2.5 5 6 5c2 0 3.5 1.5 4 2.5.5-1 2-2.5 4-2.5 3.5 0 5.5 3.5 3.5 7.5C19 16.65 12 21 12 21z"/></svg>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="font-body text-sm font-bold text-primary-deep truncate">12 berceaux seulement</span>
                            <span class="font-body text-xs text-primary">Qualité de vie au travail &amp; sérénité</span>
                        </div>
                    </div>
                </div>

                <div class="hidden sm:flex absolute -top-4 right-8 bg-white px-4 py-2 rounded-full shadow-lg items-center gap-2">
                    <svg class="w-4 h-4 text-coral" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 7v5l3 3"/></svg>
                    <span class="font-body text-xs font-bold text-primary-deep">Rythme respecté</span>
                </div>
            </div>
        </div>
    </div>

    <x-hill-divider color="mint" />
</section>

{{-- SECTION 2 : POURQUOI NOUS REJOINDRE --}}
<section class="bg-mint py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <x-confetti shape="rond" class="top-14 right-[8%] w-5 h-5 bg-white animate-float-slow" />
    <x-confetti shape="squiggle" class="bottom-10 left-[6%] w-8 h-8 text-primary-deep animate-spin-slow" />

    <div class="max-w-[1240px] mx-auto px-6 reveal relative z-10" :class="{ 'reveal-shown': shown }">
        <div class="text-center max-w-2xl mx-auto flex flex-col items-center">
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-white/80 shadow-sm">Notre culture d'équipe</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">Pourquoi faire grandir votre carrière chez nous ?</h2>
            <p class="font-body text-primary-deep/70 mt-2">Pour prendre soin des enfants avec douceur, il faut d'abord prendre soin de celles et ceux qui les accompagnent.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-12">
            @foreach ([
                ['titre' => 'Une équipe à taille humaine', 'texte' => '4 professionnelles pour 12 enfants seulement. Finies les cadences épuisantes : ici, le temps se pose et chaque prénom compte.', 'tag' => "Taux d'encadrement privilégié", 'bg' => 'bg-white', 'icon' => 'groups'],
                ['titre' => 'Un projet pédagogique porteur de sens', 'texte' => "Motricité libre (Emmi Pikler), espace sensoriel Snoezelen, alimentation équilibrée et éveil à la nature, sans compromis.", 'tag' => 'Matériel noble & éveil sensoriel', 'bg' => 'bg-peach/60', 'icon' => 'leaf'],
                ['titre' => 'Accompagnement & expertise du réseau', 'texte' => "Soutien constant de notre coordinatrice petite enfance et de nos psychologues référentes, au sein du réseau TEF Group Invest.", 'tag' => 'Écoute & mentorat actif', 'bg' => 'bg-white', 'icon' => 'support'],
                ['titre' => 'Des temps pédagogiques réguliers', 'texte' => 'Réunions mensuelles rémunérées, journées pédagogiques, formations certifiantes (bébé signe, premiers secours) et analyse de la pratique.', 'tag' => 'Formation continue certifiée', 'bg' => 'bg-peach/60', 'icon' => 'book'],
            ] as $carte)
            <div class="{{ $carte['bg'] }} rounded-3xl p-7 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all flex flex-col justify-between">
                <div class="flex flex-col gap-3">
                    <div class="w-14 h-14 rounded-2xl bg-mint flex items-center justify-center text-primary">
                        @if ($carte['icon'] === 'groups')
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="8" cy="9" r="3"/><circle cx="16" cy="9" r="3"/><path stroke-linecap="round" d="M2 20c0-3 3-5 6-5s6 2 6 5M10 20c0-3 3-5 6-5s6 2 6 5"/></svg>
                        @elseif ($carte['icon'] === 'leaf')
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
                        @elseif ($carte['icon'] === 'support')
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="3"/><path stroke-linecap="round" d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6"/><path stroke-linecap="round" d="M19 8c0-1-.5-2-1.5-2"/></svg>
                        @else
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5A2.5 2.5 0 016.5 17H20M4 4.5A2.5 2.5 0 016.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15z"/></svg>
                        @endif
                    </div>
                    <h3 class="font-display text-xl text-primary-deep">{{ $carte['titre'] }}</h3>
                    <p class="font-body text-sm text-primary-deep/70">{{ $carte['texte'] }}</p>
                </div>
                <div class="mt-5 pt-3 flex items-center gap-2 text-primary font-body text-sm font-bold">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ $carte['tag'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SECTION 3 : NOS OFFRES --}}
<section id="offres" class="bg-cream py-20 relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true" x-init="setTimeout(() => shown = true, 1200)">
    <x-confetti shape="coeur" class="top-10 right-[6%] w-6 h-6 text-coral animate-bounce-soft" />

    <div class="max-w-[1240px] mx-auto px-6 reveal relative z-10" :class="{ 'reveal-shown': shown }">
        <div class="text-center max-w-2xl mx-auto flex flex-col items-center">
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-mint/60 shadow-sm">Opportunités ouvertes</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">Nos offres d'emploi</h2>
            <p class="font-body text-primary-deep/70 mt-2">Des opportunités à temps plein pour faire grandir nos petits explorateurs.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-12">
            @foreach ([
                [
                    'titre' => 'Éducateur·rice de Jeunes Enfants (EJE)', 'sous' => 'Pôle pédagogie & référent équipe',
                    'tags' => ['CDI / Temps plein', '35h hebdo', 'Alfortville'],
                    'missions' => 'Conception et animation du projet pédagogique, coordination de l\'équipe terrain, relation privilégiée avec les familles.',
                    'profil' => "Diplôme d'État d'EJE requis, sensibilité éprouvée à l'éco-responsabilité et à la motricité libre.",
                    'badge' => null,
                ],
                [
                    'titre' => 'Auxiliaire de Puériculture (AP)', 'sous' => 'Soins & éveil quotidien',
                    'tags' => ['CDI / Temps plein', '8h30 – 18h30', 'Alfortville'],
                    'missions' => "Accueil individualisé des bébés, soins d'hygiène et de confort bienveillants, animations d'ateliers d'éveil sensoriel.",
                    'profil' => "Diplôme d'État d'Auxiliaire de Puériculture (DEAP), douceur, rigueur et goût du travail en petit groupe.",
                    'badge' => 'Coup de cœur',
                ],
                [
                    'titre' => "Assistant·e Petite Enfance (CAP AEPE)", 'sous' => 'Accompagnement & vie de la crèche',
                    'tags' => ['CDI / Alternance', 'Temps plein', 'Alfortville'],
                    'missions' => "Participation à la vie quotidienne de la crèche, aide à la prise des repas, accompagnement aux rituels d'endormissement.",
                    'profil' => "Titulaire du CAP Petite Enfance / CAP AEPE, esprit d'équipe, enthousiasme et curiosité pédagogique.",
                    'badge' => null,
                ],
            ] as $job)
            <div class="relative flex flex-col justify-between bg-white rounded-3xl p-7 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all overflow-hidden">
                @if ($job['badge'])
                <div class="absolute top-0 right-0 bg-peach text-coral px-4 py-1 text-xs font-bold rounded-bl-2xl">{{ $job['badge'] }}</div>
                @endif
                <div>
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach ($job['tags'] as $i => $tag)
                        <span class="px-3 py-1 rounded-full font-body text-[11px] font-bold {{ $i === 0 ? 'bg-mint text-primary' : 'bg-cream text-primary-deep/70' }}">{{ $tag }}</span>
                        @endforeach
                    </div>
                    <h3 class="font-display text-xl text-primary-deep mb-1">{{ $job['titre'] }}</h3>
                    <span class="inline-block font-body text-xs text-primary font-bold mb-4">{{ $job['sous'] }}</span>
                    <div class="mb-4">
                        <p class="font-body text-sm font-bold text-primary-deep mb-1">Missions principales :</p>
                        <p class="font-body text-sm text-primary-deep/70 leading-relaxed">{{ $job['missions'] }}</p>
                    </div>
                    <div class="mb-5 p-3 rounded-2xl bg-cream">
                        <p class="font-body text-xs font-bold text-primary-deep mb-0.5">Profil recherché :</p>
                        <p class="font-body text-xs text-primary-deep/70">{{ $job['profil'] }}</p>
                    </div>
                </div>
                <a href="#candidature" @click="selectRole('{{ $job['titre'] }}')" class="w-full h-[52px] rounded-full bg-coral text-white font-body font-bold text-sm inline-flex items-center justify-center shadow-lg hover:-translate-y-0.5 hover:opacity-90 transition-all">
                    Postuler à cette offre
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SECTION 4 : CANDIDATURE --}}
<section id="candidature" class="bg-peach/40 py-20 relative overflow-hidden">
    <x-confetti shape="etoile" class="top-10 left-[6%] w-7 h-7 text-sun animate-float" />

    <div class="max-w-[1240px] mx-auto px-6 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- Formulaire --}}
            <div class="lg:col-span-8 bg-white rounded-[32px] p-6 sm:p-10 shadow-lg">
                @if (session('success'))
                <div class="flex flex-col items-center text-center gap-4 py-10">
                    <div class="w-16 h-16 rounded-full bg-mint flex items-center justify-center text-primary animate-bounce-soft">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h3 class="font-display text-2xl text-primary-deep">Merci pour votre candidature !</h3>
                    <p class="font-body text-primary-deep/70 max-w-sm">{{ session('success') }}</p>
                </div>
                @else
                <div>
                    <div class="mb-6">
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-peach text-coral font-body text-xs uppercase font-extrabold">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
                            Rejoignez l'aventure
                        </span>
                        <h2 class="font-display text-2xl text-primary-deep mt-3">Envoyez-nous votre candidature</h2>
                        <p class="font-body text-primary-deep/70 mt-2">Votre profil ne correspond pas exactement aux offres ci-dessus ? Nous étudions avec intérêt toute candidature engagée.</p>
                    </div>

                    @if ($errors->any())
                    <div class="bg-coral/10 border border-coral/30 rounded-2xl p-4 mb-4">
                        <ul class="font-body text-sm text-coral list-disc list-inside">
                            @foreach ($errors->all() as $erreur)
                            <li>{{ $erreur }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('recrutement.store') }}" enctype="multipart/form-data" class="flex flex-col gap-5">
                        @csrf
                        <x-anti-spam />
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="flex flex-col gap-1.5">
                                <label for="nom" class="font-body text-xs font-bold uppercase tracking-wider text-primary-deep">Nom *</label>
                                <input id="nom" name="nom" value="{{ old('nom') }}" type="text" required placeholder="Ex : Dupont" class="h-[52px] rounded-full bg-cream px-6 text-primary-deep placeholder:text-primary-deep/40 focus:outline-none focus:ring-2 focus:ring-primary transition-all">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label for="prenom" class="font-body text-xs font-bold uppercase tracking-wider text-primary-deep">Prénom *</label>
                                <input id="prenom" name="prenom" value="{{ old('prenom') }}" type="text" required placeholder="Ex : Camille" class="h-[52px] rounded-full bg-cream px-6 text-primary-deep placeholder:text-primary-deep/40 focus:outline-none focus:ring-2 focus:ring-primary transition-all">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="flex flex-col gap-1.5">
                                <label for="rec_email" class="font-body text-xs font-bold uppercase tracking-wider text-primary-deep">Adresse e-mail *</label>
                                <input id="rec_email" name="email" value="{{ old('email') }}" type="email" required placeholder="camille.dupont@email.fr" class="h-[52px] rounded-full bg-cream px-6 text-primary-deep placeholder:text-primary-deep/40 focus:outline-none focus:ring-2 focus:ring-primary transition-all">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label for="rec_tel" class="font-body text-xs font-bold uppercase tracking-wider text-primary-deep">Téléphone *</label>
                                <input id="rec_tel" name="tel" value="{{ old('tel') }}" type="tel" required placeholder="06 12 34 56 78" class="h-[52px] rounded-full bg-cream px-6 text-primary-deep placeholder:text-primary-deep/40 focus:outline-none focus:ring-2 focus:ring-primary transition-all">
                            </div>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="poste" class="font-body text-xs font-bold uppercase tracking-wider text-primary-deep">Poste souhaité *</label>
                            <div class="relative">
                                <select id="poste" name="poste" x-model="poste" required class="w-full h-[52px] rounded-full bg-cream pl-6 pr-14 text-primary-deep appearance-none focus:outline-none focus:ring-2 focus:ring-primary transition-all">
                                    <option disabled value="">Sélectionnez un poste...</option>
                                    <option>Éducateur·rice de Jeunes Enfants (EJE)</option>
                                    <option>Auxiliaire de Puériculture (AP)</option>
                                    <option>Assistant·e Petite Enfance (CAP AEPE)</option>
                                    <option>Direction de crèche</option>
                                    <option>Stage / Alternance</option>
                                    <option>Autre profil ou expertise</option>
                                </select>
                                <div class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-mint flex items-center justify-center text-primary pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="rec_message" class="font-body text-xs font-bold uppercase tracking-wider text-primary-deep">Votre message / motivations *</label>
                            <textarea id="rec_message" name="message" required rows="4" placeholder="Parlez-nous de vous, de votre vision de la petite enfance et de ce qui vous attire au Jardin de Régina..." class="rounded-3xl bg-cream p-5 text-primary-deep placeholder:text-primary-deep/40 focus:outline-none focus:ring-2 focus:ring-primary transition-all resize-y">{{ old('message') }}</textarea>
                        </div>

                        {{-- Zone de dépôt de fichiers --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="font-body text-xs font-bold uppercase tracking-wider text-primary-deep">Documents (CV &amp; lettre de motivation) *</label>
                            <label for="file-input" class="relative flex flex-col items-center justify-center p-8 rounded-3xl bg-mint/30 hover:bg-mint/50 cursor-pointer transition-colors text-center border-2 border-dashed border-primary/40">
                                <input id="file-input" name="documents[]" type="file" multiple required accept=".pdf,.doc,.docx" class="hidden" @change="handleFiles($event.target.files)">
                                <div class="w-12 h-12 rounded-full bg-white shadow-sm flex items-center justify-center text-primary mb-3">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0L8 8m4-4l4 4M5 20h14"/></svg>
                                </div>
                                <p class="font-body text-sm font-bold text-primary-deep" x-show="fichiers.length === 0">
                                    Glissez-déposez votre CV et lettre ici, ou <span class="text-primary underline">parcourez vos fichiers</span>
                                </p>
                                <p class="font-body text-sm font-bold text-primary" x-show="fichiers.length === 1" x-text="'Fichier sélectionné : ' + fichiers[0]"></p>
                                <p class="font-body text-sm font-bold text-primary" x-show="fichiers.length > 1" x-text="fichiers.length + ' fichiers sélectionnés'"></p>
                                <p class="font-body text-xs text-primary-deep/60 mt-1">Formats acceptés : PDF, DOC, DOCX (max 5 Mo au total)</p>
                            </label>
                        </div>

                        <label class="flex items-start gap-3 pt-1 cursor-pointer">
                            <input type="checkbox" name="rgpd_consent" value="1" required class="mt-1 w-5 h-5 rounded accent-primary cursor-pointer">
                            <span class="font-body text-xs text-primary-deep/70 leading-relaxed">J'accepte que mes données personnelles soient traitées par Le Jardin de Régina pour l'examen de ma candidature, conformément à la politique de confidentialité.</span>
                        </label>

                        <div class="pt-2">
                            <button type="submit" class="w-full sm:w-auto h-[52px] px-10 rounded-full bg-coral text-white font-body font-bold text-sm inline-flex items-center justify-center gap-2 shadow-lg hover:-translate-y-0.5 hover:opacity-90 transition-all">
                                <span>Envoyer ma candidature</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </button>
                        </div>
                    </form>
                </div>
                @endif
            </div>

            {{-- Colonne réassurance --}}
            <div class="lg:col-span-4 flex flex-col gap-6">
                <div class="bg-white rounded-3xl p-7 shadow-sm">
                    <h3 class="font-display text-lg text-primary-deep flex items-center gap-2 mb-2">
                        <svg class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Processus bienveillant</span>
                    </h3>
                    <p class="font-body text-sm text-primary-deep/70 mb-6">Un recrutement transparent, respectueux de votre temps et axé sur vos valeurs humaines.</p>
                    <div class="flex flex-col gap-5 relative border-l-2 border-mint ml-3 pl-6">
                        @foreach ([
                            ['n' => 1, 'titre' => 'Étude sous 5 jours', 'texte' => 'Chaque CV est examiné avec attention par la direction pédagogique du réseau.'],
                            ['n' => 2, 'titre' => 'Échange téléphonique convivial', 'texte' => '20 minutes pour faire connaissance et présenter notre projet.'],
                            ['n' => 3, 'titre' => 'Immersion & visite de la crèche', 'texte' => "Rencontre avec l'équipe à Alfortville pour apprécier l'atmosphère du lieu."],
                        ] as $step)
                        <div class="relative">
                            <span class="absolute -left-[31px] top-0 w-6 h-6 rounded-full bg-primary text-white text-xs font-bold flex items-center justify-center">{{ $step['n'] }}</span>
                            <h4 class="font-body text-sm font-bold text-primary-deep">{{ $step['titre'] }}</h4>
                            <p class="font-body text-xs text-primary-deep/70 mt-1">{{ $step['texte'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Témoignage --}}
                <div class="bg-peach/60 rounded-3xl p-6 shadow-sm relative overflow-hidden">
                    <span class="absolute -bottom-3 -right-1 text-coral/20 text-8xl font-display pointer-events-none">”</span>
                    <p class="font-display text-sm italic text-primary-deep mb-4 leading-relaxed relative z-10">« Avoir du temps pour chaque enfant, travailler avec du matériel en bois sain et évoluer dans une équipe soudée où l'humain prime : c'est tout ce dont je rêvais. »</p>
                    <div class="flex items-center gap-3 relative z-10">
                        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-coral font-bold text-sm shadow-sm">MD</div>
                        <div>
                            <p class="font-body text-xs font-bold text-primary-deep">Marine D.</p>
                            <p class="font-body text-[11px] text-coral">Auxiliaire de Puériculture au Jardin</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 rounded-3xl bg-mint/40 flex items-center gap-4">
                    <svg class="w-8 h-8 text-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
                    <div class="flex flex-col">
                        <span class="font-body text-xs font-bold text-primary-deep">Une question préalable ?</span>
                        <a href="mailto:jardinderegina94@gmail.com" class="font-body text-xs text-primary hover:underline font-bold break-all">jardinderegina94@gmail.com</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</div>
@endsection
