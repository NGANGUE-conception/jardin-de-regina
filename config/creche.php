<?php

/*
|--------------------------------------------------------------------------
| Informations officielles de la crèche
|--------------------------------------------------------------------------
| Source unique utilisée pour le référencement (données structurées
| schema.org, aperçus de partage, plan du site). À mettre à jour ici en cas
| de changement (téléphone, horaires, capacité...).
*/

return [

    'nom' => 'Le Jardin de Régina',
    'type' => 'Micro-crèche',
    'slogan' => 'Micro-crèche écoresponsable à Alfortville',
    // Description par défaut des pages (celles qui n'en définissent pas une propre) et des données structurées.
    'description' => 'Le Jardin de Régina, micro-crèche écoresponsable à Alfortville (94) : 14 places pour les enfants de 10 semaines à 3 ans et plus, du lundi au vendredi de 8h30 à 18h30.',

    'telephone' => '+33767646184',
    'telephone_affiche' => '07 67 64 61 84',
    'email' => 'jardinderegina94@gmail.com',

    'adresse' => [
        'rue' => '4 bis rue des Écoles',
        'code_postal' => '94140',
        'ville' => 'Alfortville',
        'departement' => 'Val-de-Marne',
        'region' => 'Île-de-France',
        'pays' => 'FR',
    ],

    // Coordonnées GPS de l'adresse (Base Adresse Nationale, api-adresse.data.gouv.fr).
    'geo' => [
        'latitude' => 48.803094,
        'longitude' => 2.415488,
    ],

    'horaires' => [
        'jours' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
        'ouverture' => '08:30',
        'fermeture' => '18:30',
    ],

    'places' => 14, // 12 places régulières + 2 places d'urgence
    'age_minimum_mois' => 2, // 10 semaines (accueil jusqu'à 3 ans et plus)

    'gestionnaire' => 'TEF Group Invest',

    // Communes desservies (référencement local). Les communes voisines peuvent être ajoutées après validation de la direction.
    'zone' => ['Alfortville'],

    // Profils publics de la crèche (Google Business Profile, Facebook, Instagram...), à compléter quand ils existeront.
    'reseaux' => [],

    // Image utilisée pour les aperçus de partage (WhatsApp, Facebook, LinkedIn...).
    'image_partage' => 'images/partage.jpg',
];
