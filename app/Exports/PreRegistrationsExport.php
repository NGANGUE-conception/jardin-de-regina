<?php

namespace App\Exports;

use App\Models\PreRegistration;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PreRegistrationsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection(): Collection
    {
        return PreRegistration::latest()->get();
    }

    public function headings(): array
    {
        return [
            'Dossier',
            'Enfant',
            'Sexe',
            'Date de naissance',
            "Date d'entrée souhaitée",
            "Type d'accueil",
            'Jours souhaités',
            'Parent référent',
            'Téléphone',
            'Email',
            'Adresse',
            'Statut',
            'Déposé le',
        ];
    }

    public function map($dossier): array
    {
        return [
            $dossier->dossier_number,
            $dossier->nomComplet(),
            $dossier->child_gender,
            $dossier->child_birthdate->format('d/m/Y'),
            $dossier->entry_date->format('d/m/Y'),
            $dossier->accueilLabel(),
            implode(', ', $dossier->care_days ?? []),
            trim($dossier->p1_prenom.' '.$dossier->p1_nom),
            $dossier->p1_tel,
            $dossier->p1_email,
            $dossier->adresseComplete(),
            match ($dossier->status) {
                'nouvelle' => 'Nouvelle',
                'attente' => 'En attente',
                'acceptee' => 'Acceptée',
                'refusee' => 'Refusée',
                default => $dossier->status,
            },
            $dossier->created_at->format('d/m/Y H:i'),
        ];
    }
}
