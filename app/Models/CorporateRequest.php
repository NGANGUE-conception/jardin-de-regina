<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporateRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_number',
        'company_name',
        'sector',
        'employees_count',
        'berceaux_count',
        'contact_name',
        'contact_role',
        'contact_email',
        'contact_tel',
        'message',
        'status',
        'internal_note',
    ];

    public const STATUTS = ['nouvelle', 'en_discussion', 'signee', 'refusee'];

    public static function genererNumero(): string
    {
        $annee = now()->year;
        $dernier = static::where('request_number', 'like', '#JR-ENT-%')
            ->orderByDesc('id')
            ->first();

        $prochain = 1;
        if ($dernier) {
            $prochain = (int) substr($dernier->request_number, -4) + 1;
        }

        return sprintf('#JR-ENT-%04d', $prochain);
    }

    public function toAdminArray(): array
    {
        return [
            'id' => $this->request_number,
            'dbId' => $this->id,
            'nom' => $this->company_name,
            'secteur' => $this->sector ?: 'Non précisé',
            'effectif' => $this->employees_count ?: 'Non précisé',
            'contact' => $this->contact_name.($this->contact_role ? ' — '.$this->contact_role : ''),
            'tel' => $this->contact_tel,
            'email' => $this->contact_email,
            'berceaux' => $this->berceaux_count ?: 'Non précisé',
            'message' => $this->message ?: 'Aucun message complémentaire.',
            'statut' => $this->status,
            'dateLabel' => ucfirst($this->created_at->diffForHumans()),
            'motif' => $this->status === 'refusee' ? $this->internal_note : null,
        ];
    }
}
