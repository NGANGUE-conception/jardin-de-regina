@extends('layouts.app')

@section('title', __('Gestion des cookies — Le Jardin de Régina'))
@section('description', __('Cookies utilisés sur le site du Jardin de Régina : cookies techniques et mesure d\'audience anonyme, sans publicité. Gérez vos préférences ici.'))
  


@section('content')
<x-legal-layout
    titre="Gestion des cookies"
    sous-titre="Ce que sont les cookies utilisés sur ce site, à quoi ils servent, et comment vous pouvez les gérer."
    doc-actif="cookies"
    :sommaire="[
        'Qu\'est-ce qu\'un cookie ?',
        'Les cookies utilisés sur ce site',
        'Cookies tiers',
        'Comment gérer vos préférences',
    ]"
>
    <x-legal-article :n="1" titre="Qu'est-ce qu'un cookie ?">
        <p>{{ __('Un cookie est un petit fichier texte déposé sur votre appareil (ordinateur, tablette, téléphone) lorsque vous visitez un site Internet. Il permet au site de se souvenir de certaines informations d\'une visite à l\'autre.') }}</p>
    </x-legal-article>

    <x-legal-article :n="2" titre="Les cookies utilisés sur ce site">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="p-4 rounded-2xl bg-cream flex flex-col gap-1">
                <div class="flex items-center gap-2 text-primary font-bold">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ __('Cookies techniques essentiels') }}</span>
                </div>
                <p class="font-body text-sm text-primary-deep/80">{{ __('Maintien de session et sécurité des formulaires (pré-inscription, contact, candidature). Ces cookies ne nécessitent pas votre consentement, car ils sont indispensables au fonctionnement du site.') }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-cream flex flex-col gap-1">
                <div class="flex items-center gap-2 text-primary font-bold">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l7 4-7 4z"/><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                    <span>{{ __('Préférences d\'affichage') }}</span>
                </div>
                <p class="font-body text-sm text-primary-deep/80">{{ __('Mémorisation de choix simples (comme un onglet déjà ouvert), uniquement dans votre navigateur, jamais transmis à nos serveurs.') }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-cream flex flex-col gap-1 sm:col-span-2">
                <div class="flex items-center gap-2 text-primary font-bold">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M3 12h18M12 3c2.5 2.7 3.8 5.7 3.8 9s-1.3 6.3-3.8 9c-2.5-2.7-3.8-5.7-3.8-9S9.5 5.7 12 3z"/></svg>
                    <span>{{ __('Langue du site') }}</span>
                </div>
                <p class="font-body text-sm text-primary-deep/80">{{ __('Le cookie') }} <code>jr_langue</code> {{ __('mémorise la langue choisie avec les boutons FR / EN du menu, pendant 1 an au maximum. Il ne contient que « fr » ou « en » et ne nécessite pas votre consentement, car c\'est vous qui le demandez en changeant de langue.') }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-cream flex flex-col gap-1 sm:col-span-2">
                <div class="flex items-center gap-2 text-primary font-bold">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 20V10m6 10V4m6 16v-7m4 7H2"/></svg>
                    <span>{{ __('Mesure d\'audience anonyme') }}</span>
                </div>
                <p class="font-body text-sm text-primary-deep/80">{{ __('Un cookie nommé') }} <code>{{ __('jr_visiteur') }}</code>{{ __(', valable 13 mois au maximum, nous permet de compter les visites du site (nombre de visiteurs, pages consultées, site d\'origine, type d\'appareil). Il contient un identifiant aléatoire qui ne permet pas de savoir qui vous êtes. Votre adresse IP n\'est pas enregistrée et ces statistiques sont supprimées au bout de 25 mois. Ce cookie est exempté de consentement selon les recommandations de la CNIL, mais vous pouvez le refuser à tout moment (voir la rubrique 4).') }}</p>
            </div>
        </div>
    </x-legal-article>

    <x-legal-article :n="3" titre="Cookies tiers">
        <p>{{ __('Le site n\'intègre') }} <strong>{{ __('aucun cookie publicitaire ni outil de mesure d\'audience tiers') }}</strong> {{ __('(pas de Google Analytics, pas de pixel Facebook). La mesure d\'audience décrite ci-dessus est réalisée par nos soins, sur notre propre serveur :') }}</p>
        <ul class="flex flex-col gap-2">
            @foreach ([
                'Les données ne sont ni transmises ni vendues à des tiers.',
                "Elles ne servent pas à vous suivre sur d'autres sites.",
                'Elles sont conformes aux recommandations de la CNIL sur la mesure d\'audience exemptée de consentement.',
            ] as $item)
            <li class="flex items-start gap-2">
                <svg class="w-5 h-5 text-primary shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                <span>{{ __($item) }}</span>
            </li>
            @endforeach
        </ul>
        <p class="p-4 rounded-2xl bg-mint/40 text-sm">{{ __('Si vous envoyez un formulaire (pré-inscription, contact, devis, candidature), la visite au cours de laquelle vous l\'avez envoyé est associée à votre demande (site d\'origine et pages consultées), afin de nous aider à comprendre comment les familles découvrent la crèche. Voir notre') }} <a href="{{ route('politique-de-confidentialite') }}" class="text-primary font-bold hover:underline">{{ __('politique de confidentialité') }}</a>.</p>
    </x-legal-article>

    <x-legal-article :n="4" titre="Comment gérer vos préférences">
        @php($statistiquesRefusees = (bool) request()->cookie(\App\Support\SuiviVisites::COOKIE_REFUS))
        <div class="p-5 rounded-2xl border-2 border-mint flex flex-col gap-3">
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <span class="font-body font-bold text-primary-deep">{{ __('Mesure d\'audience anonyme') }}</span>
                <span class="px-3 py-1 rounded-full font-body text-xs font-bold {{ $statistiquesRefusees ? 'bg-peach text-coral' : 'bg-mint text-primary' }}">
                    {{ $statistiquesRefusees ? __('Refusée : vos visites ne sont pas comptées') : __('Active : vos visites sont comptées anonymement') }}
                </span>
            </div>
            @if (session('statistiques'))
                <p class="p-3 rounded-xl bg-mint/50 font-body text-sm text-primary-deep">{{ session('statistiques') }}</p>
            @endif
            <form method="POST" action="{{ route('gestion-des-cookies.statistiques') }}">
                @csrf
                <input type="hidden" name="choix" value="{{ $statistiquesRefusees ? 'accepter' : 'refuser' }}">
                <button type="submit" class="px-5 py-2 rounded-full font-body text-sm font-bold transition-all {{ $statistiquesRefusees ? 'bg-primary text-white hover:opacity-90' : 'bg-white text-primary border border-primary/30 hover:bg-mint/30' }}">
                    {{ $statistiquesRefusees ? __('Accepter à nouveau la mesure d\'audience') : __('Refuser la mesure d\'audience') }}
                </button>
            </form>
        </div>

        <p>{{ __('Vous pouvez à tout moment configurer ou supprimer les cookies déposés sur votre appareil directement depuis les réglages de votre navigateur :') }}</p>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach (['Chrome', 'Firefox', 'Safari', 'Edge'] as $navigateur)
            <span class="px-4 py-2 rounded-full bg-cream text-primary-deep font-body text-sm font-semibold text-center">{{ $navigateur }}</span>
            @endforeach
        </div>
        <p class="font-body text-sm text-primary-deep/80">{{ __('La désactivation des cookies techniques essentiels peut empêcher le bon fonctionnement des formulaires du site (pré-inscription, contact, candidature).') }}</p>
        <div class="p-4 rounded-2xl bg-cream flex items-center gap-3">
            <svg class="w-6 h-6 text-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="3"/><path stroke-linecap="round" d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6"/></svg>
            <p class="font-body text-sm text-primary-deep">{{ __('Une question sur les cookies ? Écrivez-nous à') }} <a href="mailto:{{ $etab->email() }}" class="text-primary font-bold hover:underline">{{ $etab->email() }}</a>.</p>
        </div>
    </x-legal-article>

</x-legal-layout>
@endsection
