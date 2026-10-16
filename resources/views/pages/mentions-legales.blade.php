@extends('layouts.app')

@section('title', __('Mentions légales — Le Jardin de Régina'))
@section('description', __('Mentions légales du site du Jardin de Régina, micro-crèche à Alfortville : éditeur, hébergeur, propriété intellectuelle et données personnelles.'))
  


@section('content')
<x-legal-layout
    titre="Mentions légales"
    sous-titre="Cadre légal, éditeur du site et engagements de transparence de la micro-crèche Le Jardin de Régina à Alfortville."
    doc-actif="mentions-legales"
    :brouillon="false"
    :sommaire="[
        'Éditeur & exploitation',
        'Hébergement',
        'Propriété intellectuelle',
        'Données personnelles (RGPD)',
        'Cookies & traceurs',
        'Contact & réclamations',
    ]"
>
    <x-legal-article :n="1" titre="Éditeur du site & exploitation">
        <p>{{ __('Le présent site est édité et exploité par la structure gestionnaire de l\'établissement d\'accueil du jeune enfant (EAJE)') }} <strong>{{ __('Le Jardin de Régina') }}</strong>{{ __(', micro-crèche privée écoresponsable.') }}</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-cream rounded-2xl">
            <div class="flex flex-col gap-1">
                <span class="font-body text-xs uppercase tracking-wider text-primary-deep/75 font-bold">{{ __('Dénomination') }}</span>
                <span class="font-body font-semibold text-primary-deep">{{ config('creche.societe.denomination') }} ({{ config('creche.societe.forme') }})</span>
            </div>
            <div class="flex flex-col gap-1">
                <span class="font-body text-xs uppercase tracking-wider text-primary-deep/75 font-bold">{{ __('Présidence') }}</span>
                <span class="font-body font-semibold text-primary-deep">{{ __(':societe (SAS), représentée par :representant', ['societe' => config('creche.societe.presidente'), 'representant' => $etab->representant()]) }}</span>
            </div>
            <div class="flex flex-col gap-1">
                <span class="font-body text-xs uppercase tracking-wider text-primary-deep/75 font-bold">{{ __('SIREN / SIRET') }}</span>
                <span class="font-body font-semibold text-primary-deep">{{ config('creche.societe.siren') }} / {{ config('creche.societe.siret') }}</span>
            </div>
            <div class="flex flex-col gap-1">
                <span class="font-body text-xs uppercase tracking-wider text-primary-deep/75 font-bold">{{ __('Directeur de la publication') }}</span>
                <span class="font-body font-semibold text-primary-deep">{{ __(':representant (représentant légal)', ['representant' => $etab->representant()]) }}</span>
            </div>
            <div class="flex flex-col gap-1">
                <span class="font-body text-xs uppercase tracking-wider text-primary-deep/75 font-bold">{{ __('Capital social') }}</span>
                <span class="font-body font-semibold text-primary-deep">{{ __('[Capital social à compléter]') }}</span>
            </div>
            <div class="flex flex-col gap-1 md:col-span-2">
                <span class="font-body text-xs uppercase tracking-wider text-primary-deep/75 font-bold">{{ __('Adresse de l\'établissement') }}</span>
                <span class="font-body font-semibold text-primary-deep flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-7.58 7-12A7 7 0 105 9c0 4.42 7 12 7 12z"/><circle cx="12" cy="9" r="2.3"/></svg>
                    {{ __(':adresse, France', ['adresse' => $etab->adresse()]) }}
                </span>
            </div>
            <div class="flex flex-col gap-1">
                <span class="font-body text-xs uppercase tracking-wider text-primary-deep/75 font-bold">{{ __('Téléphone') }}</span>
                <a href="tel:{{ $etab->telephoneLien() }}" class="font-body font-bold text-coral hover:underline flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2l2 5-2 1a11 11 0 006 6l1-2 5 2v2a2 2 0 01-2 2h-1C9 19 3 13 3 6V5z"/></svg>
                    {{ $etab->telephone() }}
                </a>
            </div>
            <div class="flex flex-col gap-1">
                <span class="font-body text-xs uppercase tracking-wider text-primary-deep/75 font-bold">{{ __('E-mail') }}</span>
                <a href="mailto:{{ $etab->email() }}" class="font-body font-bold text-primary hover:underline flex items-center gap-1.5 break-all">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
                    {{ $etab->email() }}
                </a>
            </div>
        </div>

        <div class="flex items-start gap-3 p-4 rounded-2xl bg-mint/40">
            <svg class="w-7 h-7 text-primary shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 3v6c0 5-3.5 7.5-7 9-3.5-1.5-7-4-7-9V6l7-3z"/></svg>
            <div>
                <div class="font-body font-bold text-primary-deep">{{ __('Agrément préfectoral & conventionnement CAF') }}</div>
                <p class="font-body text-sm text-primary-deep/80 leading-relaxed">{{ __('L\'établissement bénéficie d\'une autorisation de fonctionnement délivrée après avis conforme de la PMI du Val-de-Marne, pour une capacité de') }} <strong>{{ __('12 berceaux réguliers') }}</strong> {{ __('et') }} <strong>{{ __('2 places d\'urgence') }}</strong>{{ __('. La structure est éligible au complément de libre choix du mode de garde (CMG - PAJE) versé par la CAF.') }}</p>
            </div>
        </div>
    </x-legal-article>

    <x-legal-article :n="2" titre="Hébergement de la plateforme">
        <p>{{ __('Le site Internet Le Jardin de Régina ainsi que ses formulaires de pré-inscription sécurisés sont hébergés au sein de l\'Union Européenne, auprès de :') }}</p>
        <div class="p-4 bg-cream rounded-2xl flex flex-col sm:flex-row justify-between gap-4">
            <div class="flex flex-col gap-1">
                <span class="font-body font-bold text-primary-deep">{{ __('[Nom de l\'hébergeur à préciser lors de la mise en ligne]') }}</span>
                <span class="font-body text-sm text-primary-deep/80">{{ __('Ce choix sera confirmé au moment de la mise en ligne du site.') }}</span>
            </div>
        </div>
        <p class="flex items-center gap-2 font-body text-sm text-primary-deep/80">
            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V7a4 4 0 018 0v4"/></svg>
            {{ __('Protocole SSL/TLS chiffré garantissant la confidentialité des échanges en ligne.') }}
        </p>
    </x-legal-article>

    <x-legal-article :n="3" titre="Propriété intellectuelle & droits réservés">
        <p>{{ __('L\'ensemble de ce site relève de la législation française sur le droit d\'auteur et la propriété intellectuelle (articles L.111-1 et suivants du Code de la propriété intellectuelle).') }}</p>
        <p>{{ __('Sont notamment protégés :') }}</p>
        <ul class="grid grid-cols-1 md:grid-cols-2 gap-2 font-body text-sm">
            @foreach ([
                'L\'identité visuelle, la charte graphique et les harmonies de couleurs du site.',
                'Les photographies et captations des aménagements de la crèche.',
                'Les textes pédagogiques et descriptifs des ateliers.',
                'Le logo, les éléments graphiques et typographiques.',
            ] as $item)
            <li class="p-3 rounded-xl bg-cream flex items-start gap-2">
                <svg class="w-4 h-4 text-primary shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                <span>{{ __($item) }}</span>
            </li>
            @endforeach
        </ul>
        <p class="p-4 rounded-2xl bg-mint/40 text-sm italic">{{ __('Toute reproduction, représentation ou diffusion partielle ou totale de ces contenus, sans l\'accord préalable écrit du gestionnaire, est interdite et constituerait une contrefaçon.') }}</p>
    </x-legal-article>

    <x-legal-article :n="4" titre="Données personnelles & protection des familles (RGPD)">
        <div class="p-5 rounded-2xl bg-mint/40 flex flex-col gap-2">
            <div class="flex items-center gap-2 text-primary font-bold">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 3v6c0 5-3.5 7.5-7 9-3.5-1.5-7-4-7-9V6l7-3z"/></svg>
                <span class="font-display text-lg">{{ __('Notre engagement envers les familles') }}</span>
            </div>
            <p class="text-primary-deep">{{ __('Les informations collectées lors d\'une pré-inscription (nom, prénom, date de naissance de l\'enfant, coordonnées des parents, situation professionnelle) sont recueillies') }} <strong>{{ __('exclusivement') }}</strong> {{ __('pour instruire les dossiers de réservation de places et satisfaire aux obligations réglementaires de la PMI.') }}</p>
        </div>
        <p>{{ __('Vos données') }} <strong>{{ __('ne sont jamais vendues ni transmises à des tiers à des fins commerciales') }}</strong>{{ __('. Seules les personnes habilitées de l\'équipe et nos partenaires institutionnels (PMI, CAF du Val-de-Marne) peuvent y avoir accès.') }}</p>
        <p>{{ __('Les dossiers de pré-inscription sont conservés le temps nécessaire à leur traitement et au suivi de votre demande. Vous pouvez demander leur suppression à tout moment.') }}</p>
        <div class="p-4 rounded-2xl bg-cream flex flex-col gap-2">
            <div class="font-body font-bold text-primary-deep">{{ __('Exercer vos droits') }}</div>
            <p class="text-sm text-primary-deep/80">{{ __('Conformément au RGPD, vous disposez d\'un droit d\'accès, de rectification, de portabilité et d\'effacement de vos données.') }}</p>
            <a href="mailto:{{ $etab->email() }}?subject=Exercice%20de%20mes%20droits%20RGPD" class="self-start h-10 px-4 rounded-full bg-primary text-white font-body text-sm font-bold inline-flex items-center gap-2 mt-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
                {{ __('Exercer mes droits par e-mail') }}
            </a>
            <p class="text-xs text-primary-deep/75 italic mt-1">{{ __('Si vous estimez que vos droits ne sont pas respectés, vous pouvez saisir la') }} <strong>{{ __('CNIL') }}</strong> {{ __('(cnil.fr).') }}</p>
        </div>
    </x-legal-article>

    <x-legal-article :n="5" titre="Cookies & respect de la navigation">
        <p>{{ __('Pour assurer la sécurité et le bon fonctionnement du site, nous utilisons des traceurs strictement nécessaires. Le détail complet figure sur notre page dédiée.') }}</p>
        <a href="{{ route_langue('gestion-des-cookies') }}" class="inline-flex items-center gap-2 text-primary font-bold hover:underline w-fit">
            <span>{{ __('Voir la gestion des cookies du Jardin de Régina') }}</span>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </x-legal-article>

    <x-legal-article :n="6" titre="Contact, signalement & réclamations">
        <p>{{ __('Pour toute question juridique, réclamation ou signalement d\'un contenu erroné, la direction est joignable via :') }}</p>
        <div class="p-5 rounded-2xl bg-cream flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex flex-col gap-1">
                <span class="font-display text-lg text-primary-deep">{{ __('Secrétariat de direction') }}</span>
                <span class="font-body text-sm text-primary-deep/80">{{ __('Le Jardin de Régina · :adresse', ['adresse' => $etab->adresse()]) }}</span>
                <span class="font-body text-sm font-semibold text-primary-deep mt-1">{{ __('Réponse sous 48h ouvrées') }}</span>
            </div>
            <div class="flex flex-col sm:flex-row md:flex-col gap-2 shrink-0">
                <a href="tel:{{ $etab->telephoneLien() }}" class="h-11 px-4 rounded-full bg-white text-coral font-body text-sm font-bold flex items-center justify-center gap-2 shadow-sm">{{ $etab->telephone() }}</a>
                <a href="mailto:{{ $etab->email() }}" class="h-11 px-4 rounded-full bg-primary text-white font-body text-sm font-bold flex items-center justify-center gap-2">{{ __('Nous écrire') }}</a>
            </div>
        </div>
        <p class="p-4 rounded-2xl bg-mint/40 text-sm">
            <strong>{{ __('Médiation de la consommation :') }}</strong> {{ __('conformément à l\'article L. 612-1 du Code de la consommation, en cas de litige non résolu, vous pouvez saisir gratuitement un médiateur de la consommation agréé.') }}
        </p>
    </x-legal-article>

</x-legal-layout>
@endsection
