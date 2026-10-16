<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /*
     Volontairement vide : aucun compte ni donnée de démonstration n'est créé automatiquement.
    
     (Le modèle de départ de Laravel creait un compte « test@example.com » / « password » :
     lancé par erreur sur le serveur, il aurait ouvert l'espace admin à n'importe qui.)
     Le compte de la direction se crée avec : php artisan admin:creer
    */
    public function run(): void
    {
        //
    }
}
