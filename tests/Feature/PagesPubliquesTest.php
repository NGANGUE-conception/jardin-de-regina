<?php

namespace Tests\Feature;

use App\Support\PagesPubliques;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Pages publiques, référencement (balises, sitemap, robots.txt) et pages d'erreur.
 */
class PagesPubliquesTest extends TestCase
{
    use RefreshDatabase;

    public static function pagesPubliques(): array
    {
        return [
            'accueil' => ['/'],
            'notre projet' => ['/notre-projet'],
            'fonctionnement' => ['/fonctionnement'],
            'tarifs' => ['/tarifs'],
            'berceaux entreprise' => ['/berceaux-entreprise'],
            'contact' => ['/contact'],
            'recrutement' => ['/recrutement'],
            'pré-inscription' => ['/pre-inscription'],
            'mentions légales' => ['/mentions-legales'],
            'politique de confidentialité' => ['/politique-de-confidentialite'],
            'gestion des cookies' => ['/gestion-des-cookies'],
            'plan du site' => ['/plan-du-site'],
        ];
    }

    #[DataProvider('pagesPubliques')]
    public function test_chaque_page_publique_s_affiche_avec_ses_balises_de_referencement(string $adresse): void
    {
        $reponse = $this->get($adresse)->assertOk();
        $html = $reponse->getContent();

        $this->assertMatchesRegularExpression('/<title>[^<]{10,60}<\/title>/', $html, 'Titre manquant ou de plus de 60 caractères');
        $this->assertSame(1, substr_count($html, '<meta name="description"'), 'Une seule description attendue');
        $this->assertStringContainsString('<link rel="canonical" href="http://localhost'.($adresse === '/' ? '/' : $adresse).'">', $html);
        $this->assertStringContainsString('<meta property="og:image" content="http://localhost/images/partage.jpg">', $html);
        $this->assertStringContainsString('<meta name="robots" content="index, follow', $html);
        $this->assertStringContainsString('"@type": "ChildCare"', $html);
        $this->assertStringNotContainsString('fonts.googleapis.com', $html, 'Les polices doivent être hébergées sur le site (RGPD)');
    }

    public function test_les_coordonnees_de_la_creche_sont_affichees(): void
    {
        $this->get('/contact')
            ->assertOk()
            ->assertSee('07 67 64 61 84')
            ->assertSee('tel:+33767646184', false)
            ->assertSee('jardinderegina94@gmail.com')
            ->assertSee('4 bis rue des Écoles, 94140 Alfortville');
    }

    public function test_la_page_de_confirmation_n_est_pas_indexee_par_google(): void
    {
        $this->get('/pre-inscription/confirmation?dossier=%23JR-2026-0001')
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex, follow">', false)
            ->assertSee('<link rel="canonical" href="http://localhost/pre-inscription/confirmation">', false);
    }

    public function test_le_sitemap_liste_toutes_les_pages_publiques_et_aucune_page_privee(): void
    {
        $reponse = $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

        $xml = simplexml_load_string($reponse->getContent());
        $this->assertNotFalse($xml, 'Le sitemap doit être un XML valide');

        $adresses = array_map('strval', array_column(json_decode(json_encode($xml), true)['url'], 'loc'));
        $this->assertCount(PagesPubliques::pourSitemap()->count(), $adresses);
        $this->assertContains('http://localhost/', $adresses);
        $this->assertContains('http://localhost/pre-inscription', $adresses);
        foreach ($adresses as $adresse) {
            $this->assertStringNotContainsString('/admin', $adresse);
            $this->assertStringNotContainsString('confirmation', $adresse);
        }
    }

    public function test_robots_txt_interdit_tout_hors_production(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee("Disallow: /\n", false)
            ->assertDontSee('Sitemap:');
    }

    public function test_robots_txt_en_production_interdit_seulement_l_admin_et_indique_le_sitemap(): void
    {
        $this->app['env'] = 'production';

        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Disallow: /admin')
            ->assertSee('Sitemap: http://localhost/sitemap.xml');
    }

    public function test_une_page_inconnue_affiche_la_page_404_en_francais(): void
    {
        $this->get('/une-page-qui-n-existe-pas')
            ->assertNotFound()
            ->assertSee('cette page s&#039;est cachée dans le jardin', false)
            ->assertSee('<meta name="robots" content="noindex">', false);
    }

    public function test_une_erreur_serveur_affiche_la_page_500_sans_detail_technique(): void
    {
        $this->app['router']->get('/__test-erreur', fn () => throw new \RuntimeException('Détail technique secret'))->middleware('web');

        $this->get('/__test-erreur')
            ->assertStatus(500)
            ->assertSee('Un petit souci technique de notre côté')
            ->assertDontSee('Détail technique secret');
    }

    public function test_les_icones_du_site_existent(): void
    {
        foreach (['favicon.ico', 'favicon-96x96.png', 'apple-touch-icon.png', 'icon-192.png', 'icon-512.png', 'icon-maskable-512.png', 'site.webmanifest', 'images/partage.jpg'] as $fichier) {
            $this->assertFileExists(public_path($fichier));
        }
        $this->assertSame([1200, 630], array_slice(getimagesize(public_path('images/partage.jpg')), 0, 2));
    }
}
