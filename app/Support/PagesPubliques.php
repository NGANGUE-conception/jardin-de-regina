<?php

namespace App\Support;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Liste des pages publiques du site, utilisée par le sitemap.xml (pour Google) et la page « Plan du site ».
 * Toute nouvelle page publique doit être ajoutée ici pour être découverte par les moteurs de recherche.
 * (La page de confirmation de pré-inscription n'y figure pas : elle n'a pas à apparaître dans Google.)
 */
class PagesPubliques
{
    private const PAGES = [
        'La crèche' => [
            ['route' => 'accueil', 'vue' => 'accueil', 'titre' => 'Accueil', 'resume' => 'Découvrir Le Jardin de Régina en un coup d\'œil.', 'priorite' => '1.0', 'frequence' => 'weekly'],
            ['route' => 'notre-projet', 'vue' => 'notre-projet', 'titre' => 'Notre projet', 'resume' => 'Notre pédagogie, nos espaces et nos engagements écoresponsables.', 'priorite' => '0.8', 'frequence' => 'monthly'],
            ['route' => 'fonctionnement', 'vue' => 'fonctionnement', 'titre' => 'Fonctionnement', 'resume' => 'Horaires, types d\'accueil, journée type et repas.', 'priorite' => '0.8', 'frequence' => 'monthly'],
            ['route' => 'tarifs', 'vue' => 'tarifs', 'titre' => 'Tarifs et aides', 'resume' => 'Nos tarifs et les aides de la CAF (CMG / PAJE) et le crédit d\'impôt.', 'priorite' => '0.8', 'frequence' => 'monthly'],
        ],
        'Inscription et contact' => [
            ['route' => 'pre-inscription', 'vue' => 'pre-inscription', 'titre' => 'Pré-inscription', 'resume' => 'Pré-inscrire votre enfant en ligne, en quelques minutes.', 'priorite' => '0.9', 'frequence' => 'monthly'],
            ['route' => 'contact', 'vue' => 'contact', 'titre' => 'Contact et visite', 'resume' => 'Nous écrire, nous appeler ou venir visiter la crèche.', 'priorite' => '0.8', 'frequence' => 'monthly'],
        ],
        'Entreprises et recrutement' => [
            ['route' => 'berceaux-entreprise', 'vue' => 'berceaux-entreprise', 'titre' => 'Berceaux entreprise', 'resume' => 'Réserver des places en crèche pour vos salariés.', 'priorite' => '0.7', 'frequence' => 'monthly'],
            ['route' => 'recrutement', 'vue' => 'recrutement', 'titre' => 'Recrutement', 'resume' => 'Rejoindre l\'équipe du Jardin de Régina.', 'priorite' => '0.6', 'frequence' => 'monthly'],
        ],
        'Informations légales' => [
            ['route' => 'mentions-legales', 'vue' => 'mentions-legales', 'titre' => 'Mentions légales', 'resume' => 'Éditeur, hébergeur et propriété intellectuelle du site.', 'priorite' => '0.3', 'frequence' => 'yearly'],
            ['route' => 'politique-de-confidentialite', 'vue' => 'politique-de-confidentialite', 'titre' => 'Politique de confidentialité', 'resume' => 'Comment vos données sont collectées et protégées.', 'priorite' => '0.3', 'frequence' => 'yearly'],
            ['route' => 'gestion-des-cookies', 'vue' => 'gestion-des-cookies', 'titre' => 'Gestion des cookies', 'resume' => 'Les cookies utilisés et vos préférences.', 'priorite' => '0.3', 'frequence' => 'yearly'],
            ['route' => 'plan-du-site', 'vue' => 'plan-du-site', 'titre' => 'Plan du site', 'resume' => 'Toutes les pages du site.', 'priorite' => '0.3', 'frequence' => 'yearly'],
        ],
    ];

    /**
     * Pages regroupées par rubrique (pour la page « Plan du site »).
     *
     * @return array<string, array<int, array>>
     */
    public static function parRubrique(): array
    {
        return self::PAGES;
    }

    /**
     * Toutes les pages, avec leur adresse absolue et leur date de dernière modification (pour le sitemap.xml).
     */
    public static function pourSitemap(): Collection
    {
        return collect(self::PAGES)->flatten(1)->map(function (array $page) {
            $fichier = resource_path('views/pages/'.$page['vue'].'.blade.php');

            return $page + [
                'adresse' => Referencement::url(route($page['route'], absolute: false)),
                'modifiee_le' => is_file($fichier) ? Carbon::createFromTimestamp(filemtime($fichier))->toAtomString() : null,
            ];
        });
    }
}
