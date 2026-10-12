<?php

namespace Tests\Feature;

use App\Models\PreRegistration;
use App\Models\SiteVisit;
use App\Models\Visite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Journal des visites : rendez-vous programmés par la direction et visites du site, avec leurs exports Excel.
 */
class JournalVisitesTest extends TestCase
{
    use RefreshDatabase;

    private function rendezVous(array $modifications = []): array
    {
        return array_merge([
            'visit_date' => now()->addDays(2)->format('Y-m-d'),
            'visit_time' => '10:30',
            'visitor_name' => 'Famille Martin',
            'phone' => '06 12 34 56 78',
            'type' => 'preinscription',
        ], $modifications);
    }

    public function test_programmer_un_rendez_vous_lie_a_une_pre_inscription(): void
    {
        $this->connecterAdministrateur();
        $dossier = PreRegistration::factory()->create();

        $this->post('/admin/journal-des-visites', $this->rendezVous(['dossier' => 'pre_registration:'.$dossier->id]))
            ->assertSessionHas('success', 'Visite ajoutée au journal.');

        $visite = Visite::sole();
        $this->assertSame('a_confirmer', $visite->status);
        $this->assertTrue($visite->visitable->is($dossier));
        $this->assertSame('10h30', $visite->heureLabel());

        $this->get('/admin/journal-des-visites')->assertOk()->assertSee('Famille Martin')->assertSee($dossier->dossier_number);
    }

    public function test_un_dossier_lie_inexistant_est_refuse(): void
    {
        $this->connecterAdministrateur();

        $this->post('/admin/journal-des-visites', $this->rendezVous(['dossier' => 'pre_registration:999']))->assertSessionHasErrors('dossier');
        $this->post('/admin/journal-des-visites', $this->rendezVous(['dossier' => 'n_importe_quoi']))->assertSessionHasErrors('dossier');

        $this->assertDatabaseCount('visits', 0);
    }

    public function test_modifier_changer_le_statut_et_supprimer_un_rendez_vous(): void
    {
        $this->connecterAdministrateur();
        $this->post('/admin/journal-des-visites', $this->rendezVous());
        $visite = Visite::sole();

        $this->put("/admin/journal-des-visites/{$visite->id}", $this->rendezVous(['visit_time' => '14:00', 'internal_note' => 'Famille très intéressée']));
        $this->assertSame('14h00', $visite->fresh()->heureLabel());
        $this->assertSame('Famille très intéressée', $visite->fresh()->internal_note);

        $this->patch("/admin/journal-des-visites/{$visite->id}/statut", ['status' => 'realisee']);
        $this->assertSame('realisee', $visite->fresh()->status);

        $this->delete("/admin/journal-des-visites/{$visite->id}");
        $this->assertModelMissing($visite);
    }

    public function test_le_filtre_par_periode_du_journal(): void
    {
        $this->connecterAdministrateur();
        $this->post('/admin/journal-des-visites', $this->rendezVous(['visitor_name' => 'Famille Proche', 'visit_date' => now()->format('Y-m-d')]));
        $this->post('/admin/journal-des-visites', $this->rendezVous(['visitor_name' => 'Famille Lointaine', 'visit_date' => now()->addMonths(2)->format('Y-m-d')]));

        $aujourdhui = now()->format('Y-m-d');
        $this->get("/admin/journal-des-visites?du=$aujourdhui&au=$aujourdhui")->assertSee('Famille Proche')->assertDontSee('Famille Lointaine');
    }

    public function test_l_export_excel_des_rendez_vous(): void
    {
        $this->connecterAdministrateur();
        $this->post('/admin/journal-des-visites', $this->rendezVous());

        $this->get('/admin/journal-des-visites/export')->assertOk()->assertDownload('journal-des-rendez-vous-complet.xlsx');
    }

    public function test_la_page_et_l_export_des_visites_du_site(): void
    {
        $this->connecterAdministrateur();
        SiteVisit::create(['visitor_id' => '00000000-0000-4000-8000-000000000002', 'started_at' => now()->subDay(), 'last_activity_at' => now()->subDay(), 'landing_path' => '/', 'source' => 'google', 'device' => 'mobile', 'pages_count' => 3]);

        foreach (['', '?periode=7j&regroupement=semaine', '?periode=12m', '?periode=mois_precedent', '?formulaires=1'] as $parametres) {
            $this->get('/admin/journal-des-visites/site'.$parametres)->assertOk();
        }
        $this->get('/admin/journal-des-visites/site')->assertSee('Google');

        $reponse = $this->get('/admin/journal-des-visites/site/export?periode=30j')->assertOk();
        $this->assertStringStartsWith('attachment; filename=visites-du-site-', $reponse->headers->get('Content-Disposition'));
    }

    public function test_des_dates_inversees_sont_refusees_dans_les_visites_du_site(): void
    {
        $this->connecterAdministrateur();

        $this->get('/admin/journal-des-visites/site?du=2026-05-01&au=2026-01-01')->assertSessionHasErrors('au');
    }
}
