@extends('layouts.app')

@section('title', "Contact et visite — Le Jardin de Régina, Alfortville")
  @section('description', "Une question ou envie de visiter la crèche ? Le Jardin de Régina, {$etab->adresse()}. Appelez le {$etab->telephone()} ou écrivez-nous.")
  


@section('content')

{{-- SECTION 1 : HERO --}}
<section class="relative bg-cream pt-10 pb-10 overflow-hidden">
    <div class="absolute -top-24 right-1/4 w-96 h-96 rounded-full bg-peach blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute top-12 left-10 w-80 h-80 rounded-full bg-mint blur-3xl pointer-events-none -z-10"></div>
    <x-confetti shape="etoile" class="top-16 left-[8%] w-7 h-7 text-sun animate-float" />
    <x-confetti shape="coeur" class="bottom-10 right-[10%] w-6 h-6 text-coral animate-bounce-soft" />

    <div class="max-w-[860px] mx-auto px-6 text-center relative z-10">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white shadow-sm text-primary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
            <span class="font-body text-xs uppercase tracking-widest font-extrabold">Contact &amp; visites</span>
        </div>
        <h1 class="font-display text-4xl md:text-5xl text-primary-deep mt-4">Parlons de votre enfant</h1>
        <p class="font-body text-lg text-primary-deep/80 mt-4 max-w-xl mx-auto leading-relaxed">
            Une question sur notre projet d'accueil, nos disponibilités, ou envie de visiter notre cocon végétal ? Écrivez-nous ou échangeons de vive voix.
        </p>
        <div class="flex flex-wrap items-center justify-center gap-3 mt-6">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white shadow-sm font-body text-sm font-semibold text-primary-deep">
                <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 7v5l3 3"/></svg>
                Réponse sous 48h ouvrées
            </span>
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white shadow-sm font-body text-sm font-semibold text-primary-deep">
                <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 21V8a2 2 0 012-2h12a2 2 0 012 2v13M9 21v-6h6v6"/></svg>
                Visites privées sur rendez-vous
            </span>
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white shadow-sm font-body text-sm font-semibold text-primary-deep">
                <svg class="w-4 h-4 text-coral" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-7.58 7-12A7 7 0 105 9c0 4.42 7 12 7 12z"/><circle cx="12" cy="9" r="2.3"/></svg>
                {{ $etab->ville() }} ({{ $etab->codePostal() }})
            </span>
        </div>
    </div>
</section>

{{-- SECTION 2 : FORMULAIRE + INFOS --}}
<section class="bg-cream pb-16">
    <div class="max-w-[1240px] mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- Formulaire --}}
            <div id="contact-form" class="lg:col-span-7 bg-white rounded-[32px] p-6 md:p-8 shadow-lg">
                @if (session('success'))
                <div class="flex flex-col items-center text-center gap-4 py-10">
                    <div class="w-16 h-16 rounded-full bg-mint flex items-center justify-center text-primary animate-bounce-soft">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h3 class="font-display text-2xl text-primary-deep">Merci infiniment !</h3>
                    <p class="font-body text-primary-deep/80 max-w-sm">{{ session('success') }}</p>
                </div>
                @else
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <span class="font-body text-xs uppercase tracking-widest text-coral font-extrabold">Formulaire direct</span>
                            <h2 class="font-display text-2xl text-primary-deep mt-1">Envoyez-nous un message</h2>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-mint flex items-center justify-center text-primary shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
                        </div>
                    </div>
                    <p class="font-body text-primary-deep/80 mb-6">Remplissez ce formulaire et notre équipe reviendra vers vous avec bienveillance et précision.</p>

                    <x-formulaire.resume-erreurs :champs="['fullname' => 'fullname', 'email' => 'email', 'phone' => 'phone', 'subject' => 'subject', 'message' => 'message', 'rgpd_consent' => 'contact-rgpd']" />

                    <form method="POST" action="{{ route('contact.store') }}" class="flex flex-col gap-4">
                        @csrf
                        <x-anti-spam />
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex flex-col gap-1.5">
                                <label for="fullname" class="font-body text-sm font-bold text-primary-deep">Nom &amp; prénom *</label>
                                <input id="fullname" name="fullname" value="{{ old('fullname') }}" type="text" required placeholder="Claire &amp; Marc Laurent" class="h-[50px] px-5 rounded-2xl bg-cream text-primary-deep placeholder:text-primary-deep/40 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all @error('fullname') ring-2 ring-coral @enderror" @error('fullname') aria-invalid="true" aria-describedby="fullname-erreur" @enderror>
                                <x-formulaire.erreur champ="fullname" id="fullname" />
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label for="email" class="font-body text-sm font-bold text-primary-deep">Adresse e-mail *</label>
                                <input id="email" name="email" value="{{ old('email') }}" type="email" required placeholder="parent@exemple.fr" class="h-[50px] px-5 rounded-2xl bg-cream text-primary-deep placeholder:text-primary-deep/40 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all @error('email') ring-2 ring-coral @enderror" @error('email') aria-invalid="true" aria-describedby="email-erreur" @enderror>
                                <x-formulaire.erreur champ="email" id="email" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex flex-col gap-1.5">
                                <label for="phone" class="font-body text-sm font-bold text-primary-deep">Téléphone *</label>
                                <input id="phone" name="phone" value="{{ old('phone') }}" type="tel" required placeholder="06 12 34 56 78" class="h-[50px] px-5 rounded-2xl bg-cream text-primary-deep placeholder:text-primary-deep/40 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all @error('phone') ring-2 ring-coral @enderror" @error('phone') aria-invalid="true" aria-describedby="phone-erreur" @enderror>
                                <x-formulaire.erreur champ="phone" id="phone" />
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label for="subject" class="font-body text-sm font-bold text-primary-deep">Objet de votre demande *</label>
                                <div class="relative">
                                    <select id="subject" name="subject" required class="w-full h-[50px] pl-5 pr-14 rounded-2xl bg-cream text-primary-deep shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all appearance-none @error('subject') ring-2 ring-coral @enderror" @error('subject') aria-invalid="true" aria-describedby="subject-erreur" @enderror>
                                        <option disabled {{ old('subject') ? '' : 'selected' }} value="">Sélectionnez un sujet</option>
                                        @foreach (['Demander une visite privée', "Demande d'informations pédagogiques", 'Renseignement sur les places disponibles', 'Autre question ou partenariat'] as $sujet)
                                        <option {{ old('subject') === $sujet ? 'selected' : '' }}>{{ $sujet }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-mint flex items-center justify-center text-primary pointer-events-none">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                                <x-formulaire.erreur champ="subject" id="subject" />
                            </div>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="message" class="font-body text-sm font-bold text-primary-deep">Votre message *</label>
                            <textarea id="message" name="message" required rows="4" placeholder="Précisez l'âge de votre enfant, vos souhaits d'accueil (jours, horaires, date d'entrée souhaitée)..." class="p-4 rounded-2xl bg-cream text-primary-deep placeholder:text-primary-deep/40 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all resize-none @error('message') ring-2 ring-coral @enderror" @error('message') aria-invalid="true" aria-describedby="message-erreur" @enderror>{{ old('message') }}</textarea>
                            <x-formulaire.erreur champ="message" id="message" />
                        </div>
                        <label class="flex items-start gap-3 cursor-pointer pt-1">
                            <input id="contact-rgpd" type="checkbox" name="rgpd_consent" value="1" {{ old('rgpd_consent') ? 'checked' : '' }} required class="mt-1 h-5 w-5 rounded text-primary accent-primary cursor-pointer @error('rgpd_consent') outline outline-2 outline-offset-2 outline-coral @enderror" @error('rgpd_consent') aria-invalid="true" aria-describedby="contact-rgpd-erreur" @enderror>
                            <span class="font-body text-sm text-primary-deep/80">J'accepte que mes données soient utilisées pour traiter ma demande, conformément à la politique de confidentialité.</span>
                        </label>
                        <x-formulaire.erreur champ="rgpd_consent" id="contact-rgpd" />
                        <div class="pt-2">
                            <button type="submit" class="h-[52px] px-8 rounded-full bg-coral text-white font-body font-bold text-sm inline-flex items-center justify-center gap-2 shadow-lg hover:-translate-y-0.5 hover:opacity-90 transition-all">
                                <span>Envoyer mon message</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </button>
                        </div>
                    </form>
                </div>
                @endif
            </div>

            {{-- Colonne infos --}}
            <div class="lg:col-span-5 flex flex-col gap-6">
                <div class="bg-mint/40 rounded-[32px] p-6 shadow-sm">
                    <div class="flex items-center gap-2 text-primary mb-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 4-6 8-6 11a6 6 0 0012 0c0-3-2-7-6-11z"/></svg>
                        <span class="font-body text-xs uppercase tracking-widest font-extrabold">Direct &amp; chaleureux</span>
                    </div>
                    <h3 class="font-display text-xl text-primary-deep mb-4">Nous joindre directement</h3>

                    <div class="flex flex-col gap-3">
                        @foreach ([
                            ['icon' => 'pin', 'titre' => 'Adresse de la crèche', 'val' => $etab->adresse(), 'sous' => 'À deux pas du centre et des berges aménagées', 'href' => null],
                            ['icon' => 'phone', 'titre' => 'Téléphone direct', 'val' => $etab->telephone(), 'sous' => 'Du lundi au vendredi '.$etab->horairesTexte(), 'href' => 'tel:'.$etab->telephoneLien()],
                            ['icon' => 'mail', 'titre' => 'Courrier électronique', 'val' => $etab->email(), 'sous' => "Accueil des parents & dossiers d'inscription", 'href' => 'mailto:'.$etab->email()],
                            ['icon' => 'clock', 'titre' => "Horaires d'accueil", 'val' => 'Lundi – vendredi : '.$etab->horaires(), 'sous' => 'Fermeture les week-ends et jours fériés', 'href' => null],
                        ] as $item)
                        <div class="flex items-start gap-3 p-3 rounded-2xl bg-white hover:shadow-sm transition-all">
                            <div class="w-10 h-10 rounded-full bg-mint flex items-center justify-center text-primary shrink-0 mt-0.5">
                                @if ($item['icon'] === 'pin')
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-7.58 7-12A7 7 0 105 9c0 4.42 7 12 7 12z"/><circle cx="12" cy="9" r="2.3"/></svg>
                                @elseif ($item['icon'] === 'phone')
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2l2 5-2 1a11 11 0 006 6l1-2 5 2v2a2 2 0 01-2 2h-1C9 19 3 13 3 6V5z"/></svg>
                                @elseif ($item['icon'] === 'mail')
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 7v5l3 3"/></svg>
                                @endif
                            </div>
                            <div class="flex flex-col">
                                <span class="font-body text-xs text-primary-deep/75">{{ $item['titre'] }}</span>
                                @if ($item['href'])
                                    <a href="{{ $item['href'] }}" class="font-body font-bold text-primary hover:underline">{{ $item['val'] }}</a>
                                @else
                                    <span class="font-body font-bold text-primary-deep">{{ $item['val'] }}</span>
                                @endif
                                <span class="font-body text-xs text-primary-deep/75">{{ $item['sous'] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Visite sereine --}}
                <div class="bg-peach/50 rounded-[32px] p-5 shadow-sm flex flex-col sm:flex-row gap-4 items-center">
                    <div class="w-24 h-28 rounded-t-full rounded-b-2xl overflow-hidden shrink-0 shadow-sm">
                        <img loading="lazy" decoding="async" src="{{ asset('images/salle-de-jeux.webp') }}" alt="Intérieur chaleureux du Jardin de Régina" class="w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col">
                        <span class="font-body text-xs uppercase text-coral font-extrabold">Respect du sommeil</span>
                        <h4 class="font-display text-lg text-primary-deep mt-0.5">Visites en toute sérénité</h4>
                        <p class="font-body text-sm text-primary-deep/80 mt-1">Afin de préserver le calme et les siestes, les visites sont programmées sur rendez-vous, en dehors des temps de repos.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 3 : CARTE ET ACCÈS --}}
<section class="bg-mint pt-16 pb-20 relative overflow-hidden">
    <x-confetti shape="rond" class="top-14 left-[6%] w-5 h-5 bg-white animate-float-slow" />

    <div class="max-w-[1240px] mx-auto px-6 relative z-10">
        <div class="text-center max-w-2xl mx-auto flex flex-col items-center mb-12">
            <span class="font-body text-xs uppercase tracking-widest text-primary font-extrabold px-4 py-1 rounded-full bg-white shadow-sm">Localisation &amp; transports</span>
            <h2 class="font-display text-3xl md:text-4xl text-primary-deep mt-3">Comment venir au Jardin de Régina ?</h2>
            <p class="font-body text-primary-deep/80 mt-2">Idéalement située au cœur d'Alfortville, accessible depuis Paris, Créteil et les communes voisines.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            {{-- Carte stylisée --}}
            <div class="lg:col-span-7 bg-white rounded-3xl p-3 shadow-lg overflow-hidden">
                <div class="relative w-full h-[360px] md:h-[420px] rounded-2xl overflow-hidden bg-sky/40 flex items-center justify-center">
                    <svg class="absolute inset-0 w-full h-full" fill="none" preserveAspectRatio="xMidYMid slice" viewBox="0 0 700 450">
                        <path d="M-50,80 Q200,130 320,240 T500,480" fill="none" stroke="#A9D8EA" stroke-linecap="round" stroke-width="65"/>
                        <path d="M-50,80 Q200,130 320,240 T500,480" fill="none" stroke="#DCEEFA" stroke-linecap="round" stroke-width="40"/>
                        <path d="M120,40 C170,40 230,120 180,180 C130,220 80,180 80,110 Z" fill="#E3F2F1"/>
                        <path d="M420,260 C500,260 550,330 510,390 C450,440 380,380 400,310 Z" fill="#E3F2F1"/>
                        <line stroke="#ffffff" stroke-width="12" x1="0" x2="700" y1="210" y2="190"/>
                        <line stroke="#ffffff" stroke-width="10" x1="260" x2="480" y1="0" y2="450"/>
                        <line stroke="#ffffff" stroke-width="8" x1="150" x2="650" y1="450" y2="80"/>
                        <line stroke="#ffffff" stroke-width="8" x1="380" x2="220" y1="0" y2="450"/>
                        <text fill="#12414D" font-family="Nunito" font-size="11" font-weight="600" x="310" y="180">Rue des Écoles</text>
                        <text fill="#1B8FA6" font-family="Nunito" font-size="11" font-style="italic" opacity="0.7" x="140" y="270">La Seine</text>
                        <g transform="translate(370, 185)">
                            <circle cx="0" cy="0" fill="#E2574C" opacity="0.3" r="26"/>
                            <circle cx="0" cy="0" fill="#E2574C" r="14"/>
                            <circle cx="0" cy="0" fill="#ffffff" r="5"/>
                            <rect fill="#12414D" height="34" rx="17" width="170" x="-85" y="-55"/>
                            <text fill="#ffffff" font-family="Nunito" font-size="12" font-weight="700" text-anchor="middle" x="0" y="-33">Le Jardin de Régina</text>
                            <polygon fill="#12414D" points="-6,-21 6,-21 0,-15"/>
                        </g>
                    </svg>
                    <div class="absolute bottom-4 right-4 z-10">
                        <a href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode($etab->adresse()) }}" target="_blank" rel="noopener noreferrer" class="px-5 py-2.5 rounded-full bg-white text-primary font-body text-sm font-bold inline-flex items-center gap-2 shadow-md hover:bg-mint/40 transition-all">
                            <span>Ouvrir dans Google Maps</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                    <div class="absolute top-4 left-4 z-10 bg-white/90 backdrop-blur-md px-3.5 py-1.5 rounded-full text-xs font-bold text-primary-deep flex items-center gap-1.5 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-coral animate-bounce-soft"></span>
                        <span>{{ $etab->adresseCourte() }}</span>
                    </div>
                </div>
                <div class="mt-3 flex items-center justify-between px-2 text-primary-deep/75 font-body text-sm">
                    <span>Coordonnées GPS : 48.8052° N, 2.4208° E</span>
                    <span class="text-primary font-semibold">Micro-crèche agréée PMI</span>
                </div>
            </div>

            {{-- Accès & transports --}}
            <div class="lg:col-span-5 flex flex-col gap-4">
                <div class="flex items-center gap-2">
                    <svg class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="3"/><path stroke-linecap="round" d="M8 20l1-3M16 20l-1-3"/><circle cx="9" cy="14" r="1" fill="currentColor"/><circle cx="15" cy="14" r="1" fill="currentColor"/></svg>
                    <h3 class="font-display text-xl text-primary-deep">Accès &amp; transports</h3>
                </div>

                <div class="p-4 rounded-2xl bg-white shadow-sm flex items-start gap-4">
                    <div class="w-11 h-11 rounded-full bg-sky flex items-center justify-center shrink-0 font-body font-extrabold text-xs text-primary-deep">RER D</div>
                    <div class="flex flex-col">
                        <span class="font-body font-bold text-primary-deep">Gare Maisons-Alfort / Alfortville</span>
                        <p class="font-body text-sm text-primary-deep/80 mt-0.5">À 10 min de marche paisible. Liaison directe vers <strong>Paris Gare de Lyon en 6 minutes</strong>.</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white shadow-sm flex items-start gap-4">
                    <div class="w-11 h-11 rounded-full bg-peach flex items-center justify-center shrink-0 font-body font-extrabold text-xs text-coral">M 8</div>
                    <div class="flex flex-col">
                        <span class="font-body font-bold text-primary-deep">École Vétérinaire de Maisons-Alfort</span>
                        <p class="font-body text-sm text-primary-deep/80 mt-0.5">Puis bus 103 direct, ou 12 minutes de promenade le long des berges piétonnes.</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white shadow-sm flex items-start gap-4">
                    <div class="w-11 h-11 rounded-full bg-cream flex items-center justify-center shrink-0 text-primary-deep">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="8" width="18" height="9" rx="2"/><circle cx="7" cy="19" r="1.5"/><circle cx="17" cy="19" r="1.5"/></svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-body font-bold text-primary-deep">Lignes de bus RATP</span>
                        <div class="flex flex-wrap gap-1.5 my-1.5">
                            @foreach (['103', '125', '172', '217', '325'] as $ligne)
                            <span class="px-2 py-0.5 rounded-full bg-cream text-primary-deep font-body font-extrabold text-xs">{{ $ligne }}</span>
                            @endforeach
                        </div>
                        <p class="font-body text-sm text-primary-deep/80">Arrêts <strong>Mairie d'Alfortville</strong> et <strong>Rue des Écoles</strong> à moins de 3 minutes à pied.</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white shadow-sm flex items-start gap-4">
                    <div class="w-11 h-11 rounded-full bg-mint flex items-center justify-center shrink-0 text-primary">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="6" cy="17" r="3"/><circle cx="18" cy="17" r="3"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 17h6l-3-9-4 5h8"/></svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-body font-bold text-primary-deep">Mobilité douce &amp; dépose-minute</span>
                        <p class="font-body text-sm text-primary-deep/80 mt-0.5">Station Vélib' à 150 mètres. Emplacements réservés dépose-minute devant l'entrée.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 4 : CTA --}}
<section class="bg-cream py-16">
    <div class="max-w-[1240px] mx-auto px-6">
        <div class="relative rounded-[40px] bg-gradient-to-br from-primary via-primary to-primary-deep text-white p-8 md:p-12 overflow-hidden shadow-2xl">
            <div class="absolute -right-16 -bottom-16 w-80 h-80 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
            <x-confetti shape="fleur" class="top-6 right-[15%] w-8 h-8 text-white/70 animate-float" />
            <div class="max-w-2xl relative z-10">
                <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white/20 backdrop-blur-sm font-body text-xs uppercase tracking-widest font-extrabold mb-3">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21s-7-4.35-9.5-8.5C.5 8.5 2.5 5 6 5c2 0 3.5 1.5 4 2.5.5-1 2-2.5 4-2.5 3.5 0 5.5 3.5 3.5 7.5C19 16.65 12 21 12 21z"/></svg>
                    Visite personnalisée
                </span>
                <h2 class="font-display text-3xl md:text-4xl">Venez découvrir la crèche : demandez une visite</h2>
                <p class="font-body text-white/90 mt-3">Rien ne remplace la sensation d'un lieu pensé pour le bien-être de votre bébé. Venez ressentir l'atmosphère chaleureuse de notre cocon d'éveil.</p>
                <div class="flex flex-wrap items-center gap-4 mt-8">
                    <a href="#contact-form" onclick="document.querySelector('#fullname')?.focus()" class="h-[52px] px-8 rounded-full bg-white text-primary-deep font-body font-bold text-sm inline-flex items-center gap-2 shadow-md hover:-translate-y-0.5 transition-all">
                        <span>Demander une visite privée</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg>
                    </a>
                    <a href="tel:{{ $etab->telephoneLien() }}" class="h-[52px] px-7 rounded-full bg-transparent text-white font-body font-bold text-sm inline-flex items-center gap-2 border border-white/40 hover:bg-white/10 transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2l2 5-2 1a11 11 0 006 6l1-2 5 2v2a2 2 0 01-2 2h-1C9 19 3 13 3 6V5z"/></svg>
                        <span>Appeler le {{ $etab->telephone() }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
