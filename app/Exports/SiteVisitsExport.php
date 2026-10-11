<?php

namespace App\Exports;

use App\Exports\SiteVisits\DetailSheet;
use App\Exports\SiteVisits\FormulairesSheet;
use App\Exports\SiteVisits\SyntheseSheet;
use App\Support\StatistiquesVisites;
use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

/**
 * Rapport Excel des visites du site : synthèse, détail de chaque visite, formulaires envoyés.
 */
class SiteVisitsExport implements Export, WithMultipleSheets
{
    public function __construct(private StatistiquesVisites $stats) {}

    public function sheets(): array
    {
        return [
            new SyntheseSheet($this->stats),
            new DetailSheet($this->stats),
            new FormulairesSheet($this->stats),
        ];
    }
}
