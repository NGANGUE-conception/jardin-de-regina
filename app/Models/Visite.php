<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Visite extends Model
{
    protected $table = 'visits';

    protected $attributes = [
        'status' => 'a_confirmer',
    ];

    protected $fillable = [
        'visit_date',
        'visit_time',
        'visitor_name',
        'phone',
        'email',
        'type',
        'visitable_type',
        'visitable_id',
        'status',
        'internal_note',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    public const STATUTS = ['a_confirmer', 'confirmee', 'realisee', 'annulee', 'absent'];

    public const TYPES = [
        'preinscription' => 'Visite de pré-inscription',
        'suivi' => 'Entretien de suivi',
        'entreprise' => 'Visite - berceau entreprise',
        'candidature' => 'Entretien candidature',
        'autre' => 'Autre rendez-vous',
    ];

    /*
      Le dossier lié à la visite (pré-inscription, demande entreprise ou candidature), ou null.
     */
    public function visitable(): MorphTo
    {
        return $this->morphTo();
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    /**
     * "09:30:00" -> "09h30"
     */
    public function heureLabel(): string
    {
        return substr($this->visit_time, 0, 2).'h'.substr($this->visit_time, 3, 2);
    }

    public function dossierNumero(): ?string
    {
        return match (true) {
            $this->visitable instanceof PreRegistration => $this->visitable->dossier_number,
            $this->visitable instanceof CorporateRequest => $this->visitable->request_number,
            $this->visitable instanceof JobApplication => $this->visitable->application_number,
            default => null,
        };
    }

    public function toAdminArray(): array
    {
        return [
            'dbId' => $this->id,
            'date' => $this->visit_date->toDateString(),
            'heure' => $this->heureLabel(),
            'heureBrute' => substr($this->visit_time, 0, 5),
            'nom' => $this->visitor_name,
            'tel' => $this->phone,
            'email' => $this->email,
            'type' => $this->type,
            'typeLabel' => $this->typeLabel(),
            'dossier' => $this->dossierNumero(),
            'dossierValeur' => $this->visitable_type ? $this->visitable_type.':'.$this->visitable_id : null,
            'statut' => $this->status,
            'note' => $this->internal_note,
        ];
    }
}
