<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PreRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'dossier_number',
        'child_last_name',
        'child_first_name',
        'child_gender',
        'child_birthdate',
        'entry_date',
        'care_type',
        'care_days',
        'special_needs',
        'special_needs_details',
        'p1_civilite',
        'p1_nom',
        'p1_prenom',
        'p1_email',
        'p1_tel',
        'p1_fixe',
        'p1_profession',
        'p1_employeur',
        'p1_cp',
        'p1_ville',
        'p1_adresse',
        'single_parent',
        'p2_civilite',
        'p2_nom',
        'p2_prenom',
        'p2_email',
        'p2_tel',
        'p2_profession',
        'p2_employeur',
        'message',
        'newsletter_opt_in',
        'rgpd_consent',
        'status',
        'internal_note',
    ];

    protected $casts = [
        'child_birthdate' => 'date',
        'entry_date' => 'date',
        'care_days' => 'array',
        'special_needs' => 'boolean',
        'single_parent' => 'boolean',
        'newsletter_opt_in' => 'boolean',
        'rgpd_consent' => 'boolean',
    ];

    public const STATUTS = ['nouvelle', 'attente', 'acceptee', 'refusee'];

    public function visites(): MorphMany
    {
        return $this->morphMany(Visite::class, 'visitable');
    }

    /**
     * Génère un numéro de dossier lisible du type #JR-2026-0033.
     */
    public static function genererNumeroDossier(): string
    {
        $annee = now()->year;
        $dernier = static::where('dossier_number', 'like', "#JR-{$annee}-%")
            ->orderByDesc('id')
            ->first();

        $prochain = 1;
        if ($dernier) {
            $prochain = (int) substr($dernier->dossier_number, -4) + 1;
        }

        return sprintf('#JR-%d-%04d', $annee, $prochain);
    }

    public function nomComplet(): string
    {
        return trim($this->child_first_name.' '.$this->child_last_name);
    }

    public function initiales(): string
    {
        return mb_strtoupper(mb_substr($this->child_first_name, 0, 1).mb_substr($this->child_last_name, 0, 1));
    }

    public function ageLibelle(): string
    {
        $mois = (int) $this->child_birthdate->diffInMonths(now());

        if ($mois < 24) {
            return $mois.' mois';
        }

        $annees = intdiv($mois, 12);

        return $annees.' an'.($annees > 1 ? 's' : '');
    }

    public function naissanceLibelle(): string
    {
        return 'Né(e) le '.$this->child_birthdate->translatedFormat('j F Y').' · '.$this->ageLibelle();
    }

    public function accueilLabel(): string
    {
        return match ($this->care_type) {
            'urgence' => "Accueil d'urgence",
            'occasionnel' => 'Occasionnel ('.count($this->care_days ?? []).'j)',
            default => count($this->care_days ?? []) >= 5
                ? 'Temps plein (5j)'
                : count($this->care_days ?? []).' jours / semaine',
        };
    }

    public function entreeLabel(): string
    {
        return $this->entry_date->translatedFormat('j M. Y');
    }

    public function adresseComplete(): string
    {
        return trim(collect([$this->p1_adresse, $this->p1_cp.' '.$this->p1_ville])->filter()->implode(', '));
    }

    public function specificitesLibelle(): string
    {
        if ($this->special_needs && $this->special_needs_details) {
            return $this->special_needs_details;
        }

        return $this->special_needs
            ? 'Situation particulière signalée, détails à préciser avec la famille.'
            : 'Aucune allergie ni besoin spécifique signalé au dépôt du dossier.';
    }

    public function toAdminArray(): array
    {
        return [
            'id' => $this->dossier_number,
            'dbId' => $this->id,
            'dateLabel' => ucfirst($this->created_at->diffForHumans()),
            'statut' => $this->status,
            'entreprise' => false,
            'accueilType' => $this->care_type,
            'accueilLabel' => $this->accueilLabel(),
            'entreeLabel' => $this->entreeLabel(),
            'adresse' => $this->adresseComplete() ?: 'Adresse non renseignée',
            'specificites' => $this->specificitesLibelle(),
            'note' => $this->internal_note ?: 'Aucune note interne pour le moment.',
            'enfant' => [
                'nom' => $this->nomComplet(),
                'initiales' => $this->initiales(),
                'age' => $this->ageLibelle(),
                'sexe' => $this->child_gender,
                'naissance' => $this->naissanceLibelle(),
            ],
            'parent' => [
                'nom' => trim($this->p1_prenom.' '.$this->p1_nom),
                'role' => ($this->p1_civilite === 'Monsieur' ? 'Père' : 'Mère').' · référent principal',
                'tel' => $this->p1_tel,
                'email' => $this->p1_email,
            ],
        ];
    }
}
