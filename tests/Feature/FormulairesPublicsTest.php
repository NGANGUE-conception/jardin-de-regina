<?php

namespace Tests\Feature;

use App\Http\Middleware\ProtectionAntiSpam;
use App\Mail\NewSubmissionAlert;
use App\Mail\SubmissionConfirmation;
use App\Models\ContactMessage;
use App\Models\CorporateRequest;
use App\Models\JobApplication;
use App\Models\PreRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Les 4 formulaires publics : enregistrement, emails, messages d'erreur en français et anti-spam.
 */
class FormulairesPublicsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Storage::fake('local');
    }

    private function preInscription(array $modifications = []): array
    {
        return array_merge([
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
        ], $modifications);
    }

    private function contact(array $modifications = []): array
    {
        return array_merge([
            'fullname' => 'Claire Martin',
            'email' => 'claire.martin@exemple.fr',
            'phone' => '06 12 34 56 78',
            'subject' => 'Demander une visite privée',
            'message' => 'Bonjour, nous aimerions visiter la crèche.',
            'rgpd_consent' => '1',
        ], $modifications);
    }

    /* ---------------------------------------------------------------- Pré-inscription */

    public function test_une_pre_inscription_valide_est_enregistree_et_envoie_deux_emails(): void
    {
        $reponse = $this->post('/pre-inscription', $this->preInscription() + $this->antiSpam());

        $dossier = PreRegistration::sole();
        $reponse->assertRedirect(route('confirmation-preinscription', ['dossier' => $dossier->dossier_number]));
        $this->assertMatchesRegularExpression('/^#JR-\d{4}-0001$/', $dossier->dossier_number);
        $this->assertSame('nouvelle', $dossier->status);
        $this->assertSame(['lundi', 'mardi'], $dossier->care_days);
        $this->assertTrue($dossier->rgpd_consent);

        Mail::assertSent(SubmissionConfirmation::class, fn ($mail) => $mail->hasTo('claire.martin@exemple.fr') && $mail->numeroDossier === $dossier->dossier_number);
        Mail::assertSent(NewSubmissionAlert::class, fn ($mail) => $mail->hasTo(config('mail.admin_address')));
    }

    public function test_les_numeros_de_dossier_se_suivent(): void
    {
        $this->post('/pre-inscription', $this->preInscription() + $this->antiSpam());
        $this->post('/pre-inscription', $this->preInscription(['p1_email' => 'autre@exemple.fr']) + $this->antiSpam());

        $numeros = PreRegistration::orderBy('id')->pluck('dossier_number')->map(fn ($n) => substr($n, -4))->all();
        $this->assertSame(['0001', '0002'], $numeros);
    }

    public function test_une_pre_inscription_incomplete_est_refusee_avec_des_messages_en_francais(): void
    {
        $this->from('/pre-inscription')
            ->post('/pre-inscription', $this->preInscription(['child_first_name' => '', 'p1_email' => 'pas-un-email', 'rgpd_consent' => null]) + $this->antiSpam())
            ->assertRedirect('/pre-inscription')
            ->assertSessionHasErrors([
                'child_first_name' => 'Merci de renseigner le prénom de l\'enfant.',
                'p1_email' => 'Merci d\'indiquer une adresse e-mail valide (ex. prenom.nom@exemple.fr).',
                'rgpd_consent' => 'Vous devez accepter le traitement de vos données pour envoyer votre demande.',
            ]);

        $this->assertDatabaseCount('pre_registrations', 0);
        Mail::assertNothingSent();
    }

    public function test_au_moins_un_jour_de_presence_est_demande_sauf_en_accueil_d_urgence(): void
    {
        $this->post('/pre-inscription', $this->preInscription(['care_days' => null]) + $this->antiSpam())
            ->assertSessionHasErrors(['care_days' => 'Merci de sélectionner au moins un jour de présence.']);

        $this->post('/pre-inscription', $this->preInscription(['care_type' => 'urgence', 'care_days' => null]) + $this->antiSpam())
            ->assertSessionHasNoErrors();
        $this->assertDatabaseCount('pre_registrations', 1);
    }

    public function test_la_page_de_pre_inscription_rouvre_l_etape_qui_contient_l_erreur(): void
    {
        $this->post('/pre-inscription', $this->preInscription(['p2_email' => 'mauvais']) + $this->antiSpam());
        $page = $this->get('/pre-inscription')
            ->assertSee('Merci d&#039;indiquer une adresse e-mail valide', false)
            ->assertSee('id="p2_email-erreur"', false)
            ->getContent();

        // Configuration Alpine de la page (les guillemets y sont encodés en " par Blade).
        preg_match("/preInscription\(JSON\.parse\('(.*?)'\)\)/", $page, $trouve);
        $config = json_decode(json_decode('"'.$trouve[1].'"'), true);
        $this->assertSame(2, $config['etape'], 'L\'étape 2 (qui contient l\'erreur) doit être rouverte');
        $this->assertTrue($config['p2Ouvert'], 'Le bloc « second parent » doit être déplié');
    }

    /* ---------------------------------------------------------------- Contact, devis, candidature */

    public function test_un_message_de_contact_valide_est_enregistre(): void
    {
        $this->post('/contact', $this->contact() + $this->antiSpam())
            ->assertRedirect(route('contact').'#contact-form')
            ->assertSessionHas('success');

        $message = ContactMessage::sole();
        $this->assertSame('non_lu', $message->status);
        Mail::assertSent(SubmissionConfirmation::class, fn ($mail) => $mail->hasTo('claire.martin@exemple.fr'));
        Mail::assertSent(NewSubmissionAlert::class);
    }

    public function test_un_message_de_contact_avec_une_erreur_conserve_la_saisie(): void
    {
        $this->post('/contact', $this->contact(['message' => '']) + $this->antiSpam());

        $this->get('/contact')
            ->assertSee('Merci de renseigner votre message.')
            ->assertSee('value="Claire Martin"', false)
            ->assertSee('id="message-erreur"', false)
            ->assertSee('aria-invalid="true" aria-describedby="message-erreur"', false);
    }

    public function test_une_demande_de_devis_entreprise_valide_est_enregistree(): void
    {
        $this->post('/berceaux-entreprise/devis', [
            'company_name' => 'Atelier Test SAS',
            'contact_name' => 'Sophie Martin',
            'contact_email' => 'sophie@entreprise.fr',
            'contact_tel' => '01 43 00 00 00',
            'rgpd_consent' => '1',
        ] + $this->antiSpam())->assertSessionHasNoErrors();

        $this->assertSame('Atelier Test SAS', CorporateRequest::sole()->company_name);
        Mail::assertSent(NewSubmissionAlert::class);
    }

    public function test_une_candidature_enregistre_le_cv_en_prive(): void
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
        ] + $this->antiSpam())->assertSessionHasNoErrors();

        $candidature = JobApplication::sole();
        $this->assertCount(1, $candidature->documents);
        Storage::disk('local')->assertExists($candidature->documents[0]);
        $this->assertStringStartsWith('candidatures/', $candidature->documents[0]);
    }

    public function test_une_candidature_refuse_les_fichiers_non_autorises(): void
    {
        $this->post('/recrutement/candidature', [
            'nom' => 'Durand', 'prenom' => 'Camille', 'email' => 'camille@exemple.fr', 'tel' => '06 00 00 00 00',
            'poste' => 'Auxiliaire de Puériculture (AP)', 'message' => 'Motivée !', 'rgpd_consent' => '1',
            'documents' => [UploadedFile::fake()->create('virus.exe', 10)],
        ] + $this->antiSpam())->assertSessionHasErrors(['documents.0' => 'Chaque fichier doit être au format PDF, DOC ou DOCX.']);

        $this->assertDatabaseCount('job_applications', 0);
    }

    /* ---------------------------------------------------------------- Anti-spam */

    public function test_un_robot_qui_remplit_le_champ_piege_est_ignore_en_silence(): void
    {
        $this->post('/contact', $this->contact([ProtectionAntiSpam::CHAMP_PIEGE => 'http://spam.example']) + $this->antiSpam())
            ->assertRedirect(route('contact').'#contact-form')
            ->assertSessionHas('success');

        $this->assertDatabaseCount('contact_messages', 0);
        Mail::assertNothingSent();
    }

    public function test_un_envoi_trop_rapide_est_refuse_sans_perdre_la_saisie(): void
    {
        $this->from('/contact')
            ->post('/contact', $this->contact() + [ProtectionAntiSpam::CHAMP_HEURE => encrypt(now()->getTimestamp())])
            ->assertRedirect('/contact')
            ->assertSessionHasErrors(['anti_spam'])
            ->assertSessionHasInput('fullname', 'Claire Martin');

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_un_jeton_anti_spam_absent_ou_falsifie_est_refuse(): void
    {
        $this->post('/contact', $this->contact())->assertSessionHasErrors(['anti_spam']);
        $this->post('/contact', $this->contact() + [ProtectionAntiSpam::CHAMP_HEURE => 'faux'])->assertSessionHasErrors(['anti_spam']);

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_au_dela_de_10_envois_par_heure_le_formulaire_est_bloque(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $this->post('/contact', $this->contact(['email' => "parent$i@exemple.fr"]) + $this->antiSpam())->assertSessionHasNoErrors();
        }

        $this->from('/contact')
            ->post('/contact', $this->contact(['email' => 'parent11@exemple.fr']) + $this->antiSpam())
            ->assertSessionHasErrors(['anti_spam' => 'Vous avez envoyé plusieurs demandes en peu de temps. Merci de patienter un peu avant de réessayer, ou appelez-nous au 07 67 64 61 84.']);

        $this->assertDatabaseCount('contact_messages', 10);
    }
}
