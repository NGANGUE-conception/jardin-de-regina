<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_number',
        'nom',
        'prenom',
        'email',
        'tel',
        'poste',
        'message',
        'documents',
        'status',
        'internal_note',
    ];

    protected $casts = [
        'documents' => 'array',
    ];

    public const STATUTS = ['nouvelle', 'entretien_prevu', 'retenue', 'refusee'];

    public static function genererNumero(): string
    {
        $dernier = static::where('application_number', 'like', '#JR-CAN-%')
            ->orderByDesc('id')
            ->first();

        $prochain = 1;
        if ($dernier) {
            $prochain = (int) substr($dernier->application_number, -4) + 1;
        }

        return sprintf('#JR-CAN-%04d', $prochain);
    }

    public function nomComplet(): string
    {
        return trim($this->prenom.' '.$this->nom);
    }

    public function toAdminArray(): array
    {
        return [
            'id' => $this->application_number,
            'dbId' => $this->id,
            'nom' => $this->nomComplet(),
            'poste' => $this->poste,
            'diplome' => 'Non précisé (voir CV joint)',
            'experience' => 'Non précisé (voir CV joint)',
            'tel' => $this->tel,
            'email' => $this->email,
            'statut' => $this->status,
            'dateLabel' => ucfirst($this->created_at->diffForHumans()),
            'cvJoint' => ! empty($this->documents),
            'message' => $this->message,
        ];
    }
}
