<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Support\MotDePasse;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

/**
 * Crée un compte de l'espace admin (direction), par exemple sur le serveur juste après la mise en ligne.
 *
 * Le mot de passe est demandé de façon masquée (jamais en paramètre de la commande, pour qu'il
 * n'apparaisse pas dans l'historique du terminal) et doit respecter les mêmes règles que dans l'admin.
 */
class CreerAdministrateur extends Command
{
    protected $signature = 'admin:creer';

    protected $description = 'Crée un compte administrateur pour l\'espace admin (nom, email, mot de passe)';

    public function handle(): int
    {
        $this->info('Création d\'un compte pour l\'espace admin.');

        $donnees = [
            'name' => trim((string) $this->ask('Nom affiché dans l\'admin (ex. Direction)', 'Direction')),
            'email' => strtolower(trim((string) $this->ask('Adresse email de connexion'))),
            'password' => (string) $this->secret('Mot de passe (12 caractères min., majuscule, minuscule, chiffre et symbole)'),
            'password_confirmation' => (string) $this->secret('Retapez le mot de passe'),
        ];

        $validation = Validator::make($donnees, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'password' => MotDePasse::regles(),
        ], MotDePasse::messages() + [
            'email.unique' => 'Un compte existe déjà avec cette adresse. Utilisez « Mot de passe oublié » sur la page de connexion pour en changer le mot de passe.',
            'email.email' => 'Cette adresse email n\'est pas valide.',
        ]);

        if ($validation->fails()) {
            foreach ($validation->errors()->all() as $message) {
                $this->error($message);
            }
            $this->line('Aucun compte n\'a été créé.');

            return self::FAILURE;
        }

        // Le modèle User chiffre automatiquement le mot de passe (cast "hashed").
        $utilisateur = User::create([
            'name' => $donnees['name'],
            'email' => $donnees['email'],
            'password' => $donnees['password'],
        ]);

        $this->info("Compte créé : {$utilisateur->email}. Connexion sur ".route('admin.login'));

        return self::SUCCESS;
    }
}
