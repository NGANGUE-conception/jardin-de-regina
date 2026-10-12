<?php

namespace App\Exports\SiteVisits;

use App\Models\SitePageView;
use App\Models\SiteVisitForm;
use App\Support\StatistiquesVisites;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithFreezePane;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Onglet "Formulaires envoyés" : une ligne par formulaire, avec la visite qui y a mené.
 */
class FormulairesSheet implements FromCollection, WithColumnWidths, WithFreezePane, WithHeadings, WithMapping, WithStrictNullComparison, WithStyles, WithTitle
{
    public function __construct(private StatistiquesVisites $stats) {}

    public function title(): string
    {
        return 'Formulaires envoyés';
    }

    public function collection(): Collection
    {
        return SiteVisitForm::query()
            ->whereHas('siteVisit', fn ($q) => $q->whereBetween('started_at', [$this->stats->debut, $this->stats->fin]))
            ->with(['formulaire', 'siteVisit.pageViews'])
            ->orderByDesc('submitted_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            "Date d'envoi",
            'Heure',
            'Type de formulaire',
            'N° de dossier',
            'Nom',
            'Email',
            'Téléphone',
            'Détails',
            'Provenance de la visite',
            'Appareil',
            "Pages vues avant l'envoi",
        ];
    }

    /**
     * @param  SiteVisitForm  $formulaire
     */
    public function map($formulaire): array
    {
        $resume = $formulaire->resume();
        $visite = $formulaire->siteVisit;

        return [
            $formulaire->submitted_at->format('d/m/Y'),
            $formulaire->submitted_at->format('H:i'),
            $resume['type'],
            $resume['numero'],
            $resume['nom'],
            $resume['email'],
            $resume['tel'],
            SiteVisitForm::detailsEnLigne($resume),
            $visite->provenance(),
            $visite->appareilLabel(),
            $visite->pageViews
                ->filter(fn (SitePageView $vue) => $vue->viewed_at->lte($formulaire->submitted_at))
                ->map(fn (SitePageView $vue) => SitePageView::libelle($vue->path))
                ->implode(' → '),
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 13, 'B' => 9, 'C' => 20, 'D' => 16, 'E' => 24, 'F' => 30, 'G' => 16,
            'H' => 60, 'I' => 26, 'J' => 12, 'K' => 60,
        ];
    }

    public function freezePane(): string
    {
        return 'A2';
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('H:H')->getAlignment()->setWrapText(true);
        $sheet->getStyle('K:K')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:K')->getAlignment()->setVertical('top');

        return [
            'A1:K1' => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1C6E6C']],
            ],
        ];
    }
}
