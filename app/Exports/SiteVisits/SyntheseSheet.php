<?php

namespace App\Exports\SiteVisits;

use App\Support\StatistiquesVisites;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Onglet "Synthèse" : totaux par jour / semaine / mois, provenance, appareils et pages les plus vues.
 */
class SyntheseSheet implements FromArray, WithColumnWidths, WithStrictNullComparison, WithStyles, WithTitle
{
    /** Lignes à mettre en forme : [numéro de ligne, nombre de colonnes]. */
    private array $lignesEntetes = [];

    private array $lignesTotaux = [];

    public function __construct(private StatistiquesVisites $stats) {}

    public function title(): string
    {
        return 'Synthèse';
    }

    public function array(): array
    {
        $resume = $this->stats->resume();
        $lignes = [
            ['Visites du site — Le Jardin de Régina'],
            ['Période', ucfirst($this->stats->libellePeriode())],
            ['Regroupement', StatistiquesVisites::REGROUPEMENTS[$this->stats->regroupement]],
            ['Rapport généré le', now()->translatedFormat('j F Y à H\hi')],
            [],
            ['Chiffres clés'],
            ['Visites', $resume['visites']],
            ['Visiteurs uniques', $resume['visiteurs']],
            ['Nouveaux visiteurs', $resume['nouveaux']],
            ['Pages vues', $resume['pagesVues']],
            ['Pages vues par visite', $resume['pagesParVisite']],
            ['Visites avec formulaire envoyé', $resume['avecFormulaire']],
            ['Taux de conversion (%)', $resume['tauxConversion']],
            ['Visites sur mobile (%)', $resume['mobilePct']],
            [],
        ];
        $this->lignesEntetes[] = [6, 2];

        $lignes[] = ['Période', 'Visites', 'Visiteurs uniques', 'Pages vues', 'Visites avec formulaire', 'Formulaires envoyés', 'Taux de conversion (%)'];
        $this->lignesEntetes[] = [count($lignes), 7];

        foreach ($this->stats->serie() as $ligne) {
            $lignes[] = [
                $ligne['libelle'],
                $ligne['visites'],
                $ligne['visiteurs'],
                $ligne['pagesVues'],
                $ligne['avecFormulaire'],
                $ligne['formulaires'],
                $ligne['tauxConversion'],
            ];
        }

        $lignes[] = ['Total', $resume['visites'], $resume['visiteurs'], $resume['pagesVues'], $resume['avecFormulaire'], $resume['formulaires'], $resume['tauxConversion']];
        $this->lignesTotaux[] = [count($lignes), 7];

        $this->ajouterTableau($lignes, ['Provenance', 'Visites', 'Part (%)'], $this->stats->sources()->map(
            fn ($s) => [$s['libelle'], $s['visites'], $s['pct']]
        )->all());

        $this->ajouterTableau($lignes, ['Appareil', 'Visites', 'Part (%)'], $this->stats->appareils()->map(
            fn ($a) => [$a['libelle'], $a['visites'], $a['pct']]
        )->all());

        $this->ajouterTableau($lignes, ['Page', 'Vues'], $this->stats->pages(20)->map(
            fn ($p) => [$p['page'], $p['vues']]
        )->all());

        return $lignes;
    }

    public function columnWidths(): array
    {
        return ['A' => 38, 'B' => 30, 'C' => 18, 'D' => 14, 'E' => 24, 'F' => 22, 'G' => 24];
    }

    public function styles(Worksheet $sheet): array
    {
        $styles = [
            1 => ['font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1C6E6C']]],
            'A2:A4' => ['font' => ['bold' => true]],
        ];

        foreach ($this->lignesEntetes as [$ligne, $colonnes]) {
            $styles['A'.$ligne.':'.chr(64 + $colonnes).$ligne] = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1C6E6C']],
            ];
        }

        foreach ($this->lignesTotaux as [$ligne, $colonnes]) {
            $styles['A'.$ligne.':'.chr(64 + $colonnes).$ligne] = [
                'font' => ['bold' => true],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'E3F2F1']],
            ];
        }

        return $styles;
    }

    private function ajouterTableau(array &$lignes, array $entete, array $contenu): void
    {
        $lignes[] = [];
        $lignes[] = $entete;
        $this->lignesEntetes[] = [count($lignes), count($entete)];

        foreach ($contenu ?: [['Aucune donnée sur la période']] as $ligne) {
            $lignes[] = $ligne;
        }
    }
}
