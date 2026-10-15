<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Commande « php artisan admin:creer » (création du compte de la direction sur le serveur).
 */
class CreerAdministrateurTest extends TestCase
{
    use RefreshDatabase;

    private const MOT_DE_PASSE_SOLIDE = 'Regina#Serveur2026';

    private function lancer(string $email, string $motDePasse, ?string $confirmation = null)
    {
        return $this->artisan('admin:creer')
            ->expectsQuestion('Nom affiché dans l\'admin (ex. Direction)', 'Direction')
            ->expectsQuestion('Adresse email de connexion', $email)
            ->expectsQuestion('Mot de passe (12 caractères min., majuscule, minuscule, chiffre et symbole)', $motDePasse)
            ->expectsQuestion('Retapez le mot de passe', $confirmation ?? $motDePasse);
    }

    public function test_cree_un_compte_qui_peut_se_connecter_a_l_admin(): void
    {
        $this->lancer(' Direction@JardinDeRegina.fr ', self::MOT_DE_PASSE_SOLIDE)->assertSuccessful();

        $compte = User::sole();
        $this->assertSame('direction@jardinderegina.fr', $compte->email);
        $this->assertTrue(Hash::check(self::MOT_DE_PASSE_SOLIDE, $compte->password));

        $this->post('/admin/login', ['email' => 'direction@jardinderegina.fr', 'password' => self::MOT_DE_PASSE_SOLIDE])
            ->assertRedirect(route('admin'));
    }

    public function test_refuse_un_mot_de_passe_faible_ou_mal_confirme(): void
    {
        $this->lancer('direction@jardinderegina.fr', 'motdepasse')
            ->expectsOutputToContain('au moins 12 caractères')
            ->assertFailed();

        $this->lancer('direction@jardinderegina.fr', self::MOT_DE_PASSE_SOLIDE, 'Autre#MotDePasse2026')
            ->expectsOutputToContain('La confirmation ne correspond pas')
            ->assertFailed();

        $this->assertDatabaseCount('users', 0);
    }

    public function test_refuse_une_adresse_deja_utilisee(): void
    {
        $this->administrateur(['email' => 'direction@jardinderegina.fr']);

        $this->lancer('direction@jardinderegina.fr', self::MOT_DE_PASSE_SOLIDE)
            ->expectsOutputToContain('Un compte existe déjà')
            ->assertFailed();

        $this->assertDatabaseCount('users', 1);
    }

    public function test_le_seeder_ne_cree_aucun_compte(): void
    {
        $this->seed();

        $this->assertDatabaseCount('users', 0);
    }
}
