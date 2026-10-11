<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SitePageView extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'site_visit_id',
        'path',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    /**
     * Nom lisible des pages publiques, pour l'admin et l'export Excel.
     */
    public const PAGES = [
        '/' => 'Accueil',
        '/notre-projet' => 'Notre projet',
        '/fonctionnement' => 'Fonctionnement',
        '/tarifs' => 'Tarifs et aides',
        '/berceaux-entreprise' => 'Berceaux entreprise',
        '/contact' => 'Contact',
        '/recrutement' => 'Recrutement',
        '/pre-inscription' => 'Pré-inscription',
        '/pre-inscription/confirmation' => 'Confirmation de pré-inscription',
        '/mentions-legales' => 'Mentions légales',
        '/politique-de-confidentialite' => 'Politique de confidentialité',
        '/gestion-des-cookies' => 'Gestion des cookies',
        '/plan-du-site' => 'Plan du site',
    ];

    public function siteVisit(): BelongsTo
    {
        return $this->belongsTo(SiteVisit::class);
    }

    public static function libelle(string $path): string
    {
        return self::PAGES[$path] ?? $path;
    }
}
