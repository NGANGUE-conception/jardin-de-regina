<?php

namespace Tests\Feature;

use App\Mail\SubmissionConfirmation;
use App\Models\PreRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Pré-inscription saisie à la main par la direction (bouton « Nouvelle pré-inscription manuelle »).
 */
class PreInscriptionManuelleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
    }

    /**
     * Demande minimale reçue par téléphone : pas d'email, pas d'adresse.
     *
     * @param  array<string, mixed>  $remplacements
     * @return array<string, mixed>
     */
    private function saisie(array $remplacements = []): array
    {
        return array_merge([
            'source' => 'telephone',
            'child_first_name' => 'Jeanne',
            'child_last_name' => 'Morel',
            'child_gender' => 'Fille',
            'child_birthdate' => '2026-03-14',
            'entry_date' => '2026-12-01',
            'care_type' => 'regulier',
            'care_days' => ['lundi', 'mardi', 'jeudi'],
            'p1_civilite' => 'Monsieur',
            'p1_prenom' => 'Paul',
            'p1_nom' => 'Morel',
            'p1_tel' => '06 11 22 33 44',
            'famille_informee' => '1',
        ], $remplacements);
    }

    public function test_un_visiteur_non_connecte_ne_peut_pas_creer_de_dossier(): void
    {
        $this->post('/admin/pre-inscriptions', $this->saisie())->assertRedirect(route('admin.login'));

        $this->assertDatabaseCount('pre_registrations', 0);
    }

    public function test_les_deux_pages_affichent_le_bouton_et_le_formulaire(): void
    {
        $this->connecterAdministrateur();

        foreach (['/admin', '/admin/pre-inscriptions'] as $page) {
            $this->get($page)->assertOk()
                ->assertSee('Nouvelle pré-inscription manuelle')
                ->assertSee('action="'.route('admin.pre-inscriptions.store').'"', false);
        }
    }

    public function test_creation_d_un_dossier_sans_email(): void
    {
        $this->connecterAdministrateur();

        $reponse = $this->post('/admin/pre-inscriptions', $this->saisie(['internal_note' => 'Appel du matin']));

        $dossier = PreRegistration::sole();
        $reponse->assertRedirect(route('admin.pre-inscriptions'))
            ->assertSessionHas('success')
            ->assertSessionHas('dossier_ouvert', $dossier->dossier_number);

        $this->assertSame('telephone', $dossier->source);
        $this->assertSame('nouvelle', $dossier->status);
        $this->assertNull($dossier->p1_email);
        $this->assertTrue($dossier->rgpd_consent);
        $this->assertFalse($dossier->special_needs);
        $this->assertSame(['lundi', 'mardi', 'jeudi'], $dossier->care_days);
        $this->assertSame('Appel du matin', $dossier->internal_note);
        $this->assertMatchesRegularExpression('/^#JR-\d{4}-\d{4}$/', $dossier->dossier_number);
        Mail::assertNothingSent();

        // Le dossier s'affiche ensuite dans la liste, sans lien email vide.
        $this->get('/admin/pre-inscriptions')->assertOk()->assertSee('Jeanne')->assertSee('Téléphone');
    }

    public function test_besoins_specifiques_detectes_quand_le_champ_est_rempli(): void
    {
        $this->connecterAdministrateur();

        $this->post('/admin/pre-inscriptions', $this->saisie(['special_needs_details' => 'Allergie aux arachides']));

        $this->assertTrue(PreRegistration::sole()->special_needs);
    }

    public function test_email_de_confirmation_envoye_seulement_si_demande(): void
    {
        $this->connecterAdministrateur();

        $this->post('/admin/pre-inscriptions', $this->saisie(['p1_email' => 'paul.morel@exemple.fr']));
        Mail::assertNothingSent();

        $this->post('/admin/pre-inscriptions', $this->saisie(['p1_email' => 'paul.morel@exemple.fr', 'envoyer_confirmation' => '1']));
        $dernier = PreRegistration::latest('id')->first();
        Mail::assertSent(SubmissionConfirmation::class, fn ($mail) => $mail->hasTo('paul.morel@exemple.fr') && $mail->numeroDossier === $dernier->dossier_number);
        Mail::assertSentCount(1);
    }

    public function test_aucun_email_quand_la_confirmation_est_demandee_sans_adresse(): void
    {
        $this->connecterAdministrateur();

        $this->post('/admin/pre-inscriptions', $this->saisie(['envoyer_confirmation' => '1']))->assertSessionHasNoErrors('preInscriptionManuelle');

        $this->assertDatabaseCount('pre_registrations', 1);
        Mail::assertNothingSent();
    }

    public function test_accueil_d_urgence_sans_jours_de_presence(): void
    {
        $this->connecterAdministrateur();

        $this->post('/admin/pre-inscriptions', $this->saisie(['care_type' => 'urgence', 'care_days' => null]))
            ->assertSessionHasNoErrors('preInscriptionManuelle');

        $this->assertNull(PreRegistration::sole()->care_days);
    }

    public function test_saisie_incomplete_refusee(): void
    {
        $this->connecterAdministrateur();

        $this->from('/admin/pre-inscriptions')
            ->post('/admin/pre-inscriptions', $this->saisieIncomplete())
            ->assertRedirect('/admin/pre-inscriptions')
            ->assertSessionHasErrors(['famille_informee', 'care_days', 'p1_tel', 'p1_email', 'source'], null, 'preInscriptionManuelle');

        $this->assertDatabaseCount('pre_registrations', 0);
        Mail::assertNothingSent();
    }

    public function test_apres_une_erreur_le_tiroir_se_rouvre_avec_les_messages_et_la_saisie(): void
    {
        $this->connecterAdministrateur();

        // État Alpine initial du tiroir, encodé par @js : {"ouvert":true,...} avec des guillemets échappés en ".
        $tiroirOuvert = 'ouvert'.chr(92).'u0022:true';

        $this->followingRedirects()
            ->from('/admin/pre-inscriptions')
            ->post('/admin/pre-inscriptions', $this->saisieIncomplete())
            ->assertOk()
            ->assertSee($tiroirOuvert, false)
            ->assertSee('Merci de corriger')
            ->assertSee('Merci de confirmer que la famille a été informée', false)
            ->assertSee('value="Jeanne"', false);
    }

    /**
     * @return array<string, mixed>
     */
    private function saisieIncomplete(): array
    {
        return $this->saisie([
            'famille_informee' => null,
            'care_days' => [],
            'p1_tel' => '',
            'p1_email' => 'pas-un-email',
            'source' => 'site-pirate',
        ]);
    }
}
