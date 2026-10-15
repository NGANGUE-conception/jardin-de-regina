<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Vite;
use Tests\TestCase;

/**
 * En-têtes de sécurité envoyés avec chaque réponse (middleware EnTetesSecurite).
 */
class EnTetesSecuriteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Comme sur le serveur en ligne : fichiers compilés, pas de serveur Vite de développement
        // (même si « npm run dev » tourne sur le poste pendant les tests).
        Vite::useHotFile(storage_path('framework/testing/aucun-serveur-vite'));
    }

    public function test_les_pages_publiques_et_l_admin_envoient_les_en_tetes_de_securite(): void
    {
        foreach (['/', '/contact', '/admin/login', '/sitemap.xml', '/page-qui-n-existe-pas'] as $adresse) {
            $reponse = $this->get($adresse);

            $reponse->assertHeader('X-Frame-Options', 'DENY')
                ->assertHeader('X-Content-Type-Options', 'nosniff')
                ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
            $this->assertStringContainsString('camera=()', $reponse->headers->get('Permissions-Policy'), $adresse);
            $this->assertStringContainsString("default-src 'self'", $reponse->headers->get('Content-Security-Policy'), $adresse);
            $this->assertStringContainsString("frame-ancestors 'none'", $reponse->headers->get('Content-Security-Policy'), $adresse);
        }
    }

    public function test_https_impose_seulement_en_production_et_en_https(): void
    {
        $this->get('/')->assertHeaderMissing('Strict-Transport-Security');
        $this->get('https://localhost/')->assertHeaderMissing('Strict-Transport-Security');

        $this->app['env'] = 'production';

        $this->get('http://localhost/')->assertHeaderMissing('Strict-Transport-Security');
        $this->get('https://localhost/')->assertHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
    }

    public function test_pas_de_content_security_policy_pendant_npm_run_dev(): void
    {
        $fichierHot = storage_path('framework/testing/vite-hot');
        File::ensureDirectoryExists(dirname($fichierHot));
        File::put($fichierHot, 'http://localhost:5173');
        Vite::useHotFile($fichierHot);

        try {
            $this->get('/')
                ->assertHeaderMissing('Content-Security-Policy')
                ->assertHeader('X-Frame-Options', 'DENY');
        } finally {
            File::delete($fichierHot);
        }
    }
}
