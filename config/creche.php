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
    'representant' => 'M. Fabrice TCHOUMO',

    // Date d'ouverture de la crèche (affichée tant qu'elle n'est pas passée).
    'ouverture_le' => '2026-10-30',

    // Identité légale (registre national des entreprises, annuaire-entreprises.data.gouv.fr, consulté le 15/10/2026).
    'societe' => [
        'denomination' => 'LE JARDIN DE REGINA',
        'forme' => 'SAS',
        'siren' => '104 174 685',
        'siret' => '104 174 685 00016',
        'presidente' => 'TEF GROUP INVEST',
    ],

    /*
     * Tarification PAJE : un tarif horaire unique pour toutes les familles. Il doit rester inférieur ou égal
     * à 10 € de l'heure pour que les familles puissent toucher le complément de mode de garde (CMG) de la CAF.
     */
    'tarif_horaire' => 10.00,

    /*
     * CMG « structure » 2026 versé par la CAF (couple, enfant de moins de 3 ans), selon les revenus annuels du foyer.
     * Montants indicatifs : la CAF les revalorise chaque année. Parent isolé : +40 %. Enfant de 3 à 6 ans : moitié.
     */
    'cmg' => [
        ['revenus_jusqu_a' => 24333, 'montant' => 992.12],
        ['revenus_jusqu_a' => 54075, 'montant' => 855.25],
        ['revenus_jusqu_a' => null, 'montant' => 718.41],
    ],

    // Crédit d'impôt pour frais de garde : 50 % des dépenses restant à charge, plafonnées à 3 500 € par an et par enfant.
    'credit_impot' => ['taux' => 0.5, 'plafond_depenses' => 3500],

    // Communes desservies (référencement local). Les communes voisines peuvent être ajoutées après validation de la direction.
    'zone' => ['Alfortville'],

    // Profils publics de la crèche (Google Business Profile, Facebook, Instagram...), à compléter quand ils existeront.
    'reseaux' => [],

    // Image utilisée pour les aperçus de partage (WhatsApp, Facebook, LinkedIn...).
    'image_partage' => 'images/partage.jpg',
];
