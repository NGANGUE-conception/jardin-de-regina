<?php

namespace App\Support;

use App\Models\SiteVisit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Mesure d'audience maison du site public, conforme à l'exemption de consentement de la CNIL :
 *  - aucune adresse IP enregistrée, aucun suivi sur d'autres sites ;
 *  - identifiant de visiteur aléatoire (cookie de 13 mois maximum, jamais prolongé) ;
 *  - données supprimées après 25 mois (commande "visites:purger") ;
 *  - possibilité de refuser d'être compté (page Gestion des cookies).
 *
 * Une "visite" = une suite de pages vues sans interruption de plus de 30 minutes.
 */
class SuiviVisites
{
    public const COOKIE_VISITEUR = 'jr_visiteur';

    public const COOKIE_REFUS = 'jr_refus_stats';

    /** 13 mois, durée maximale recommandée par la CNIL pour un cookie de mesure d'audience. */
    public const DUREE_COOKIE_MINUTES = 60 * 24 * 395;

    /** Durée de conservation des visites (25 mois, recommandation CNIL). */
    public const CONSERVATION_MOIS = 25;

    private const SESSION_CLE = 'suivi_visite';

    private const INACTIVITE_MINUTES = 30;

    private const ROBOTS = '/bot|crawl|spider|slurp|mediapartners|facebookexternalhit|whatsapp|telegram|discord|skype|embedly|preview|headless|lighthouse|pagespeed|gtmetrix|pingdom|uptime|monitor|curl|wget|python|java\/|go-http|httpclient|okhttp|axios|node-fetch|guzzle|scrapy|feedfetcher|validator/i';

    /**
     * Faut-il compter cette page ? Uniquement les pages publiques HTML affichées
     * avec succès à un vrai visiteur (pas un robot, pas l'admin connecté).
     */
    public function doitSuivre(Request $request, Response $response): bool
    {
        if (! $request->isMethod('GET') || $request->ajax() || $request->expectsJson() || $request->prefetch()) {
            return false;
        }

        if ($request->is('admin', 'admin/*', 'up', 'storage/*', 'livewire*')) {
            return false;
        }

        if (Auth::check() || $request->cookie(self::COOKIE_REFUS)) {
            return false;
        }

        $navigateur = (string) $request->userAgent();
        if ($navigateur === '' || preg_match(self::ROBOTS, $navigateur)) {
            return false;
        }

        return $response->getStatusCode() === 200
            && str_contains((string) $response->headers->get('Content-Type'), 'text/html');
    }

    /**
     * Enregistre la page vue, en créant la visite si besoin.
     */
    public function enregistrerPage(Request $request, Response $response): void
    {
        $visiteurId = $request->cookie(self::COOKIE_VISITEUR);
        $nouveauVisiteur = ! is_string($visiteurId) || ! Str::isUuid($visiteurId);

        if ($nouveauVisiteur) {
            $visiteurId = (string) Str::uuid();
        }

        $maintenant = now();
        $visite = $this->visiteEnCours($request, $maintenant->timestamp);

        if (! $visite) {
            $navigateur = (string) $request->userAgent();
            [$source, $hoteReferent, $campagne] = $this->provenance($request);

            $visite = SiteVisit::create([
                'visitor_id' => $visiteurId,
                'is_returning' => ! $nouveauVisiteur,
                'started_at' => $maintenant,
                'last_activity_at' => $maintenant,
                'landing_path' => $this->chemin($request),
                'source' => $source,
                'referrer_host' => $hoteReferent,
                'campaign' => $campagne,
                'device' => $this->appareil($navigateur),
                'browser' => $this->navigateur($navigateur),
                'os' => $this->systeme($navigateur),
                'pages_count' => 0,
            ]);
        }

        $visite->pageViews()->create([
            'path' => $this->chemin($request),
            'viewed_at' => $maintenant,
        ]);

        $visite->forceFill([
            'last_activity_at' => $maintenant,
            'pages_count' => $visite->pages_count + 1,
        ])->save();

        $request->session()->put(self::SESSION_CLE, [
            'id' => $visite->id,
            'derniere_activite' => $maintenant->timestamp,
        ]);

        // Le cookie n'est déposé qu'une fois : sa durée n'est jamais prolongée (exigence CNIL).
        if ($nouveauVisiteur) {
            $response->headers->setCookie(cookie(self::COOKIE_VISITEUR, $visiteurId, self::DUREE_COOKIE_MINUTES));
        }
    }

    /**
     * Relie un formulaire envoyé (pré-inscription, devis, candidature, contact) à la visite en cours.
     * Ne bloque jamais l'envoi du formulaire en cas de problème.
     */
    public function rattacherFormulaire(Request $request, Model $formulaire): void
    {
        try {
            $id = $request->session()->get(self::SESSION_CLE.'.id');
            $visite = $id ? SiteVisit::find($id) : null;

            if (! $visite) {
                return;
            }

            $visite->formulaires()->create([
                'formulaire_type' => $formulaire->getMorphClass(),
                'formulaire_id' => $formulaire->getKey(),
                'submitted_at' => now(),
            ]);

            $visite->forceFill(['last_activity_at' => now()])->save();
        } catch (Throwable $e) {
            report($e);
        }
    }

    public function oublierVisiteEnCours(Request $request): void
    {
        $request->session()->forget(self::SESSION_CLE);
    }

    private function visiteEnCours(Request $request, int $maintenant): ?SiteVisit
    {
        $enCours = $request->session()->get(self::SESSION_CLE);

        if (! is_array($enCours) || $maintenant - ($enCours['derniere_activite'] ?? 0) > self::INACTIVITE_MINUTES * 60) {
            return null;
        }

        return SiteVisit::find($enCours['id'] ?? null);
    }

    private function chemin(Request $request): string
    {
        return Str::limit('/'.ltrim($request->path(), '/'), 250, '');
    }

    /**
     * @return array{0: string, 1: ?string, 2: ?string} [source, site d'origine, campagne]
     */
    private function provenance(Request $request): array
    {
        $campagne = Str::limit(trim((string) $request->query('utm_campaign')), 100, '') ?: null;

        $hote = null;
        $referent = $request->headers->get('referer');
        if ($referent) {
            $hote = strtolower((string) parse_url($referent, PHP_URL_HOST));
            $hote = preg_replace('/^(www|m|l|lm)\./', '', $hote) ?: null;

            // Navigation interne après une pause de plus de 30 minutes : on la considère comme un accès direct.
            if ($hote === preg_replace('/^www\./', '', strtolower($request->getHost()))) {
                $hote = null;
            }
        }

        $utmSource = trim((string) $request->query('utm_source'));

        $source = match (true) {
            $utmSource !== '' => $this->normaliserSource($utmSource),
            $hote === null => 'direct',
            default => $this->normaliserSource($hote),
        };

        return [$source, $hote ? Str::limit($hote, 190, '') : null, $campagne];
    }

    private function normaliserSource(string $valeur): string
    {
        $valeur = strtolower($valeur);

        $correspondances = [
            'google' => 'google',
            'bing' => 'bing',
            'qwant' => 'qwant',
            'ecosia' => 'ecosia',
            'duckduckgo' => 'duckduckgo',
            'yahoo' => 'yahoo',
            'facebook' => 'facebook',
            'fb.' => 'facebook',
            'instagram' => 'instagram',
            'linkedin' => 'linkedin',
            'lnkd.in' => 'linkedin',
            'tiktok' => 'tiktok',
            'youtube' => 'youtube',
            'monenfant' => 'monenfant',
            'pagesjaunes' => 'pagesjaunes',
        ];

        foreach ($correspondances as $motif => $source) {
            if (str_contains($valeur, $motif)) {
                return $source;
            }
        }

        return 'autre';
    }

    private function appareil(string $ua): string
    {
        return match (true) {
            (bool) preg_match('/iPad|Tablet|PlayBook|Silk|Kindle|Android(?!.*Mobile)/i', $ua) => 'tablette',
            (bool) preg_match('/Mobi|iPhone|iPod|Android|Windows Phone|Opera Mini/i', $ua) => 'mobile',
            default => 'ordinateur',
        };
    }

    private function navigateur(string $ua): string
    {
        return match (true) {
            (bool) preg_match('/Edg(e|A|iOS)?\//', $ua) => 'Edge',
            (bool) preg_match('/OPR\/|Opera/', $ua) => 'Opera',
            str_contains($ua, 'SamsungBrowser') => 'Samsung Internet',
            (bool) preg_match('/Firefox|FxiOS/', $ua) => 'Firefox',
            (bool) preg_match('/Chrome|CriOS/', $ua) => 'Chrome',
            str_contains($ua, 'Safari') => 'Safari',
            default => 'Autre',
        };
    }

    private function systeme(string $ua): string
    {
        return match (true) {
            str_contains($ua, 'Windows') => 'Windows',
            (bool) preg_match('/iPhone|iPad|iPod/', $ua) => 'iOS',
            str_contains($ua, 'Android') => 'Android',
            str_contains($ua, 'Mac OS X') => 'macOS',
            str_contains($ua, 'CrOS') => 'ChromeOS',
            str_contains($ua, 'Linux') => 'Linux',
            default => 'Autre',
        };
    }
}
