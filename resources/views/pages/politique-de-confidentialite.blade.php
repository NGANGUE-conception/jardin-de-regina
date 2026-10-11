@extends('layouts.app')

@section('title', 'Politique de confidentialité — Le Jardin de Régina')

@section('content')
<x-legal-layout
    titre="Politique de confidentialité"
    sous-titre="Comment Le Jardin de Régina collecte, utilise et protège les données des familles, des candidats et des visiteurs du site."
    doc-actif="confidentialite"
    :sommaire="[
        'Qui est responsable de vos données',
        'Quelles données nous collectons',
        'Pourquoi nous les collectons',
        'Combien de temps nous les conservons',
        'Qui peut y accéder',
        'Vos droits',
    ]"
>
    <x-legal-article :n="1" titre="Qui est responsable de vos données ?">
        <p>Le responsable du traitement des données collectées sur ce site est <strong>Le Jardin de Régina</strong>, micro-crèche gérée par M. Fabrice TCHOUMO, 4 B rue des Écoles, 94140 Alfortville.</p>
        <p>Pour toute question sur vos données personnelles, vous pouvez écrire à <a href="mailto:jardinderegina94@gmail.com" class="text-primary font-bold hover:underline">jardinderegina94@gmail.com</a>.</p>
    </x-legal-article>

    <x-legal-article :n="2" titre="Quelles données nous collectons">
        <p>Selon le formulaire que vous utilisez sur le site, nous collectons :</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 rounded-2xl bg-cream flex flex-col gap-1">
                <span class="font-body font-bold text-primary-deep">Pré-inscription</span>
                <span class="font-body text-sm text-primary-deep/70">Identité et date de naissance de l'enfant, coordonnées des parents, adresse, profession, employeur, situation familiale.</span>
            </div>
            <div class="p-4 rounded-2xl bg-cream flex flex-col gap-1">
                <span class="font-body font-bold text-primary-deep">Devis entreprise</span>
                <span class="font-body text-sm text-primary-deep/70">Nom de l'entreprise, secteur, coordonnées du contact RH.</span>
            </div>
            <div class="p-4 rounded-2xl bg-cream flex flex-col gap-1">
                <span class="font-body font-bold text-primary-deep">Contact</span>
                <span class="font-body text-sm text-primary-deep/70">Nom, e-mail, téléphone, objet et contenu de votre message.</span>
            </div>
            <div class="p-4 rounded-2xl bg-cream flex flex-col gap-1">
                <span class="font-body font-bold text-primary-deep">Candidature</span>
                <span class="font-body text-sm text-primary-deep/70">Identité, coordonnées, CV et lettre de motivation.</span>
            </div>
            <div class="p-4 rounded-2xl bg-cream flex flex-col gap-1 md:col-span-2">
                <span class="font-body font-bold text-primary-deep">Navigation sur le site</span>
                <span class="font-body text-sm text-primary-deep/70">Statistiques de visite anonymes : date et heure, pages consultées, site d'origine (par exemple Google), type d'appareil et de navigateur. Aucune adresse IP n'est enregistrée. Si vous envoyez un formulaire, ces informations de navigation sont associées à votre demande. Détails et refus possible sur la page <a href="{{ route('gestion-des-cookies') }}" class="text-primary font-bold hover:underline">Gestion des cookies</a>.</span>
            </div>
        </div>
        <p class="font-body text-sm text-primary-deep/70">Nous ne collectons jamais plus de données que nécessaire au traitement de votre demande.</p>
    </x-legal-article>

    <x-legal-article :n="3" titre="Pourquoi nous les collectons">
        <ul class="flex flex-col gap-2">
            @foreach ([
                'Étudier et suivre votre demande de pré-inscription ou de devis.',
                'Vous répondre lorsque vous nous contactez.',
                'Examiner votre candidature à un poste.',
                "Respecter nos obligations légales envers la PMI et la CAF.",
                'Mesurer la fréquentation du site et comprendre comment les familles nous découvrent, de façon anonyme.',
            ] as $item)
            <li class="flex items-start gap-2">
                <svg class="w-5 h-5 text-primary shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                <span>{{ $item }}</span>
            </li>
            @endforeach
        </ul>
        <p class="p-4 rounded-2xl bg-mint/40 text-sm">Vos données ne sont <strong>jamais utilisées à des fins publicitaires</strong> ni vendues à des tiers.</p>
    </x-legal-article>

    <x-legal-article :n="4" titre="Combien de temps nous les conservons">
        <div class="flex flex-col gap-3">
            @foreach ([
                ['titre' => 'Pré-inscriptions non abouties', 'duree' => '12 mois'],
                ['titre' => 'Dossiers de familles accueillies', 'duree' => "Durée de l'accueil + délai légal de conservation comptable"],
                ['titre' => 'Candidatures non retenues', 'duree' => '12 mois'],
                ['titre' => 'Messages de contact', 'duree' => '24 mois'],
                ['titre' => 'Statistiques de visite du site', 'duree' => '25 mois'],
            ] as $item)
            <div class="flex items-center justify-between p-3 rounded-xl bg-cream">
                <span class="font-body text-sm text-primary-deep">{{ $item['titre'] }}</span>
                <span class="font-body text-sm font-bold text-primary">{{ $item['duree'] }}</span>
            </div>
            @endforeach
        </div>
        <p class="font-body text-sm text-primary-deep/70">À l'issue de ces durées, vos données sont supprimées ou rendues anonymes.</p>
    </x-legal-article>

    <x-legal-article :n="5" titre="Qui peut accéder à vos données">
        <p>Seules les personnes suivantes peuvent accéder à vos données, dans la stricte limite de leurs missions :</p>
        <ul class="flex flex-col gap-2">
            @foreach ([
                "L'équipe de direction et administrative du Jardin de Régina.",
                'Les services de la PMI du Val-de-Marne, dans le cadre du contrôle des crèches.',
                'La CAF, pour le traitement des aides financières (CMG/PAJE).',
                'Notre hébergeur informatique, uniquement pour le fonctionnement technique du site.',
            ] as $item)
            <li class="flex items-start gap-2">
                <svg class="w-5 h-5 text-primary shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="3"/><path stroke-linecap="round" d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6"/></svg>
                <span>{{ $item }}</span>
            </li>
            @endforeach
        </ul>
    </x-legal-article>

    <x-legal-article :n="6" titre="Vos droits">
        <p>Conformément au Règlement Général sur la Protection des Données (RGPD), vous disposez des droits suivants sur vos données personnelles :</p>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            @foreach (['Accès', 'Rectification', 'Effacement', 'Limitation', 'Portabilité', 'Opposition'] as $droit)
            <span class="px-4 py-2 rounded-full bg-mint/50 text-primary-deep font-body text-sm font-semibold text-center">{{ $droit }}</span>
            @endforeach
        </div>
        <div class="p-4 rounded-2xl bg-cream flex flex-col gap-2 mt-2">
            <a href="mailto:jardinderegina94@gmail.com?subject=Exercice%20de%20mes%20droits%20RGPD" class="self-start h-10 px-4 rounded-full bg-primary text-white font-body text-sm font-bold inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
                Exercer mes droits par e-mail
            </a>
            <p class="text-xs text-primary-deep/60 italic">Si vos droits ne sont pas respectés, vous pouvez saisir la <strong>CNIL</strong> (cnil.fr).</p>
        </div>
    </x-legal-article>

</x-legal-layout>
@endsection
