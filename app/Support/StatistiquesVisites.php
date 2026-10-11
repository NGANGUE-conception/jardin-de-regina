<?php

namespace App\Support;

use App\Models\SitePageView;
use App\Models\SiteVisit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Statistiques des visites du site sur une période, regroupées par jour, semaine ou mois.
 * Utilisée par la page admin "Visites du site" et par son export Excel, pour que les
 * chiffres soient toujours identiques entre les deux.
 */
class StatistiquesVisites
{
    public const PERIODES = [
        '7j' => '7 derniers jours',
        '30j' => '30 derniers jours',
        'mois' => 'Ce mois-ci',
        'mois_precedent' => 'Mois dernier',
        '12m' => '12 derniers mois',
        'perso' => 'Dates choisies',
    ];

    public const REGROUPEMENTS = [
        'jour' => 'Par jour',
        'semaine' => 'Par semaine',
        'mois' => 'Par mois',
    ];

    private ?Collection $visites = null;

    public function __construct(
        public readonly Carbon $debut,
        public readonly Carbon $fin,
        public readonly string $periode,
        public readonly string $regroupement,
    ) {}

    /**
     * Construit la période à partir des paramètres de l'URL (?periode=...&regroupement=...&du=...&au=...).
     */
    public static function depuisRequete(Request $request): self
    {
        $request->validate([
            'periode' => ['nullable', 'in:'.implode(',', array_keys(self::PERIODES))],
            'regroupement' => ['nullable', 'in:'.implode(',', array_keys(self::REGROUPEMENTS))],
            'du' => ['nullable', 'date'],
            'au' => ['nullable', 'date', 'after_or_equal:du'],
        ]);

        $periode = $request->input('periode')
            ?? ($request->filled('du') || $request->filled('au') ? 'perso' : '30j');

        $aujourdhui = now();

        [$debut, $fin] = match ($periode) {
            '7j' => [$aujourdhui->copy()->subDays(6), $aujourdhui->copy()],
            'mois' => [$aujourdhui->copy()->startOfMonth(), $aujourdhui->copy()],
            'mois_precedent' => [
                $aujourdhui->copy()->subMonthNoOverflow()->startOfMonth(),
                $aujourdhui->copy()->subMonthNoOverflow()->endOfMonth(),
            ],
            '12m' => [$aujourdhui->copy()->subMonthsNoOverflow(11)->startOfMonth(), $aujourdhui->copy()],
            'perso' => [
                $request->filled('du') ? Carbon::parse($request->input('du')) : $aujourdhui->copy()->subDays(29),
                $request->filled('au') ? Carbon::parse($request->input('au')) : $aujourdhui->copy(),
            ],
            default => [$aujourdhui->copy()->subDays(29), $aujourdhui->copy()],
        };

        $regroupement = $request->input('regroupement') ?? ($periode === '12m' ? 'mois' : 'jour');

        return new self($debut->startOfDay(), $fin->endOfDay(), $periode, $regroupement);
    }

    public function libellePeriode(): string
    {
        return 'du '.$this->debut->translatedFormat('j F Y').' au '.$this->fin->translatedFormat('j F Y');
    }

    /**
     * Requête de base : visites commencées dans la période, les plus récentes d'abord.
     */
    public function requete(): Builder
    {
        return SiteVisit::query()
            ->whereBetween('started_at', [$this->debut, $this->fin])
            ->orderByDesc('started_at');
    }

    /**
     * Toutes les visites de la période (colonnes légères, nombre de formulaires inclus).
     */
    public function visites(): Collection
    {
        return $this->visites ??= $this->requete()
            ->withCount('formulaires')
            ->get(['id', 'visitor_id', 'is_returning', 'started_at', 'source', 'device', 'pages_count']);
    }

    /**
     * Chiffres clés de la période.
     */
    public function resume(): array
    {
        $visites = $this->visites();
        $total = $visites->count();
        $avecFormulaire = $visites->where('formulaires_count', '>', 0)->count();

        return [
            'visites' => $total,
            'visiteurs' => $visites->pluck('visitor_id')->unique()->count(),
            'nouveaux' => $visites->where('is_returning', false)->count(),
            'pagesVues' => (int) $visites->sum('pages_count'),
            'pagesParVisite' => $total ? round($visites->avg('pages_count'), 1) : 0,
            'avecFormulaire' => $avecFormulaire,
            'formulaires' => (int) $visites->sum('formulaires_count'),
            'tauxConversion' => $this->pourcentage($avecFormulaire, $total),
            'mobilePct' => $this->pourcentage($visites->where('device', 'mobile')->count(), $total),
        ];
    }

    /**
     * Une ligne par jour / semaine / mois de la période, y compris ceux sans visite (pour le graphique).
     */
    public function serie(): Collection
    {
        $parCle = $this->visites()->groupBy(fn (SiteVisit $v) => $this->cleRegroupement($v->started_at));

        $serie = collect();
        $curseur = $this->debutRegroupement($this->debut->copy());

        while ($curseur->lte($this->fin)) {
            $cle = $this->cleRegroupement($curseur);
            $groupe = $parCle->get($cle, collect());
            $total = $groupe->count();
            $avecFormulaire = $groupe->where('formulaires_count', '>', 0)->count();

            $serie->push([
                'cle' => $cle,
                'libelle' => $this->libelleRegroupement($curseur),
                'libelleCourt' => $this->libelleCourt($curseur),
                'visites' => $total,
                'visiteurs' => $groupe->pluck('visitor_id')->unique()->count(),
                'pagesVues' => (int) $groupe->sum('pages_count'),
                'avecFormulaire' => $avecFormulaire,
                'formulaires' => (int) $groupe->sum('formulaires_count'),
                'tauxConversion' => $this->pourcentage($avecFormulaire, $total),
            ]);

            $curseur = match ($this->regroupement) {
                'semaine' => $curseur->addWeek(),
                'mois' => $curseur->addMonthNoOverflow(),
                default => $curseur->addDay(),
            };
        }

        return $serie;
    }

    /**
     * Provenance des visites, de la plus fréquente à la moins fréquente.
     */
    public function sources(): Collection
    {
        $total = $this->visites()->count();

        return $this->visites()
            ->groupBy('source')
            ->map(fn ($groupe, $source) => [
                'source' => $source,
                'libelle' => SiteVisit::SOURCES[$source] ?? ucfirst($source),
                'visites' => $groupe->count(),
                'pct' => $this->pourcentage($groupe->count(), $total),
            ])
            ->sortByDesc('visites')
            ->values();
    }

    public function appareils(): Collection
    {
        $total = $this->visites()->count();

        return collect(SiteVisit::APPAREILS)
            ->map(function ($libelle, $device) use ($total) {
                $nombre = $this->visites()->where('device', $device)->count();

                return [
                    'libelle' => $libelle,
                    'visites' => $nombre,
                    'pct' => $this->pourcentage($nombre, $total),
                ];
            })
            ->values();
    }

    /**
     * Pages les plus consultées sur la période.
     */
    public function pages(int $limite = 8): Collection
    {
        return SitePageView::query()
            ->join('site_visits', 'site_visits.id', '=', 'site_page_views.site_visit_id')
            ->whereBetween('site_visits.started_at', [$this->debut, $this->fin])
            ->selectRaw('site_page_views.path, count(*) as vues')
            ->groupBy('site_page_views.path')
            ->orderByDesc('vues')
            ->limit($limite)
            ->get()
            ->map(fn ($ligne) => [
                'page' => SitePageView::libelle($ligne->path),
                'vues' => (int) $ligne->vues,
            ]);
    }

    private function cleRegroupement(Carbon $date): string
    {
        return match ($this->regroupement) {
            'semaine' => $date->copy()->startOfWeek(Carbon::MONDAY)->toDateString(),
            'mois' => $date->format('Y-m'),
            default => $date->toDateString(),
        };
    }

    private function debutRegroupement(Carbon $date): Carbon
    {
        return match ($this->regroupement) {
            'semaine' => $date->startOfWeek(Carbon::MONDAY),
            'mois' => $date->startOfMonth(),
            default => $date->startOfDay(),
        };
    }

    private function libelleRegroupement(Carbon $date): string
    {
        return match ($this->regroupement) {
            'semaine' => 'Semaine du '.$date->translatedFormat('j F Y'),
            'mois' => ucfirst($date->translatedFormat('F Y')),
            default => ucfirst($date->translatedFormat('l j F Y')),
        };
    }

    private function libelleCourt(Carbon $date): string
    {
        return match ($this->regroupement) {
            'semaine' => $date->format('d/m'),
            'mois' => ucfirst($date->translatedFormat('M y')),
            default => $date->format('d/m'),
        };
    }

    private function pourcentage(int|float $partie, int|float $total): float
    {
        return $total > 0 ? round($partie / $total * 100, 1) : 0.0;
    }
}
