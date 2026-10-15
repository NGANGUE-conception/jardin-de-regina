<?php

namespace Tests\Feature;

use App\Models\Parametre;
use App\Support\Etablissement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Espace admin → Paramètres : vérification d'identité, email et mot de passe, coordonnées de l'établissement.
 */
class ParametresTest extends TestCase
{
    use RefreshDatabase;

    private function verifierIdentite(): void
    {
        $this->post('/admin/parametres/verifier-identite', ['verification_identite' => self::MOT_DE_PASSE])->assertSessionHasNoErrors();
    }

    private function coordonnees(array $modifications = []): array
    {
        return array_merge([
            'telephone' => '07 67 64 61 84',
            'email' => 'jardinderegina94@gmail.com',
            'rue' => '4 bis rue des Écoles',
            'code_postal' => '94140',
            'ville' => 'Alfortville',
            'ouverture' => '08:30',
            'fermeture' => '18:30',
            'gestionnaire' => 'TEF Group Invest',
            'representant' => 'M. Fabrice TCHOUMO',
        ], $modifications);
    }

    /* ---------------------------------------------------------------- Compte administrateur */

    public function test_l_email_et_le_mot_de_passe_ne_peuvent_pas_changer_sans_verification_d_identite(): void
    {
        $admin = $this->connecterAdministrateur();

        $this->put('/admin/parametres/email', ['email' => 'pirate@exemple.test'])->assertSessionHasErrors('verification_identite', null, 'identite');
        $this->put('/admin/parametres/mot-de-passe', ['password' => 'Pirate#Motdepasse1', 'password_confirmation' => 'Pirate#Motdepasse1'])->assertSessionHasErrors('verification_identite', null, 'identite');

        $this->assertSame('direction@exemple.test', $admin->fresh()->email);
        $this->assertTrue(Hash::check(self::MOT_DE_PASSE, $admin->fresh()->password));
    }

    public function test_la_verification_d_identite_refuse_un_mauvais_mot_de_passe(): void
    {
        $this->connecterAdministrateur();

        $this->post('/admin/parametres/verifier-identite', ['verification_identite' => 'mauvais'])
            ->assertSessionHasErrors(['verification_identite' => 'Mot de passe incorrect. Votre identité n\'a pas pu être vérifiée.'], null, 'identite');
    }

    public function test_changement_d_email_apres_verification_puis_reverrouillage(): void
    {
        $admin = $this->connecterAdministrateur();
        $this->verifierIdentite();

        $this->put('/admin/parametres/email', ['email' => ' Nouvelle.Adresse@Exemple.test '])->assertSessionHasNoErrors();
        $this->assertSame('nouvelle.adresse@exemple.test', $admin->fresh()->email);

        // La vérification est consommée : un second changement demande de se re-vérifier.
        $this->put('/admin/parametres/email', ['email' => 'encore@exemple.test'])->assertSessionHasErrors('verification_identite', null, 'identite');
    }

    public function test_changement_de_mot_de_passe_apres_verification(): void
    {
        $admin = $this->connecterAdministrateur();
        $this->verifierIdentite();

        $this->put('/admin/parametres/mot-de-passe', ['password' => 'Nouveau#MotDePasse2026', 'password_confirmation' => 'Nouveau#MotDePasse2026'])
            ->assertSessionHasNoErrors();

        $this->assertTrue(Hash::check('Nouveau#MotDePasse2026', $admin->fresh()->password));
    }

    public function test_le_nouveau_mot_de_passe_doit_etre_solide_et_different(): void
    {
        $this->connecterAdministrateur();
        $this->verifierIdentite();

        $this->put('/admin/parametres/mot-de-passe', ['password' => self::MOT_DE_PASSE, 'password_confirmation' => self::MOT_DE_PASSE])
            ->assertSessionHasErrors(['password' => 'Le nouveau mot de passe doit être différent de l\'actuel.'], null, 'motDePasse');
        $this->put('/admin/parametres/mot-de-passe', ['password' => 'toutpetitmotdepasse', 'password_confirmation' => 'toutpetitmotdepasse'])
            ->assertSessionHasErrors('password', null, 'motDePasse');
    }

    public function test_la_verification_d_identite_expire_apres_10_minutes(): void
    {
        $admin = $this->connecterAdministrateur();
        $this->verifierIdentite();
        $this->travel(11)->minutes();

        $this->put('/admin/parametres/email', ['email' => 'trop.tard@exemple.test'])->assertSessionHasErrors('verification_identite', null, 'identite');
        $this->assertSame('direction@exemple.test', $admin->fresh()->email);
    }

    /* ---------------------------------------------------------------- Coordonnées de l'établissement */

    public function test_les_coordonnees_modifiees_s_affichent_sur_tout_le_site_et_pour_google(): void
    {
        Http::fake(['api-adresse.data.gouv.fr/*' => Http::response(['features' => [[
            'properties' => ['score' => 0.92],
            'geometry' => ['coordinates' => [2.415031, 48.815085]],
        ]]])]);
        $this->connecterAdministrateur();

        $this->put('/admin/parametres/etablissement', $this->coordonnees([
            'telephone' => '+33 1 43 75 00 00',
            'email' => 'Contact@JardinDeRegina.fr',
            'rue' => '10 rue Paul Vaillant-Couturier',
            'code_postal' => '94700',
            'ville' => 'Maisons-Alfort',
            'ouverture' => '08:00',
            'fermeture' => '19:00',
        ]))->assertRedirect(route('admin.parametres'))->assertSessionHasNoErrors();

        $etablissement = app(Etablissement::class);
        $this->assertSame('01 43 75 00 00', $etablissement->telephone());
        $this->assertSame('contact@jardinderegina.fr', $etablissement->email());
        $this->assertSame(48.815085, $etablissement->latitude());

        $this->post('/admin/logout');
        $page = $this->get('/contact')->assertOk();
        $page->assertSee('01 43 75 00 00')->assertSee('tel:+33143750000', false)->assertSee('10 rue Paul Vaillant-Couturier')->assertSee('8h – 19h')
            ->assertDontSee('07 67 64 61 84');
        $page->assertSee('Maisons-Alfort (94700)')->assertDontSee('Alfortville (94140)')
            ->assertSee('query='.urlencode('10 rue Paul Vaillant-Couturier, 94700 Maisons-Alfort'), false);
        $this->get('/recrutement')->assertSee('Maisons-Alfort (94700)');
        $this->assertStringContainsString('"closes": "19:00"', $page->getContent());
    }

    public function test_une_adresse_introuvable_est_enregistree_avec_un_avertissement(): void
    {
        Http::fake(['api-adresse.data.gouv.fr/*' => Http::response(['features' => []])]);
        $this->connecterAdministrateur();

        $this->put('/admin/parametres/etablissement', $this->coordonnees(['rue' => 'Impasse introuvable']))
            ->assertSessionHas('avertissement');

        $this->assertSame('Impasse introuvable', app(Etablissement::class)->rue());
        $this->assertSame(48.803094, app(Etablissement::class)->latitude(), 'L\'ancienne position doit être conservée');
    }

    public function test_les_coordonnees_invalides_sont_refusees(): void
    {
        $this->connecterAdministrateur();

        $this->put('/admin/parametres/etablissement', $this->coordonnees(['telephone' => '12345', 'code_postal' => '9414', 'ouverture' => '18:00', 'fermeture' => '08:00']))
            ->assertSessionHasErrors([
                'telephone' => 'Merci d\'indiquer un numéro de téléphone français valide (ex. 07 67 64 61 84).',
                'code_postal' => 'Le code postal doit contenir 5 chiffres.',
                'fermeture' => 'L\'heure de fermeture doit être après l\'heure d\'ouverture.',
            ], null, 'etablissement');

        $this->assertDatabaseCount('parametres', 0);
    }

    public function test_sans_reglage_enregistre_les_valeurs_par_defaut_s_appliquent(): void
    {
        $this->assertSame(0, Parametre::count());
        $etablissement = app(Etablissement::class);

        $this->assertSame('07 67 64 61 84', $etablissement->telephone());
        $this->assertSame('4 bis rue des Écoles, 94140 Alfortville', $etablissement->adresse());
        $this->assertSame('8h30 – 18h30', $etablissement->horaires());
    }

    public function test_l_onglet_notifications_n_existe_plus(): void
    {
        $this->connecterAdministrateur();

        $this->get('/admin/parametres')->assertOk()->assertDontSee("onglet === 'notifications'", false);
    }
}
