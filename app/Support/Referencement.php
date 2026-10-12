<?php

namespace App\Support;

use Illuminate\Http\Request;

/**
 * Outils de référencement (SEO) partagés par le gabarit des pages publiques, le sitemap et le robots.txt.
 * Les adresses sont toujours construites à partir de APP_URL : Google ne voit ainsi qu'une seule version
 * de chaque page (pas de doublon http / https ou avec / sans "www").
 */
class Referencement
{
    /**
     * Adresse absolue d'un chemin du site, ex. "/contact" → "https://lejardinderegina.fr/contact".
     */
    public static function url(string $chemin = '/'): string
    {
        $base = rtrim((string) config('app.url'), '/');
        $chemin = '/'.ltrim($chemin, '/');

        return $chemin === '/' ? $base.'/' : $base.$chemin;
    }

    /**
     * Adresse canonique de la page demandée, sans paramètres (?...) ni ancre.
     */
    public static function urlCanonique(Request $request): string
    {
        return self::url($request->path());
    }

    /**
     * Données structurées schema.org (JSON-LD) décrivant le site et la crèche, pour Google.
     */
    public static function donneesStructurees(): array
    {
        $c = config('creche');
        $idCreche = self::url('/').'#creche';

        $creche = [
            '@type' => 'ChildCare',
            '@id' => $idCreche,
            'name' => $c['nom'],
            'description' => $c['description'],
            'url' => self::url('/'),
            'logo' => self::url('images/logo.png'),
            'image' => [
                self::url($c['image_partage']),
                self::url('images/salle-de-jeux.jpg'),
                self::url('images/jardin.jpg'),
            ],
            'telephone' => $c['telephone'],
            'email' => $c['email'],
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $c['adresse']['rue'],
                'postalCode' => $c['adresse']['code_postal'],
                'addressLocality' => $c['adresse']['ville'],
                'addressRegion' => $c['adresse']['region'],
                'addressCountry' => $c['adresse']['pays'],
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => $c['geo']['latitude'],
                'longitude' => $c['geo']['longitude'],
            ],
            'hasMap' => 'https://www.google.com/maps/search/?api=1&query='.$c['geo']['latitude'].','.$c['geo']['longitude'],
            'openingHoursSpecification' => [[
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => $c['horaires']['jours'],
                'opens' => $c['horaires']['ouverture'],
                'closes' => $c['horaires']['fermeture'],
            ]],
            'areaServed' => array_map(fn (string $ville) => ['@type' => 'City', 'name' => $ville], $c['zone']),
            'maximumAttendeeCapacity' => $c['places'],
            'audience' => [
                '@type' => 'PeopleAudience',
                'suggestedMinAge' => round($c['age_minimum_mois'] / 12, 2), // dès 10 semaines ; pas d'âge maximum (« 3 ans et plus »)
            ],
            'parentOrganization' => [
                '@type' => 'Organization',
                'name' => $c['gestionnaire'],
            ],
            'potentialAction' => [
                '@type' => 'ReserveAction',
                'name' => 'Pré-inscription en ligne',
                'target' => self::url('pre-inscription'),
            ],
        ];

        if (! empty($c['reseaux'])) {
            $creche['sameAs'] = array_values($c['reseaux']);
        }

        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'WebSite',
                    '@id' => self::url('/').'#site',
                    'url' => self::url('/'),
                    'name' => $c['nom'],
                    'inLanguage' => 'fr-FR',
                    'publisher' => ['@id' => $idCreche],
                ],
                $creche,
            ],
        ];
    }
}
