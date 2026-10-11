<?php

namespace App\Exports;

use App\Models\Visite;
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
 * Export Excel des rendez-vous programmés (onglet "Rendez-vous" du Journal des visites).
 */
class VisitesExport implements FromCollection, WithColumnWidths, WithFreezePane, WithHeadings, WithMapping, WithStrictNullComparison, WithStyles, WithTitle
{
    private const STATUTS = [
        'a_confirmer' => 'À confirmer',
        'confirmee' => 'Confirmée',
        'realisee' => 'Réalisée',
        'absent' => 'Absent',
        'annulee' => 'Annulée',
    ];

    public function __construct(private Collection $visites) {}

    public function collection(): Collection
    {
        return $this->visites;
    }

    public function title(): string
    {
        return 'Rendez-vous';
    }

    public function headings(): array
    {
        return ['Date', 'Heure', 'Visiteur', 'Téléphone', 'Email', 'Type de rendez-vous', 'Dossier lié', 'Statut', 'Compte rendu'];
    }

    /**
     * @param  Visite  $visite
     */
    public function map($visite): array
    {
        return [
            ucfirst($visite->visit_date->translatedFormat('l d/m/Y')),
            $visite->heureLabel(),
            $visite->visitor_name,
            $visite->phone,
            $visite->email,
            $visite->typeLabel(),
            $visite->dossierNumero(),
            self::STATUTS[$visite->status] ?? $visite->status,
            $visite->internal_note,
        ];
    }

    public function columnWidths(): array
    {
        return ['A' => 22, 'B' => 9, 'C' => 30, 'D' => 16, 'E' => 30, 'F' => 28, 'G' => 16, 'H' => 13, 'I' => 70];
    }

    public function freezePane(): string
    {
        return 'A2';
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('I:I')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:I')->getAlignment()->setVertical('top');

        return [
            'A1:I1' => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '217B79']],
            ],
        ];
    }
}
