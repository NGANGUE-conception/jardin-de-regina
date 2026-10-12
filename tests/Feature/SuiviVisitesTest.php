<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\SitePageView;
use App\Models\SiteVisit;
use App\Models\SiteVisitForm;
use App\Support\StatistiquesVisites;
use App\Support\SuiviVisites;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Mesure d'audience anonyme du site public (onglet « Visites du site » du Journal des visites).
 */
class SuiviVisitesTest extends TestCase
{
    use RefreshDatabase;

    private const IPHONE = 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.0 Mobile/15E148 Safari/604.1';

    private const ORDINATEUR = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0 Safari/537.36';

    public function test_une_visite_venue_de_google_sur_mobile_est_enregistree_avec_son_parcours(): void
    {
        $this->withHeaders(['User-Agent' => self::IPHONE, 'Referer' => 'https://www.google.com/'])->get('/')->assertOk();
        $this->withHeaders(['User-Agent' => self::IPHONE, 'Referer' => 'http://localhost/'])->get('/tarifs')->assertOk();

        $visite = SiteVisit::sole();
        $this->assertSame('google', $visite->source);
        $this->assertSame('mobile', $visite->device);
        $this->assertSame('Safari', $visite->browser);
        $this->assertSame('iOS', $visite->os);
        $this->assertSame(2, $visite->pages_count);
        $this->assertSame(['/', '/tarifs'], $visite->pageViews->pluck('path')->all());
    }

    public function test_la_provenance_et_la_campagne_sont_reconnues(): void
    {
        $this->withHeaders(['User-Agent' => self::ORDINATEUR, 'Referer' => 'https://l.facebook.com/'])->get('/?utm_campaign=flyer-rentree');

        $visite = SiteVisit::sole();
        $this->assertSame('facebook', $visite->source);
        $this->assertSame('flyer-rentree', $visite->campaign);
        $this->assertSame('ordinateur', $visite->device);
        $this->assertSame('Facebook · campagne flyer-rentree', $visite->provenance());
    }

    public function test_les_robots_l_admin_connecte_et_les_pages_d_erreur_ne_sont_pas_comptes(): void
    {
        $this->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'])->get('/');
        $this->withHeaders(['User-Agent' => self::ORDINATEUR])->get('/page-inexistante');
        $this->withHeaders(['User-Agent' => self::ORDINATEUR])->get('/sitemap.xml');
        $this->connecterAdministrateur();
        $this->withHeaders(['User-Agent' => self::ORDINATEUR])->get('/');

        $this->assertDatabaseCount('site_visits', 0);
    }

    public function test_aucune_adresse_ip_n_est_enregistree(): void
    {
        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.42'])->withHeaders(['User-Agent' => self::ORDINATEUR])->get('/');

        $ligne = json_encode(SiteVisit::sole()->getAttributes()).json_encode(SitePageView::sole()->getAttributes());
        $this->assertStringNotContainsString('203.0.113', $ligne);
    }

    public function test_un_formulaire_envoye_est_rattache_a_la_visite(): void
    {
        Mail::fake();
        $this->withHeaders(['User-Agent' => self::ORDINATEUR])->get('/contact');
        $this->withHeaders(['User-Agent' => self::ORDINATEUR])->post('/contact', [
            'fullname' => 'Claire Martin', 'email' => 'claire@exemple.fr', 'phone' => '06 12 34 56 78',
            'subject' => 'Demander une visite privée', 'message' => 'Bonjour', 'rgpd_consent' => '1',
        ] + $this->antiSpam())->assertSessionHasNoErrors();

        $lien = SiteVisitForm::sole();
        $this->assertTrue($lien->formulaire->is(ContactMessage::sole()));
        $resume = $lien->resume();
        $this->assertSame('Message de contact', $resume['type']);
        $this->assertSame('Claire Martin', $resume['nom']);
        $this->assertSame('claire@exemple.fr', $resume['email']);
    }

    public function test_une_nouvelle_visite_commence_apres_30_minutes_d_inactivite(): void
    {
        $premiere = $this->withHeaders(['User-Agent' => self::ORDINATEUR])->get('/');
        // Le navigateur renvoie ensuite le cookie anonyme reçu (l'outil de test, lui, ne le fait pas tout seul).
        $visiteur = $premiere->getCookie(SuiviVisites::COOKIE_VISITEUR)->getValue(); // valeur déchiffrée
        $this->withCookie(SuiviVisites::COOKIE_VISITEUR, $visiteur);

        $this->travel(20)->minutes();
        $this->withHeaders(['User-Agent' => self::ORDINATEUR])->get('/tarifs');
        $this->travel(45)->minutes();
        $this->withHeaders(['User-Agent' => self::ORDINATEUR])->get('/contact');

        $this->assertSame([2, 1], SiteVisit::orderBy('id')->pluck('pages_count')->all());
        $this->assertTrue(SiteVisit::orderByDesc('id')->first()->is_returning, 'Le visiteur revenu doit être reconnu');
    }

    public function test_un_visiteur_qui_refuse_les_statistiques_n_est_plus_compte(): void
    {
        $this->post('/gestion-des-cookies/statistiques', ['choix' => 'refuser'])
            ->assertRedirect(route('gestion-des-cookies').'#section-4')
            ->assertCookie(SuiviVisites::COOKIE_REFUS);

        $this->withCookie(SuiviVisites::COOKIE_REFUS, '1')->withHeaders(['User-Agent' => self::ORDINATEUR])->get('/');

        $this->assertDatabaseCount('site_visits', 0);
    }

    public function test_le_cookie_visiteur_dure_13_mois_au_maximum(): void
    {
        $reponse = $this->withHeaders(['User-Agent' => self::ORDINATEUR])->get('/');

        $cookie = collect($reponse->headers->getCookies())->first(fn ($c) => $c->getName() === SuiviVisites::COOKIE_VISITEUR);
        $this->assertNotNull($cookie);
        $this->assertLessThanOrEqual(now()->addDays(395)->getTimestamp() + 60, $cookie->getExpiresTime());
    }

    public function test_les_visites_de_plus_de_25_mois_sont_supprimees(): void
    {
        $ancienne = $this->visite(now()->subMonths(26));
        $recente = $this->visite(now()->subMonths(24));
        $ancienne->pageViews()->create(['path' => '/', 'viewed_at' => $ancienne->started_at]);

        $this->artisan('visites:purger')->assertSuccessful();

        $this->assertModelMissing($ancienne);
        $this->assertModelExists($recente);
        $this->assertDatabaseCount('site_page_views', 0);
    }

    public function test_les_statistiques_regroupent_les_visites_par_jour(): void
    {
        $this->visite(now()->subDays(2), visiteur: 'a', formulaires: 1);
        $this->visite(now()->subDays(2), visiteur: 'a');
        $this->visite(now()->subDay(), visiteur: 'b');

        $stats = StatistiquesVisites::depuisRequete(Request::create('/', 'GET', ['periode' => '7j']));
        $resume = $stats->resume();
        $serie = $stats->serie();

        $this->assertSame(3, $resume['visites']);
        $this->assertSame(2, $resume['visiteurs']);
        $this->assertSame(1, $resume['avecFormulaire']);
        $this->assertCount(7, $serie, 'Une ligne par jour, y compris les jours sans visite');
        $this->assertSame(2, $serie->firstWhere('cle', now()->subDays(2)->toDateString())['visites']);
        $this->assertSame(0, $serie->firstWhere('cle', now()->toDateString())['visites']);
    }

    private function visite($date, string $visiteur = 'x', int $formulaires = 0): SiteVisit
    {
        $visite = SiteVisit::create([
            'visitor_id' => str_pad($visiteur, 8, '0').'-0000-4000-8000-000000000000',
            'started_at' => $date,
            'last_activity_at' => $date,
            'landing_path' => '/',
            'source' => 'direct',
            'device' => 'mobile',
            'pages_count' => 1,
        ]);

        for ($i = 0; $i < $formulaires; $i++) {
            $visite->formulaires()->create(['formulaire_type' => 'contact_message', 'formulaire_id' => ContactMessage::factory()->create()->id, 'submitted_at' => $date]);
        }

        return $visite;
    }
}
