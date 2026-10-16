<?php

namespace Tests\Feature;

use App\Mail\NewSubmissionAlert;
use App\Mail\SubmissionConfirmation;
use App\Models\ContactMessage;
use App\Support\PagesPubliques;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Version anglaise du site : adresses /en, boutons FR / EN, balises hreflang, sitemap, pages, formulaires, emails
 * et fichier de traduction lang/en.json complet.
 */
class TraductionAnglaiseTest extends TestCase
{
    use RefreshDatabase;

    public function test_les_adresses_francaises_restent_en_francais(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('<html lang="fr">', false)
            ->assertSee('Une bonne journée au Jardin de Régina')
            ->assertSee('href="'.url('/en').'"', false);
    }

    public function test_les_adresses_en_affichent_le_site_en_anglais(): void
    {
        $this->get('/en')
            ->assertOk()
            ->assertSee('<html lang="en">', false)
            ->assertSee('<meta property="og:locale" content="en_GB">', false)
            ->assertSee('A happy day at Le Jardin de Régina')
            ->assertSee('8:30 am – 6:30 pm')
            ->assertDontSee('Une bonne journée au Jardin de Régina');

        $this->get('/en/contact')->assertOk()->assertSee('Send my message');
        $this->get('/en/pre-inscription')->assertOk()->assertSee('Send my pre-registration request');
    }

    public function test_les_liens_restent_dans_la_langue_affichee(): void
    {
        $this->get('/en/tarifs')
            ->assertSee('href="'.url('/en/contact').'"', false)
            ->assertSee('href="'.url('/en/pre-inscription').'"', false)
            ->assertDontSee('href="'.url('/contact').'"', false);

        $this->get('/tarifs')
            ->assertSee('href="'.url('/contact').'"', false)
            ->assertDontSee('href="'.url('/en/contact').'"', false);
    }

    public function test_les_boutons_fr_en_menent_a_la_meme_page_dans_l_autre_langue(): void
    {
        $page = $this->get('/en/tarifs')->getContent();

        $this->assertMatchesRegularExpression('#href="'.preg_quote(url('/en/tarifs'), '#').'" hreflang="en"[^>]*aria-current="true"#s', $page);
        $this->assertMatchesRegularExpression('#href="'.preg_quote(url('/tarifs'), '#').'" hreflang="fr"#', $page);

        // Les paramètres de la page sont conservés (ex. numéro de dossier de la confirmation)
        $this->get('/pre-inscription/confirmation?dossier=%23JR-2026-0001')
            ->assertSee('href="'.url('/en/pre-inscription/confirmation').'?dossier=%23JR-2026-0001"', false);
    }

    public function test_google_connait_les_deux_versions_de_chaque_page(): void
    {
        foreach (['/contact', '/en/contact'] as $adresse) {
            $this->get($adresse)
                ->assertSee('<link rel="alternate" hreflang="fr" href="http://localhost/contact">', false)
                ->assertSee('<link rel="alternate" hreflang="en" href="http://localhost/en/contact">', false)
                ->assertSee('<link rel="alternate" hreflang="x-default" href="http://localhost/contact">', false);
        }
        $this->get('/en/contact')->assertSee('<link rel="canonical" href="http://localhost/en/contact">', false);

        $sitemap = $this->get('/sitemap.xml')->assertOk()->getContent();
        $this->assertStringContainsString('<loc>http://localhost/en/tarifs</loc>', $sitemap);
        $this->assertStringContainsString('<xhtml:link rel="alternate" hreflang="en" href="http://localhost/en/tarifs"/>', $sitemap);
        $this->assertSame(substr_count($sitemap, '<url>'), substr_count($sitemap, 'hreflang="x-default"'));
    }

    public function test_les_pages_legales_en_anglais_rappellent_que_seul_le_francais_fait_foi(): void
    {
        $avertissement = 'Only the French version is legally binding.';

        $this->get('/mentions-legales')->assertOk()->assertDontSee($avertissement);
        $this->get('/en/mentions-legales')->assertOk()->assertSee('Legal notice')->assertSee($avertissement)
            ->assertSee('href="'.url('/mentions-legales').'"', false);
    }

    public function test_la_page_404_suit_la_langue_de_l_adresse(): void
    {
        $this->get('/en/page-qui-n-existe-pas')->assertNotFound()->assertSee('Oops, this page is hiding in the garden!');
        $this->get('/page-qui-n-existe-pas')->assertNotFound()->assertSee('Oups, cette page s&#039;est cachée dans le jardin !', false);
    }

    public function test_l_espace_admin_reste_en_francais(): void
    {
        $this->get('/admin/login')->assertOk()->assertSee('<html lang="fr">', false)->assertSee('Se connecter');
        $this->get('/en/admin/login')->assertNotFound();
    }

    public function test_un_formulaire_envoye_en_anglais(): void
    {
        Mail::fake();

        // Erreurs de saisie en anglais
        $this->from('/en/contact')->post('/en/contact', ['email' => 'pas-un-email'] + $this->antiSpam())
            ->assertRedirect('/en/contact')
            ->assertSessionHasErrors(['fullname' => 'Please fill in your full name.']);

        // Envoi valide : retour sur la page anglaise, valeur enregistrée en français (lue par l'admin),
        // email de la famille en anglais, alerte de la direction en français avec la langue utilisée.
        $this->post('/en/contact', [
            'fullname' => 'Emma Smith',
            'email' => 'emma.smith@example.com',
            'phone' => '06 12 34 56 78',
            'subject' => 'Demander une visite privée',
            'message' => 'Hello, we would like to visit the nursery.',
            'rgpd_consent' => '1',
        ] + $this->antiSpam())
            ->assertRedirect(url('/en/contact').'#contact-form')
            ->assertSessionHas('success', 'Thank you, your message has been sent. We will reply within 48 working hours.');

        $this->assertSame('Demander une visite privée', ContactMessage::sole()->subject);

        Mail::assertSent(SubmissionConfirmation::class, function (SubmissionConfirmation $mail) {
            $mail->assertSeeInHtml('Request a private visit');

            return $mail->hasTo('emma.smith@example.com')
                && $mail->titre === 'We have received your message'
                && $mail->locale === 'en';
        });
        Mail::assertSent(NewSubmissionAlert::class, fn (NewSubmissionAlert $mail) => $mail->locale === 'fr'
            && $mail->lignes['Langue du formulaire'] === 'Anglais');
    }

    public function test_une_pre_inscription_en_anglais_mene_a_la_confirmation_en_anglais(): void
    {
        Mail::fake();

        $reponse = $this->post('/en/pre-inscription', [
            'child_last_name' => 'Smith',
            'child_first_name' => 'Lily',
            'child_gender' => 'Fille',
            'child_birthdate' => '2025-05-01',
            'entry_date' => now()->addMonths(3)->format('Y-m-d'),
            'care_type' => 'regulier',
            'care_days' => ['lundi', 'mardi'],
            'p1_nom' => 'Smith',
            'p1_prenom' => 'Emma',
            'p1_email' => 'emma.smith@example.com',
            'p1_tel' => '06 12 34 56 78',
            'rgpd_consent' => '1',
        ] + $this->antiSpam());

        $this->assertStringStartsWith(url('/en/pre-inscription/confirmation'), $reponse->headers->get('Location'));
    }

    /**
     * Toute phrase marquée pour la traduction dans les pages publiques doit exister dans lang/en.json :
     * sinon, elle resterait en français sur le site anglais.
     */
    public function test_le_fichier_anglais_contient_toutes_les_phrases_du_site(): void
    {
        $traductions = json_decode(File::get(lang_path('en.json')), true);
        $this->assertIsArray($traductions);
        $this->assertSame([], array_keys(array_filter($traductions, fn ($anglais) => trim((string) $anglais) === '')), 'Traductions vides');

        $fichiers = collect(['pages', 'components', 'components/formulaire', 'errors'])
            ->flatMap(fn ($dossier) => File::glob(resource_path("views/$dossier/*.blade.php")))
            ->push(resource_path('views/layouts/app.blade.php'), resource_path('views/emails/submission-confirmation.blade.php'))
            ->reject(fn ($fichier) => preg_match('#(admin-sidebar|admin-topbar|journal-onglets)#', $fichier));

        $manquantes = [];
        foreach ($fichiers as $fichier) {
            preg_match_all('/__\(\s*(\'(?:[^\'\\\\]|\\\\.)*\'|"(?:[^"\\\\$]|\\\\.)*")/s', File::get($fichier), $appels);
            foreach ($appels[1] as $litteral) {
                $phrase = $litteral[0] === "'" ? str_replace(["\\'", '\\\\'], ["'", '\\'], substr($litteral, 1, -1)) : stripcslashes(substr($litteral, 1, -1));
                if (! str_starts_with($phrase, 'validation.') && ! array_key_exists($phrase, $traductions)) {
                    $manquantes[] = basename($fichier).' : '.$phrase;
                }
            }
        }

        foreach (PagesPubliques::parRubrique() as $rubrique => $pages) {
            foreach ([$rubrique, ...array_column($pages, 'titre'), ...array_column($pages, 'resume')] as $phrase) {
                if (! array_key_exists($phrase, $traductions)) {
                    $manquantes[] = 'PagesPubliques : '.$phrase;
                }
            }
        }

        $this->assertSame([], $manquantes, 'Phrases sans traduction anglaise');
    }
}
