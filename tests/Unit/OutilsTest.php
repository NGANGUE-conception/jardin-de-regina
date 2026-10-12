<?php

namespace Tests\Unit;

use App\Http\Controllers\Admin\ParametresController;
use App\Models\SitePageView;
use App\Models\SiteVisit;
use App\Support\Etablissement;
use App\Support\Referencement;
use App\Support\SuiviVisites;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionMethod;
use Tests\TestCase;

/**
 * Petits outils de calcul et de mise en forme (sans base de données).
 */
class OutilsTest extends TestCase
{
    public static function heures(): array
    {
        return [['08:30', '8h30'], ['18:30', '18h30'], ['18:00', '18h'], ['09:05', '9h05']];
    }

    #[DataProvider('heures')]
    public function test_les_heures_sont_affichees_a_la_francaise(string $heure, string $attendu): void
    {
        $this->assertSame($attendu, Etablissement::heureLisible($heure));
    }

    public function test_les_coordonnees_par_defaut_sont_bien_formatees(): void
    {
        $etablissement = new Etablissement;

        $this->assertSame('+33767646184', $etablissement->telephoneLien());
        $this->assertSame('8h30 – 18h30', $etablissement->horaires());
        $this->assertSame('de 8h30 à 18h30', $etablissement->horairesTexte());
        $this->assertSame('4 bis rue des Écoles, Alfortville', $etablissement->adresseCourte());
    }

    public static function telephones(): array
    {
        return [['0767646184', '07 67 64 61 84'], ['+33 7 67 64 61 84', '07 67 64 61 84'], ['01.43.75.00.00', '01 43 75 00 00']];
    }

    #[DataProvider('telephones')]
    public function test_le_telephone_saisi_est_remis_en_forme(string $saisi, string $attendu): void
    {
        $formater = new ReflectionMethod(ParametresController::class, 'formaterTelephone');

        $this->assertSame($attendu, $formater->invoke(null, $saisi));
    }

    public function test_les_adresses_pour_google_utilisent_toujours_l_adresse_officielle_du_site(): void
    {
        config(['app.url' => 'https://lejardinderegina.fr']);

        $this->assertSame('https://lejardinderegina.fr/', Referencement::url('/'));
        $this->assertSame('https://lejardinderegina.fr/contact', Referencement::url('contact'));
        $this->assertSame('https://lejardinderegina.fr/tarifs', Referencement::urlCanonique(Request::create('http://www.autre-domaine.fr/tarifs?utm_source=x')));
    }

    public static function navigateurs(): array
    {
        return [
            'iPhone' => ['Mozilla/5.0 (iPhone; CPU iPhone OS 18_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.0 Mobile/15E148 Safari/604.1', 'mobile', 'Safari', 'iOS'],
            'tablette Android' => ['Mozilla/5.0 (Linux; Android 14; SM-X710) AppleWebKit/537.36 Chrome/140.0 Safari/537.36', 'tablette', 'Chrome', 'Android'],
            'téléphone Android' => ['Mozilla/5.0 (Linux; Android 14; Pixel 8) AppleWebKit/537.36 Chrome/140.0 Mobile Safari/537.36', 'mobile', 'Chrome', 'Android'],
            'Opera sur Windows' => ['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0 Safari/537.36 OPR/125.0', 'ordinateur', 'Opera', 'Windows'],
            'Edge sur Mac' => ['Mozilla/5.0 (Macintosh; Intel Mac OS X 14_5) AppleWebKit/537.36 Chrome/140.0 Safari/537.36 Edg/140.0', 'ordinateur', 'Edge', 'macOS'],
        ];
    }

    #[DataProvider('navigateurs')]
    public function test_l_appareil_le_navigateur_et_le_systeme_sont_reconnus(string $ua, string $appareil, string $navigateur, string $systeme): void
    {
        $suivi = new SuiviVisites;
        $appel = fn (string $methode) => (new ReflectionMethod($suivi, $methode))->invoke($suivi, $ua);

        $this->assertSame($appareil, $appel('appareil'));
        $this->assertSame($navigateur, $appel('navigateur'));
        $this->assertSame($systeme, $appel('systeme'));
    }

    public static function provenances(): array
    {
        return [
            ['https://www.google.com/', [], 'google'],
            ['android-app://com.google.android.googlequicksearchbox/', [], 'google'],
            ['https://www.bing.com/search?q=creche', [], 'bing'],
            ['https://l.facebook.com/', [], 'facebook'],
            ['https://monenfant.fr/web/guest/que-recherchez-vous', [], 'monenfant'],
            ['https://www.doctissimo.fr/', [], 'autre'],
            [null, [], 'direct'],
            [null, ['utm_source' => 'instagram'], 'instagram'],
        ];
    }

    #[DataProvider('provenances')]
    public function test_la_provenance_des_visiteurs_est_reconnue(?string $referent, array $parametres, string $attendu): void
    {
        $requete = Request::create('http://localhost/', 'GET', $parametres);
        if ($referent) {
            $requete->headers->set('referer', $referent);
        }

        [$source] = (new ReflectionMethod(SuiviVisites::class, 'provenance'))->invoke(new SuiviVisites, $requete);

        $this->assertSame($attendu, $source);
        $this->assertArrayHasKey($attendu, SiteVisit::SOURCES);
    }

    public function test_le_nom_des_pages_est_lisible(): void
    {
        $this->assertSame('Tarifs et aides', SitePageView::libelle('/tarifs'));
        $this->assertSame('/page-inconnue', SitePageView::libelle('/page-inconnue'));
    }
}
