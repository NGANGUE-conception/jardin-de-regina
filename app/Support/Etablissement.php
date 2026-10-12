<?php

namespace App\Support;

use App\Models\Parametre;
use Illuminate\Support\Facades\Cache;
use Throwable;

/**
 * Coordonnées de l'établissement affichées sur tout le site (pied de page, contact, mentions légales,
 * emails, pages d'erreur, données pour Google...). Modifiables par la direction dans
 * Espace admin → Paramètres → Établissement.
 *
 * Valeurs enregistrées en base (table "parametres"), à défaut celles de config/creche.php.
 * Si la base de données est inaccessible, les valeurs par défaut s'appliquent : le site reste affichable.
 * Disponible dans toutes les vues sous le nom $etab (ex. {{ $etab->telephone() }}).
 */
class Etablissement
{
    private const CACHE = 'etablissement';

    /** Réglages modifiables depuis l'admin. */
    public const CHAMPS = ['telephone', 'email', 'rue', 'code_postal', 'ville', 'ouverture', 'fermeture', 'gestionnaire', 'representant', 'latitude', 'longitude'];

    private ?array $valeurs = null;

    public function valeurs(): array
    {
        return $this->valeurs ??= array_merge($this->parDefaut(), $this->enregistrees());
    }

    public function valeur(string $champ): string
    {
        return (string) ($this->valeurs()[$champ] ?? '');
    }

    /**
     * Enregistre les réglages modifiés et vide le cache, pour un affichage immédiat sur tout le site.
     */
    public function enregistrer(array $valeurs): void
    {
        foreach (array_intersect_key($valeurs, array_flip(self::CHAMPS)) as $cle => $valeur) {
            Parametre::updateOrCreate(['cle' => $cle], ['valeur' => $valeur]);
        }

        Cache::forget(self::CACHE);
        $this->valeurs = null;
    }

    /* ------------------------------------------------------------ Affichage */

    public function nom(): string
    {
        return (string) config('creche.nom');
    }

    /** Ex. "07 67 64 61 84" */
    public function telephone(): string
    {
        return $this->valeur('telephone');
    }

    /** Format international pour les liens "tel:" et pour Google, ex. "+33767646184". */
    public function telephoneLien(): string
    {
        $chiffres = preg_replace('/\D/', '', $this->telephone());

        return str_starts_with($chiffres, '0') ? '+33'.substr($chiffres, 1) : '+'.$chiffres;
    }

    public function email(): string
    {
        return $this->valeur('email');
    }

    public function rue(): string
    {
        return $this->valeur('rue');
    }

    public function codePostal(): string
    {
        return $this->valeur('code_postal');
    }

    public function ville(): string
    {
        return $this->valeur('ville');
    }

    /** Ex. "4 bis rue des Écoles, 94140 Alfortville" */
    public function adresse(): string
    {
        return $this->rue().', '.$this->codePostal().' '.$this->ville();
    }

    /** Ex. "4 bis rue des Écoles, Alfortville" */
    public function adresseCourte(): string
    {
        return $this->rue().', '.$this->ville();
    }

    /** Heures au format machine, ex. "08:30" (pour Google). */
    public function ouvertureMachine(): string
    {
        return $this->valeur('ouverture');
    }

    public function fermetureMachine(): string
    {
        return $this->valeur('fermeture');
    }

    /** Ex. "8h30" */
    public function ouverture(): string
    {
        return self::heureLisible($this->ouvertureMachine());
    }

    /** Ex. "18h30" */
    public function fermeture(): string
    {
        return self::heureLisible($this->fermetureMachine());
    }

    /** Ex. "8h30 – 18h30" */
    public function horaires(): string
    {
        return $this->ouverture().' – '.$this->fermeture();
    }

    /** Ex. "de 8h30 à 18h30" */
    public function horairesTexte(): string
    {
        return 'de '.$this->ouverture().' à '.$this->fermeture();
    }

    public function gestionnaire(): string
    {
        return $this->valeur('gestionnaire');
    }

    public function representant(): string
    {
        return $this->valeur('representant');
    }

    public function latitude(): float
    {
        return (float) $this->valeur('latitude');
    }

    public function longitude(): float
    {
        return (float) $this->valeur('longitude');
    }

    public function places(): int
    {
        return (int) config('creche.places');
    }

    /** "08:30" → "8h30", "18:00" → "18h" */
    public static function heureLisible(string $heure): string
    {
        [$h, $m] = array_pad(explode(':', $heure), 2, '00');

        return ((int) $h).'h'.($m === '00' ? '' : $m);
    }

    /* ------------------------------------------------------------ Interne */

    private function parDefaut(): array
    {
        $c = config('creche');

        return [
            'telephone' => $c['telephone_affiche'],
            'email' => $c['email'],
            'rue' => $c['adresse']['rue'],
            'code_postal' => $c['adresse']['code_postal'],
            'ville' => $c['adresse']['ville'],
            'ouverture' => $c['horaires']['ouverture'],
            'fermeture' => $c['horaires']['fermeture'],
            'gestionnaire' => $c['gestionnaire'],
            'representant' => $c['representant'],
            'latitude' => $c['geo']['latitude'],
            'longitude' => $c['geo']['longitude'],
        ];
    }

    private function enregistrees(): array
    {
        try {
            return array_filter(
                Cache::rememberForever(self::CACHE, fn () => Parametre::pluck('valeur', 'cle')->all()),
                fn ($valeur) => $valeur !== null && $valeur !== '',
            );
        } catch (Throwable) {
            return []; // base de données indisponible (ou table pas encore créée) : valeurs par défaut
        }
    }
}
