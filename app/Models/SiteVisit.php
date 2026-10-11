<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Une visite du site public par un internaute (voir App\Support\SuiviVisites).
 */
class SiteVisit extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'visitor_id',
        'is_returning',
        'started_at',
        'last_activity_at',
        'landing_path',
        'source',
        'referrer_host',
        'campaign',
        'device',
        'browser',
        'os',
        'pages_count',
    ];

    protected $casts = [
        'is_returning' => 'boolean',
        'started_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'pages_count' => 'integer',
    ];

    public const SOURCES = [
        'google' => 'Google',
        'bing' => 'Bing',
        'qwant' => 'Qwant',
        'ecosia' => 'Ecosia',
        'duckduckgo' => 'DuckDuckGo',
        'yahoo' => 'Yahoo',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'linkedin' => 'LinkedIn',
        'tiktok' => 'TikTok',
        'youtube' => 'YouTube',
        'monenfant' => 'monenfant.fr (CAF)',
        'pagesjaunes' => 'PagesJaunes',
        'direct' => 'Accès direct',
        'autre' => 'Autre site',
    ];

    public const APPAREILS = [
        'mobile' => 'Mobile',
        'tablette' => 'Tablette',
        'ordinateur' => 'Ordinateur',
    ];

    public function pageViews(): HasMany
    {
        return $this->hasMany(SitePageView::class)->orderBy('viewed_at')->orderBy('id');
    }

    public function formulaires(): HasMany
    {
        return $this->hasMany(SiteVisitForm::class)->orderBy('submitted_at');
    }

    public function sourceLabel(): string
    {
        return self::SOURCES[$this->source] ?? ucfirst($this->source);
    }

    /**
     * Provenance détaillée : "Google", "Autre site (doctissimo.fr)", "Facebook · campagne flyer-rentree"...
     */
    public function provenance(): string
    {
        $libelle = $this->sourceLabel();

        if ($this->source === 'autre' && $this->referrer_host) {
            $libelle .= ' ('.$this->referrer_host.')';
        }

        if ($this->campaign) {
            $libelle .= ' · campagne '.$this->campaign;
        }

        return $libelle;
    }

    public function appareilLabel(): string
    {
        return self::APPAREILS[$this->device] ?? ucfirst($this->device);
    }

    public function dureeSecondes(): int
    {
        return (int) $this->started_at->diffInSeconds($this->last_activity_at, true);
    }

    /**
     * Durée lisible entre la première et la dernière page vue.
     * Une visite d'une seule page a une durée inconnue (on ne sait pas quand la personne est partie).
     */
    public function dureeLabel(): string
    {
        if ($this->pages_count <= 1) {
            return '1 seule page';
        }

        $secondes = $this->dureeSecondes();

        if ($secondes < 60) {
            return "moins d'1 min";
        }

        $minutes = intdiv($secondes, 60);

        return $minutes < 60
            ? $minutes.' min'
            : intdiv($minutes, 60).' h '.str_pad((string) ($minutes % 60), 2, '0', STR_PAD_LEFT);
    }

    /**
     * Données pour la vue admin (nécessite pageViews et formulaires.formulaire chargés).
     */
    public function toAdminArray(): array
    {
        return [
            'id' => $this->id,
            'date' => ucfirst($this->started_at->translatedFormat('l j F Y')),
            'dateCourte' => $this->started_at->format('d/m'),
            'heure' => $this->started_at->format('H\hi'),
            'source' => $this->source,
            'provenance' => $this->provenance(),
            'appareil' => $this->appareilLabel(),
            'device' => $this->device,
            'navigateur' => trim(($this->browser ?? '').' · '.($this->os ?? ''), ' ·'),
            'nouveau' => ! $this->is_returning,
            'pages' => $this->pages_count,
            'duree' => $this->dureeLabel(),
            'parcours' => $this->pageViews->map(fn (SitePageView $vue) => [
                'heure' => $vue->viewed_at->format('H\hi'),
                'page' => SitePageView::libelle($vue->path),
            ])->values(),
            'formulaires' => $this->formulaires->map->resume()->values(),
        ];
    }
}
