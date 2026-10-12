<?php

namespace Tests\Feature;

use App\Mail\ReinitialisationMotDePasse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Connexion à l'espace admin et « Mot de passe oublié ».
 */
class AuthentificationAdminTest extends TestCase
{
    use RefreshDatabase;

    public static function pagesAdmin(): array
    {
        return [
            ['/admin'], ['/admin/pre-inscriptions'], ['/admin/journal-des-visites'], ['/admin/journal-des-visites/site'],
            ['/admin/demandes-entreprises'], ['/admin/candidatures'], ['/admin/messages'], ['/admin/parametres'],
            ['/admin/pre-inscriptions/export'], ['/admin/journal-des-visites/export'], ['/admin/journal-des-visites/site/export'],
        ];
    }

    #[DataProvider('pagesAdmin')]
    public function test_une_personne_non_connectee_est_renvoyee_vers_la_connexion(string $adresse): void
    {
        $this->get($adresse)->assertRedirect(route('admin.login'));
    }

    public function test_les_pages_de_connexion_ne_sont_pas_indexees_par_google(): void
    {
        $this->get('/admin/login')->assertOk()
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false)
            ->assertSee('Mot de passe oublié ?');
    }

    public function test_connexion_avec_le_bon_mot_de_passe(): void
    {
        $admin = $this->administrateur();

        $this->post('/admin/login', ['email' => $admin->email, 'password' => self::MOT_DE_PASSE])->assertRedirect(route('admin'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_connexion_refusee_avec_un_mauvais_mot_de_passe(): void
    {
        $admin = $this->administrateur();

        $this->from('/admin/login')
            ->post('/admin/login', ['email' => $admin->email, 'password' => 'mauvais'])
            ->assertRedirect('/admin/login')
            ->assertSessionHasErrors(['email' => 'Identifiants incorrects. Vérifiez votre email et votre mot de passe.']);
        $this->assertGuest();
    }

    public function test_un_mauvais_mot_de_passe_ferme_une_session_deja_ouverte(): void
    {
        $admin = $this->connecterAdministrateur();

        $this->post('/admin/login', ['email' => $admin->email, 'password' => 'mauvais'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_un_administrateur_deja_connecte_qui_ouvre_la_connexion_est_prevenu(): void
    {
        $this->connecterAdministrateur();

        $this->get('/admin/login')
            ->assertRedirect(route('admin'))
            ->assertSessionHas('success', 'Vous êtes déjà connecté en tant que direction@exemple.test. Pour changer de compte, déconnectez-vous en bas du menu.');
    }

    public function test_apres_5_essais_ratés_la_connexion_est_bloquee_une_minute(): void
    {
        $admin = $this->administrateur();

        for ($i = 1; $i <= 5; $i++) {
            $this->post('/admin/login', ['email' => $admin->email, 'password' => "mauvais$i"]);
        }

        $this->from('/admin/login')
            ->post('/admin/login', ['email' => $admin->email, 'password' => self::MOT_DE_PASSE])
            ->assertSessionHasErrors(['email' => 'Trop de tentatives de connexion. Patientez une minute avant de réessayer.']);
        $this->assertGuest();
    }

    public function test_deconnexion(): void
    {
        $this->connecterAdministrateur();

        $this->post('/admin/logout')->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_la_session_se_ferme_a_la_fermeture_du_navigateur(): void
    {
        $this->assertTrue((bool) config('session.expire_on_close'));
    }

    /* ---------------------------------------------------------------- Mot de passe oublié */

    public function test_la_demande_de_lien_envoie_un_email_au_compte(): void
    {
        Mail::fake();
        $this->administrateur();

        $this->from('/admin/mot-de-passe-oublie')
            ->post('/admin/mot-de-passe-oublie', ['email' => '  Direction@Exemple.test '])
            ->assertSessionHas('statut');

        Mail::assertSent(ReinitialisationMotDePasse::class, function ($mail) {
            return $mail->hasTo('direction@exemple.test')
                && str_contains($mail->lien, '/admin/reinitialiser-mot-de-passe/')
                && $mail->dureeMinutes === 60;
        });
    }

    public function test_un_email_inconnu_recoit_la_meme_reponse_sans_envoi(): void
    {
        Mail::fake();
        $this->administrateur();

        $reponseConnu = $this->post('/admin/mot-de-passe-oublie', ['email' => 'direction@exemple.test']);
        $reponseInconnu = $this->post('/admin/mot-de-passe-oublie', ['email' => 'inconnu@exemple.test']);

        $this->assertSame(session('statut'), $reponseConnu->getSession()->get('statut'));
        $reponseInconnu->assertSessionHas('statut', $reponseConnu->getSession()->get('statut'));
        Mail::assertSentCount(1);
    }

    public function test_reinitialisation_complete_avec_un_lien_valide(): void
    {
        config(['session.driver' => 'database']); // comme sur le site (les tests utilisent sinon des sessions en mémoire)
        $admin = $this->administrateur();
        $jeton = Password::createToken($admin);
        DB::table('sessions')->insert(['id' => 'autre-appareil', 'user_id' => $admin->id, 'payload' => '', 'last_activity' => time()]);

        $this->get('/admin/reinitialiser-mot-de-passe/'.$jeton.'?email='.urlencode($admin->email))
            ->assertOk()
            ->assertSee('Choisir un nouveau mot de passe');

        $this->post('/admin/reinitialiser-mot-de-passe', [
            'token' => $jeton, 'email' => $admin->email,
            'password' => 'Nouveau#MotDePasse2026', 'password_confirmation' => 'Nouveau#MotDePasse2026',
        ])->assertRedirect(route('admin'));

        $this->assertTrue(Hash::check('Nouveau#MotDePasse2026', $admin->fresh()->password));
        $this->assertAuthenticatedAs($admin);
        $this->assertDatabaseMissing('sessions', ['id' => 'autre-appareil']);
        $this->assertDatabaseCount('password_reset_tokens', 0);
    }

    public function test_un_mot_de_passe_trop_faible_est_refuse_lors_de_la_reinitialisation(): void
    {
        $admin = $this->administrateur();
        $jeton = Password::createToken($admin);

        $this->post('/admin/reinitialiser-mot-de-passe', ['token' => $jeton, 'email' => $admin->email, 'password' => 'court', 'password_confirmation' => 'court'])
            ->assertSessionHasErrors(['password' => 'Le nouveau mot de passe doit contenir au moins 12 caractères.']);

        $this->assertTrue(Hash::check(self::MOT_DE_PASSE, $admin->fresh()->password));
    }

    public function test_un_lien_faux_ou_expire_est_refuse(): void
    {
        $admin = $this->administrateur();

        $this->get('/admin/reinitialiser-mot-de-passe/jeton-invente?email='.urlencode($admin->email))
            ->assertRedirect(route('admin.password.request'));

        $jeton = Password::createToken($admin);
        $this->travel(61)->minutes();
        $this->get('/admin/reinitialiser-mot-de-passe/'.$jeton.'?email='.urlencode($admin->email))
            ->assertRedirect(route('admin.password.request'))
            ->assertSessionHasErrors('email');
    }

    public function test_les_demandes_de_lien_sont_limitees(): void
    {
        Mail::fake();

        for ($i = 1; $i <= 5; $i++) {
            $this->post('/admin/mot-de-passe-oublie', ['email' => "personne$i@exemple.test"])->assertSessionHasNoErrors();
        }

        $this->post('/admin/mot-de-passe-oublie', ['email' => 'personne6@exemple.test'])
            ->assertSessionHasErrors(['email' => 'Trop de demandes en peu de temps. Patientez quelques minutes avant de réessayer.']);
    }
}
