<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Formulaire envoyé pendant une visite du site (pré-inscription, devis entreprise,
 * candidature ou message de contact).
 */
class SiteVisitForm extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'site_visit_id',
        'formulaire_type',
        'formulaire_id',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function siteVisit(): BelongsTo
    {
        return $this->belongsTo(SiteVisit::class);
    }

    public function formulaire(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Résumé homogène du formulaire, quel que soit son type :
     * type, numéro, identité, coordonnées, détails utiles et lien vers la page admin.
     */
    public function resume(): array
    {
        $f = $this->formulaire;

        $resume = match (true) {
            $f instanceof PreRegistration => [
                'type' => 'Pré-inscription',
                'numero' => $f->dossier_number,
                'nom' => trim($f->p1_prenom.' '.$f->p1_nom),
                'email' => $f->p1_email,
                'tel' => $f->p1_tel,
                'details' => array_filter([
                    'Enfant' => $f->nomComplet().' ('.$f->ageLibelle().')',
                    'Entrée souhaitée' => $f->entry_date?->translatedFormat('j F Y'),
                    "Type d'accueil" => $f->accueilLabel(),
                    'Ville' => trim($f->p1_cp.' '.$f->p1_ville),
                ]),
                'lien' => route('admin.pre-inscriptions'),
            ],
            $f instanceof CorporateRequest => [
                'type' => 'Demande entreprise',
                'numero' => $f->request_number,
                'nom' => $f->contact_name,
                'email' => $f->contact_email,
                'tel' => $f->contact_tel,
                'details' => array_filter([
                    'Entreprise' => $f->company_name,
                    'Fonction' => $f->contact_role,
                    'Berceaux souhaités' => $f->berceaux_count,
                ]),
                'lien' => route('admin.entreprises'),
            ],
            $f instanceof JobApplication => [
                'type' => 'Candidature',
                'numero' => $f->application_number,
                'nom' => $f->nomComplet(),
                'email' => $f->email,
                'tel' => $f->tel,
                'details' => array_filter([
                    'Poste souhaité' => $f->poste,
                ]),
                'lien' => route('admin.candidatures'),
            ],
            $f instanceof ContactMessage => [
                'type' => 'Message de contact',
                'numero' => $f->message_number,
                'nom' => $f->fullname,
                'email' => $f->email,
                'tel' => $f->phone,
                'details' => array_filter([
                    'Objet' => $f->subject,
                ]),
                'lien' => route('admin.messages'),
            ],
            default => [
                'type' => 'Formulaire supprimé',
                'numero' => null,
                'nom' => null,
                'email' => null,
                'tel' => null,
                'details' => [],
                'lien' => null,
            ],
        };

        return $resume + [
            'envoyeA' => $this->submitted_at->format('H\hi'),
        ];
    }

    /**
     * Détails sur une seule ligne, pour l'export Excel.
     */
    public static function detailsEnLigne(array $resume): string
    {
        return collect($resume['details'])
            ->map(fn ($valeur, $cle) => $cle.' : '.$valeur)
            ->implode(' · ');
    }
}
