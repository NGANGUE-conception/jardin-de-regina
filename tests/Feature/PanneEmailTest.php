<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\CorporateRequest;
use App\Models\JobApplication;
use App\Models\PreRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Tests\TestCase;

/**
 * Service d'envoi d'emails en panne (Brevo indisponible, mauvais réglage...) :
 * les demandes doivent rester enregistrées et la personne doit voir la confirmation, jamais une erreur 500.
 */
class PanneEmailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        Log::spy();

        // Transport d'emails qui échoue à chaque envoi, comme un serveur SMTP injoignable.
        Mail::extend('en_panne', fn () => new class extends AbstractTransport
        {
            protected function doSend(SentMessage $message): void
            {
                throw new TransportException('Connexion au serveur SMTP impossible.');
            }

            public function __toString(): string
            {
                return 'en_panne';
            }
        });

        config([
            'mail.mailers.en_panne' => ['transport' => 'en_panne'],
            'mail.default' => 'en_panne',
        ]);
    }

    private function assertPanneJournalisee(): void
    {
        Log::shouldHaveReceived('error')->withArgs(fn ($message) => str_contains($message, 'Échec de l\'envoi d\'un email'));
    }

    public function test_pre_inscription_enregistree_et_confirmee_malgre_la_panne(): void
    {
        $this->post('/pre-inscription', [
            'child_last_name' => 'Martin',
            'child_first_name' => 'Louise',
            'child_gender' => 'Fille',
            'child_birthdate' => '2025-05-01',
            'entry_date' => now()->addMonths(3)->format('Y-m-d'),
            'care_type' => 'regulier',
            'care_days' => ['lundi', 'mardi'],
            'p1_nom' => 'Martin',
            'p1_prenom' => 'Claire',
            'p1_email' => 'claire.martin@exemple.fr',
            'p1_tel' => '06 12 34 56 78',
            'rgpd_consent' => '1',
        ] + $this->antiSpam())
            ->assertRedirect(route('confirmation-preinscription', ['dossier' => PreRegistration::sole()->dossier_number]));

        $this->assertPanneJournalisee();
    }

    public function test_message_de_contact_enregistre_et_confirme_malgre_la_panne(): void
    {
        $this->post('/contact', [
            'fullname' => 'Claire Martin',
            'email' => 'claire.martin@exemple.fr',
            'phone' => '06 12 34 56 78',
            'subject' => 'Demander une visite privée',
            'message' => 'Bonjour, nous aimerions visiter la crèche.',
            'rgpd_consent' => '1',
        ] + $this->antiSpam())->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseCount(ContactMessage::class, 1);
        $this->assertPanneJournalisee();
    }

    public function test_demande_entreprise_enregistree_et_confirmee_malgre_la_panne(): void
    {
        $this->post('/berceaux-entreprise/devis', [
            'company_name' => 'Atelier Test SAS',
            'contact_name' => 'Sophie Martin',
            'contact_email' => 'sophie@entreprise.fr',
            'contact_tel' => '01 43 00 00 00',
            'rgpd_consent' => '1',
        ] + $this->antiSpam())->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseCount(CorporateRequest::class, 1);
        $this->assertPanneJournalisee();
    }

    public function test_candidature_enregistree_et_confirmee_malgre_la_panne(): void
    {
        $this->post('/recrutement/candidature', [
            'nom' => 'Durand',
            'prenom' => 'Camille',
            'email' => 'camille@exemple.fr',
            'tel' => '06 00 00 00 00',
            'poste' => 'Auxiliaire de Puériculture (AP)',
            'message' => 'Motivée !',
            'documents' => [UploadedFile::fake()->create('cv.pdf', 120, 'application/pdf')],
            'rgpd_consent' => '1',
        ] + $this->antiSpam())->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseCount(JobApplication::class, 1);
        $this->assertPanneJournalisee();
    }

    public function test_pre_inscription_manuelle_previent_la_direction_si_l_email_n_est_pas_parti(): void
    {
        $this->connecterAdministrateur();

        $this->post('/admin/pre-inscriptions', [
            'source' => 'telephone',
            'child_first_name' => 'Jeanne',
            'child_last_name' => 'Morel',
            'child_gender' => 'Fille',
            'child_birthdate' => '2026-03-14',
            'entry_date' => '2026-12-01',
            'care_type' => 'urgence',
            'p1_prenom' => 'Paul',
            'p1_nom' => 'Morel',
            'p1_tel' => '06 11 22 33 44',
            'p1_email' => 'paul.morel@exemple.fr',
            'envoyer_confirmation' => '1',
            'famille_informee' => '1',
        ])->assertRedirect(route('admin.pre-inscriptions'))
            ->assertSessionHas('success', fn ($message) => str_contains($message, 'n\'a pas pu être envoyé'));

        $this->assertDatabaseCount(PreRegistration::class, 1);
    }

    public function test_mot_de_passe_oublie_garde_la_reponse_neutre_malgre_la_panne(): void
    {
        $administrateur = $this->administrateur();

        $this->from('/admin/mot-de-passe-oublie')
            ->post('/admin/mot-de-passe-oublie', ['email' => $administrateur->email])
            ->assertRedirect('/admin/mot-de-passe-oublie')
            ->assertSessionHas('statut');

        $this->assertPanneJournalisee();
    }
}
