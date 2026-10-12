<?php

namespace Tests;

use App\Http\Middleware\ProtectionAntiSpam;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    /** Seule base que les tests ont le droit d'utiliser (elle est vidée à chaque test). */
    private const BASE_DE_TEST = 'jardin_de_regina_test';

    /** Mot de passe des comptes administrateurs créés pendant les tests. */
    protected const MOT_DE_PASSE = 'Test#MotDePasse2026';

    /**
     * Sécurité : les tests vident la base de données. Si la configuration pointait par erreur
     * vers la vraie base (ex. cache de configuration), on s'arrête AVANT toute suppression.
     */
    public function createApplication(): Application
    {
        $app = parent::createApplication();

        $connexion = $app['config']->get('database.default');
        $base = $app['config']->get("database.connections.$connexion.database");

        if ($base !== self::BASE_DE_TEST) {
            throw new RuntimeException('Tests arrêtés : ils doivent utiliser la base « '.self::BASE_DE_TEST." », pas « $base ». Lancez « php artisan config:clear » puis relancez les tests.");
        }

        return $app;
    }

    /**
     * Champs anti-spam d'un formulaire public affiché il y a 10 secondes (comme un vrai visiteur).
     */
    protected function antiSpam(): array
    {
        return [ProtectionAntiSpam::CHAMP_HEURE => encrypt(now()->subSeconds(10)->getTimestamp())];
    }

    protected function administrateur(array $attributs = []): User
    {
        return User::factory()->create($attributs + [
            'name' => 'Direction',
            'email' => 'direction@exemple.test',
            'password' => self::MOT_DE_PASSE,
        ]);
    }

    protected function connecterAdministrateur(array $attributs = []): User
    {
        $admin = $this->administrateur($attributs);
        $this->actingAs($admin);

        return $admin;
    }
}
