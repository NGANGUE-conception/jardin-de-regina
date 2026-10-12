<?php

namespace App\Exports\SiteVisits;

use App\Models\SitePageView;
use App\Models\SiteVisit;
use App\Models\SiteVisitForm;
use App\Support\StatistiquesVisites;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithFreezePane;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Onglet "Détail des visites" : une ligne par visite, avec les infos du formulaire s'il y en a un.
 */
class DetailSheet implements FromQuery, WithColumnWidths, WithFreezePane, WithHeadings, WithMapping, WithStrictNullComparison, WithStyles, WithTitle
{
    public function __construct(private StatistiquesVisites $stats) {}

    public function title(): string
    {
        return 'Détail des visites';
    }

    public function query(): Builder
    {
        return $this->stats->requete()->with(['pageViews', 'formulaires.formulaire']);
    }

    public function headings(): array
    {
        return [
            'Date',
            "Heure d'arrivée",
            'Durée',
            'Provenance',
            'Appareil',
            'Navigateur',
            'Visiteur',
            'Pages vues',
            'Parcours',
            'Formulaire envoyé',
            'N° de dossier',
            'Nom',
            'Email',
            'Téléphone',
            'Détails du formulaire',
        ];
    }

    /**
     * @param  SiteVisit  $visite
     */
    public function map($visite): array
    {
        $formulaires = $visite->formulaires->map->resume();
        $joindre = fn (string $cle) => $formulaires->pluck($cle)->filter()->implode("\n");

        return [
            $visite->started_at->format('d/m/Y'),
            $visite->started_at->format('H:i'),
            $visite->dureeLabel(),
            $visite->provenance(),
            $visite->appareilLabel(),
            trim($visite->browser.' / '.$visite->os, ' /'),
            $visite->is_returning ? 'Déjà venu' : 'Nouveau',
            $visite->pages_count,
            $visite->pageViews->map(fn (SitePageView $vue) => SitePageView::libelle($vue->path))->implode(' → '),
            $formulaires->isEmpty() ? 'Non' : $joindre('type'),
            $joindre('numero'),
            $joindre('nom'),
            $joindre('email'),
            $joindre('tel'),
            $formulaires->map(fn ($r) => SiteVisitForm::detailsEnLigne($r))->filter()->implode("\n"),
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12, 'B' => 14, 'C' => 13, 'D' => 26, 'E' => 12, 'F' => 20, 'G' => 11, 'H' => 11,
            'I' => 60, 'J' => 20, 'K' => 16, 'L' => 24, 'M' => 30, 'N' => 16, 'O' => 60,
        ];
    }

    public function freezePane(): string
    {
        return 'A2';
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('I:I')->getAlignment()->setWrapText(true);
        $sheet->getStyle('J:O')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:O')->getAlignment()->setVertical('top');

        return [
            'A1:O1' => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1C6E6C']],
            ],
        ];
    }
}
