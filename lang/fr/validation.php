<?php

/*
|--------------------------------------------------------------------------
| Messages de validation en français
|--------------------------------------------------------------------------
| Affichés sous les champs des formulaires lorsqu'une saisie est refusée.
| Formulés pour fonctionner avec n'importe quel nom de champ (masculin,
| féminin, singulier ou pluriel) : « Merci de renseigner :attribute. »
| Les formulaires peuvent préciser leurs propres messages (méthode messages()).
*/

return [

    'accepted' => 'Merci de cocher la case : :attribute.',
    'after_or_equal' => ':Attribute doit être postérieure ou égale au :date.',
    'array' => 'La valeur de :attribute n\'est pas valide.',
    'before_or_equal' => ':Attribute doit être antérieure ou égale au :date.',
    'boolean' => 'La valeur de :attribute n\'est pas valide.',
    'confirmed' => 'La confirmation de :attribute ne correspond pas.',
    'current_password' => 'Le mot de passe est incorrect.',
    'date' => 'Merci d\'indiquer une date valide pour :attribute.',
    'date_format' => 'Merci d\'indiquer :attribute au bon format.',
    'different' => ':Attribute doit être différent de :other.',
    'email' => 'Merci d\'indiquer une adresse e-mail valide (ex. prenom.nom@exemple.fr).',
    'file' => ':Attribute doit être un fichier.',
    'image' => ':Attribute doit être une image.',
    'in' => 'La valeur choisie pour :attribute n\'est pas valide.',
    'integer' => ':Attribute doit être un nombre entier.',
    'max' => [
        'array' => ':Attribute ne peut pas contenir plus de :max éléments.',
        'file' => ':Attribute ne doit pas dépasser :max Ko.',
        'numeric' => ':Attribute ne doit pas dépasser :max.',
        'string' => ':Attribute ne doit pas dépasser :max caractères.',
    ],
    'mimes' => 'Format non accepté pour :attribute (formats acceptés : :values).',
    'mimetypes' => 'Format non accepté pour :attribute (formats acceptés : :values).',
    'min' => [
        'array' => 'Merci de sélectionner au moins :min élément(s) pour :attribute.',
        'file' => ':Attribute doit faire au moins :min Ko.',
        'numeric' => ':Attribute doit être au moins égal à :min.',
        'string' => ':Attribute doit contenir au moins :min caractères.',
    ],
    'not_in' => 'La valeur choisie pour :attribute n\'est pas valide.',
    'numeric' => ':Attribute doit être un nombre.',
    'password' => [
        'letters' => ':Attribute doit contenir au moins une lettre.',
        'mixed' => ':Attribute doit contenir au moins une majuscule et une minuscule.',
        'numbers' => ':Attribute doit contenir au moins un chiffre.',
        'symbols' => ':Attribute doit contenir au moins un symbole.',
        'uncompromised' => 'Ce mot de passe est apparu dans une fuite de données. Merci d\'en choisir un autre.',
    ],
    'regex' => 'Le format de :attribute n\'est pas valide.',
    'required' => 'Merci de renseigner :attribute.',
    'required_if' => 'Merci de renseigner :attribute.',
    'required_unless' => 'Merci de renseigner :attribute.',
    'required_with' => 'Merci de renseigner :attribute.',
    'string' => 'La valeur de :attribute n\'est pas valide.',
    'unique' => ':Attribute est déjà utilisé(e).',
    'uploaded' => 'Le fichier n\'a pas pu être envoyé : il est peut-être trop volumineux.',

    /*
    |--------------------------------------------------------------------------
    | Noms des champs
    |--------------------------------------------------------------------------
    | Remplacent ":attribute" dans les messages (ex. « Merci de renseigner votre téléphone. »).
    */
    'attributes' => [
        // Champs communs
        'email' => 'votre adresse e-mail',
        'message' => 'votre message',
        'rgpd_consent' => 'consentement au traitement des données',

        // Contact
        'fullname' => 'votre nom et prénom',
        'phone' => 'votre téléphone',
        'subject' => 'l\'objet de votre demande',

        // Devis entreprise
        'company_name' => 'le nom de l\'entreprise',
        'sector' => 'le secteur d\'activité',
        'employees_count' => 'le nombre de salariés',
        'berceaux_count' => 'le nombre de berceaux',
        'contact_name' => 'le nom du contact',
        'contact_role' => 'la fonction du contact',
        'contact_email' => 'l\'e-mail professionnel',
        'contact_tel' => 'le téléphone',

        // Candidature
        'nom' => 'votre nom',
        'prenom' => 'votre prénom',
        'tel' => 'votre téléphone',
        'poste' => 'le poste souhaité',
        'documents' => 'vos documents',
        'documents.*' => 'chaque document',

        // Pré-inscription
        'care_type' => 'le type d\'accueil',
        'care_days' => 'les jours de présence',
        'care_days.*' => 'un jour de présence',
        'special_needs' => 'les besoins spécifiques',
        'special_needs_details' => 'les précisions sur les besoins spécifiques',
        'p1_civilite' => 'votre civilité',
        'p1_fixe' => 'votre téléphone fixe',
        'p1_profession' => 'votre profession',
        'p1_employeur' => 'votre employeur',
        'p1_cp' => 'votre code postal',
        'p1_ville' => 'votre ville',
        'p1_adresse' => 'votre adresse',
        'single_parent' => 'la situation familiale',
        'p2_civilite' => 'la civilité du second parent',
        'p2_nom' => 'le nom du second parent',
        'p2_prenom' => 'le prénom du second parent',
        'p2_email' => 'l\'e-mail du second parent',
        'p2_tel' => 'le téléphone du second parent',
        'p2_profession' => 'la profession du second parent',
        'p2_employeur' => 'l\'employeur du second parent',
        'newsletter_opt_in' => 'l\'inscription aux actualités',

        // Espace admin
        'password' => 'le nouveau mot de passe',
        'du' => 'la date de début',
        'au' => 'la date de fin',
    ],

];
