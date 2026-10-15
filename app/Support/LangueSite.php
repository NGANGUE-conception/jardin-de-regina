<?php

namespace App\Support;

/**
 * Langue dans laquelle le visiteur consulte le site (boutons FR / EN du menu).
 */
class LangueSite
{
    /** @var array<string, string> */
    private const LIBELLES_FRANCAIS = ['fr' => 'Français', 'en' => 'Anglais'];

    /**
     * Nom de la langue en français, pour les alertes envoyées à la direction
     * (ex. « Anglais » : répondre à cette famille en anglais).
     */
    public static function libelleFrancais(): string
    {
        return self::LIBELLES_FRANCAIS[app()->getLocale()] ?? app()->getLocale();
    }
}
