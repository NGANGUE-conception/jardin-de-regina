<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\CorporateRequest;
use App\Models\JobApplication;
use App\Models\PreRegistration;
use App\Models\SiteVisit;
use App\Support\CompteursAdmin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Gestion des dossiers dans l'espace admin : listes, statuts, notes, documents, suppression, badges.
 */
class AdminDossiersTest extends TestCase
{
    use RefreshDatabase;

    public function test_chaque_liste_affiche_les_dossiers_enregistres(): void
    {
        $this->connecterAdministrateur();
        $preInscription = PreRegistration::factory()->create(['child_first_name' => 'Louise']);
        $entreprise = CorporateRequest::factory()->create(['company_name' => 'Atelier Test SAS']);
        $candidature = JobApplication::factory()->create(['prenom' => 'Camille']);
        $message = ContactMessage::factory()->create(['subject' => 'Question sur les tarifs']);

        $this->get('/admin')->assertOk()->assertSee($preInscription->dossier_number);
        $this->get('/admin/pre-inscriptions')->assertOk()->assertSee('Louise');
        $this->get('/admin/demandes-entreprises')->assertOk()->assertSee('Atelier Test SAS');
        $this->get('/admin/candidatures')->assertOk()->assertSee('Camille');
        $this->get('/admin/messages')->assertOk()->assertSee('Question sur les tarifs');
    }

    public function test_changement_de_statut_et_note_interne_d_une_pre_inscription(): void
    {
        $this->connecterAdministrateur();
        $dossier = PreRegistration::factory()->create();

        $this->patch("/admin/pre-inscriptions/{$dossier->id}/statut", ['status' => 'acceptee'])->assertSessionHas('success');
        $this->patch("/admin/pre-inscriptions/{$dossier->id}/note", ['internal_note' => 'Visite prévue mardi'])->assertSessionHas('success');

        $dossier->refresh();
        $this->assertSame('acceptee', $dossier->status);
        $this->assertSame('Visite prévue mardi', $dossier->internal_note);
    }

    public function test_un_statut_inconnu_est_refuse(): void
    {
        $this->connecterAdministrateur();
        $dossier = PreRegistration::factory()->create();

        $this->patch("/admin/pre-inscriptions/{$dossier->id}/statut", ['status' => 'pirate'])->assertSessionHasErrors('status');
        $this->assertSame('nouvelle', $dossier->fresh()->status);
    }

    public function test_changement_de_statut_des_autres_dossiers(): void
    {
        $this->connecterAdministrateur();
        $entreprise = CorporateRequest::factory()->create();
        $candidature = JobApplication::factory()->create();
        $message = ContactMessage::factory()->create();

        $this->patch("/admin/demandes-entreprises/{$entreprise->id}/statut", ['status' => 'signee']);
        $this->patch("/admin/candidatures/{$candidature->id}/statut", ['status' => 'entretien_prevu']);
        $this->patch("/admin/messages/{$message->id}/statut", ['status' => 'repondu']);

        $this->assertSame('signee', $entreprise->fresh()->status);
        $this->assertSame('entretien_prevu', $candidature->fresh()->status);
        $this->assertSame('repondu', $message->fresh()->status);
    }

    public function test_le_cv_se_telecharge_uniquement_par_l_admin(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('candidatures/cv-test.pdf', 'contenu du cv');
        $candidature = JobApplication::factory()->create(['documents' => ['candidatures/cv-test.pdf']]);

        $this->get("/admin/candidatures/{$candidature->id}/documents/0")->assertRedirect(route('admin.login'));

        $this->connecterAdministrateur();
        $this->get("/admin/candidatures/{$candidature->id}/documents/0")->assertOk()->assertDownload('cv-test.pdf');
        $this->get("/admin/candidatures/{$candidature->id}/documents/5")->assertNotFound();
    }

    public function test_l_export_excel_des_pre_inscriptions(): void
    {
        $this->connecterAdministrateur();
        PreRegistration::factory()->count(3)->create();

        $this->get('/admin/pre-inscriptions/export')->assertOk()->assertDownload('pre-inscriptions-'.now()->format('Y-m-d').'.xlsx');
    }

    /* ---------------------------------------------------------------- Suppression */

    public function test_seul_l_admin_connecte_peut_supprimer_un_dossier(): void
    {
        $dossier = PreRegistration::factory()->create();

        $this->delete("/admin/pre-inscriptions/{$dossier->id}")->assertRedirect(route('admin.login'));
        $this->assertModelExists($dossier);
    }

    public function test_supprimer_une_pre_inscription_detache_ses_rendez_vous_et_son_lien_de_visite(): void
    {
        $this->connecterAdministrateur();
        $dossier = PreRegistration::factory()->create();
        $rendezVous = $dossier->visites()->create(['visit_date' => now()->addDay(), 'visit_time' => '10:00', 'visitor_name' => 'Famille Test', 'type' => 'preinscription']);
        $visiteSite = SiteVisit::create(['visitor_id' => '00000000-0000-4000-8000-000000000001', 'started_at' => now(), 'last_activity_at' => now(), 'landing_path' => '/', 'source' => 'google', 'device' => 'mobile', 'pages_count' => 2]);
        $visiteSite->formulaires()->create(['formulaire_type' => 'pre_registration', 'formulaire_id' => $dossier->id, 'submitted_at' => now()]);

        $this->delete("/admin/pre-inscriptions/{$dossier->id}")->assertSessionHas('success', "Dossier {$dossier->dossier_number} supprimé définitivement.");

        $this->assertModelMissing($dossier);
        $this->assertNull($rendezVous->fresh()->visitable_type, 'Le rendez-vous est conservé mais détaché du dossier');
        $this->assertModelExists($visiteSite);
        $this->assertDatabaseCount('site_visit_forms', 0);
    }

    public function test_supprimer_une_candidature_supprime_aussi_son_cv(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('candidatures/cv-a-supprimer.pdf', 'cv');
        $this->connecterAdministrateur();
        $candidature = JobApplication::factory()->create(['documents' => ['candidatures/cv-a-supprimer.pdf']]);

        $this->delete("/admin/candidatures/{$candidature->id}")->assertSessionHas('success');

        $this->assertModelMissing($candidature);
        Storage::disk('local')->assertMissing('candidatures/cv-a-supprimer.pdf');
    }

    public function test_suppression_d_une_demande_entreprise_et_d_un_message(): void
    {
        $this->connecterAdministrateur();
        $entreprise = CorporateRequest::factory()->create();
        $message = ContactMessage::factory()->create();

        $this->delete("/admin/demandes-entreprises/{$entreprise->id}");
        $this->delete("/admin/messages/{$message->id}");

        $this->assertModelMissing($entreprise);
        $this->assertModelMissing($message);
    }

    /* ---------------------------------------------------------------- Badges du menu */

    public function test_les_badges_comptent_uniquement_les_elements_a_traiter(): void
    {
        PreRegistration::factory()->count(2)->create(['status' => 'nouvelle']);
        PreRegistration::factory()->create(['status' => 'acceptee']);
        CorporateRequest::factory()->create(['status' => 'en_discussion']);
        ContactMessage::factory()->create(['status' => 'non_lu']);
        ContactMessage::factory()->create(['status' => 'lu']);

        $this->assertSame(['pre-inscriptions' => 2, 'entreprises' => 0, 'candidatures' => 0, 'messages' => 1], CompteursAdmin::aTraiter());

        $this->connecterAdministrateur();
        $this->get('/admin')->assertSee('title="2 nouvelle(s) pré-inscription(s)"', false)->assertDontSee('nouvelle(s) demande(s) entreprise');
    }
}
