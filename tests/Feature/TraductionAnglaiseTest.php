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
 * Version anglaise du site : boutons FR / EN, mémorisation du choix, pages, formulaires, emails
 * et fichier de traduction lang/en.json complet.
 */
class TraductionAnglaiseTest extends TestCase
{
    use RefreshDatabase;

    private function enAnglais(): static
    {
        return $this->withUnencryptedCookie('jr_langue', 'en');
    }

    public function test_le_site_est_en_francais_par_defaut(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('<html lang="fr">', false)
            ->assertSee('Une bonne journée au Jardin de Régina')
            ->assertSee('href="'.route('langue', 'en').'"', false);
    }

    public function test_le_bouton_en_memorise_l_anglais_et_revient_sur_la_meme_page(): void
    {
        $reponse = $this->get('/langue/en', ['referer' => url('/tarifs')]);

        $reponse->assertRedirect(url('/tarifs'));
        $cookie = $reponse->getCookie('jr_langue', false);
        $this->assertSame('en', $cookie->getValue());
        $this->assertGreaterThan(now()->addMonths(11)->getTimestamp(), $cookie->getExpiresTime());
    }

    public function test_le_retour_ne_mene_jamais_vers_un_autre_site(): void
    {
        $this->get('/langue/en', ['referer' => 'https://site-malveillant.example/piege'])->assertRedirect(route('accueil'));
        $this->get('/langue/fr')->assertRedirect(route('accueil'));
    }

    public function test_une_langue_inconnue_est_refusee(): void
    {
        $this->get('/langue/de')->assertNotFound();
        $this->withUnencryptedCookie('jr_langue', 'de')->get('/')->assertSee('<html lang="fr">', false);
    }

    public function test_les_pages_s_affichent_en_anglais(): void
    {
        $this->enAnglais()->get('/')
            ->assertOk()
            ->assertSee('<html lang="en">', false)
            ->assertSee('<meta property="og:locale" content="en_GB">', false)
            ->assertSee('A happy day at Le Jardin de Régina')
            ->assertSee('8:30 am – 6:30 pm')
            ->assertDontSee('Une bonne journée au Jardin de Régina');

        $this->enAnglais()->get('/contact')->assertOk()->assertSee('Send my message');
        $this->enAnglais()->get('/pre-inscription')->assertOk()->assertSee('Send my pre-registration request');
    }

    public function test_le_bouton_de_la_langue_affichee_est_signale_comme_actif(): void
    {
        $page = $this->enAnglais()->get('/')->getContent();

        $this->assertMatchesRegularExpression('#href="'.preg_quote(route('langue', 'en'), '#').'"[^>]*aria-current="true"#s', $page);
        $this->assertDoesNotMatchRegularExpression('#href="'.preg_quote(route('langue', 'fr'), '#').'"[^>]*aria-current="true"#s', $page);
    }

    public function test_les_pages_legales_en_anglais_rappellent_que_seul_le_francais_fait_foi(): void
    {
        $avertissement = 'Only the French version is legally binding.';

        // Français d'abord : le cookie de langue posé ensuite reste actif pour les requêtes suivantes du test.
        $this->get('/mentions-legales')->assertOk()->assertDontSee($avertissement);
        $this->enAnglais()->get('/mentions-legales')->assertOk()->assertSee('Legal notice')->assertSee($avertissement);
    }

    public function test_la_page_404_suit_la_langue_choisie(): void
    {
        $this->enAnglais()->get('/page-qui-n-existe-pas')
            ->assertNotFound()
            ->assertSee('Oops, this page is hiding in the garden!');
    }

    public function test_l_espace_admin_reste_en_francais(): void
    {
        $this->enAnglais()->get('/admin/login')->assertOk()->assertSee('<html lang="fr">', false)->assertSee('Se connecter');
    }

    public function test_un_formulaire_envoye_en_anglais(): void
    {
        Mail::fake();

        // Erreurs de saisie en anglais
        $this->enAnglais()->post('/contact', ['email' => 'pas-un-email'] + $this->antiSpam())
            ->assertSessionHasErrors(['fullname' => 'Please fill in your full name.']);

        // Envoi valide : valeur enregistrée en français (lue par l'admin), email de la famille en anglais,
        // alerte de la direction en français avec la langue utilisée.
        $this->enAnglais()->post('/contact', [
            'fullname' => 'Emma Smith',
            'email' => 'emma.smith@example.com',
            'phone' => '06 12 34 56 78',
            'subject' => 'Demander une visite privée',
            'message' => 'Hello, we would like to visit the nursery.',
            'rgpd_consent' => '1',
        ] + $this->antiSpam())->assertSessionHas('success', 'Thank you, your message has been sent. We will reply within 48 working hours.');

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
