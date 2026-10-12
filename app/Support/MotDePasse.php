<?php

namespace App\Support;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

/**
 * Règles et changement du mot de passe administrateur, communs à :
 *  - Paramètres → Compte administrateur (changement volontaire) ;
 *  - « Mot de passe oublié » (réinitialisation par email).
 */
class MotDePasse
{
    /**
     * Règles de validation du champ "password" (avec sa confirmation "password_confirmation").
     */
    public static function regles(?User $utilisateur = null): array
    {
        return [
            'required',
            'confirmed',
            Password::min(12)->mixedCase()->numbers()->symbols(),
            function (string $attribut, mixed $valeur, Closure $echec) use ($utilisateur) {
                if ($utilisateur && is_string($valeur) && Hash::check($valeur, $utilisateur->password)) {
                    $echec("Le nouveau mot de passe doit être différent de l'actuel.");
                }
            },
        ];
    }

    public static function messages(): array
    {
        return [
            'password.required' => 'Merci de saisir un nouveau mot de passe.',
            'password.confirmed' => 'La confirmation ne correspond pas au nouveau mot de passe.',
            'password.min' => 'Le nouveau mot de passe doit contenir au moins 12 caractères.',
            'password.mixed' => 'Le nouveau mot de passe doit contenir au moins une majuscule et une minuscule.',
            'password.numbers' => 'Le nouveau mot de passe doit contenir au moins un chiffre.',
            'password.symbols' => 'Le nouveau mot de passe doit contenir au moins un symbole (ex. ! ? @ # %).',
        ];
    }

    /**
     * Enregistre le nouveau mot de passe et déconnecte les autres appareils.
     *
     * @param  string|null  $sessionAConserver  identifiant de la session à garder ouverte (l'appareil utilisé), ou null pour toutes les fermer
     */
    public static function changer(User $utilisateur, string $nouveau, ?string $sessionAConserver = null): void
    {
        // Le modèle User chiffre automatiquement le mot de passe (cast "hashed").
        // Le nouveau jeton "se souvenir de moi" invalide les connexions mémorisées sur les autres appareils.
        $utilisateur->forceFill([
            'password' => $nouveau,
            'remember_token' => Str::random(60),
        ])->save();

        // Ferme les sessions ouvertes sur les autres appareils (sessions stockées en base).
        if (config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))
                ->where('user_id', $utilisateur->getAuthIdentifier())
                ->when($sessionAConserver, fn ($requete) => $requete->where('id', '!=', $sessionAConserver))
                ->delete();
        }
    }
}
