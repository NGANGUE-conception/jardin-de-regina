<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Langue du site public : français aux adresses d'origine (/contact), anglais sous /en (/en/contact).
 * Les pages publiques existent dans les deux langues (routes/publiques.php, noms « contact » et « en.contact »).
 */
class LangueSite
{
    /** @var array<int, string> */
    public const LANGUES = ['fr', 'en'];

    public const PAR_DEFAUT = 'fr';

    /** Préfixe des adresses (et des noms de routes) de la version anglaise. */
    public const PREFIXE_ANGLAIS = 'en';

    /** @var array<string, string> */
    private const LIBELLES_FRANCAIS = ['fr' => 'Français', 'en' => 'Anglais'];

    /** Langue d'une requête, d'après son adresse : /en ou /en/... = anglais, tout le reste = français (admin compris). */
    public static function deLaRequete(Request $request): string
    {
        return $request->is(self::PREFIXE_ANGLAIS, self::PREFIXE_ANGLAIS.'/*') ? 'en' : self::PAR_DEFAUT;
    }

    /**
     * Adresse d'une page publique dans la langue affichée (ou celle demandée).
     * Ex. route('contact') → /contact en français, /en/contact en anglais.
     *
     * @param  array<string, mixed>|string|int  $parametres
     */
    public static function route(string $nom, array|string|int $parametres = [], bool $absolue = true, ?string $langue = null): string
    {
        $langue ??= app()->getLocale();
        $nomTraduit = self::PREFIXE_ANGLAIS.'.'.$nom;

        return route($langue === 'en' && Route::has($nomTraduit) ? $nomTraduit : $nom, $parametres, $absolue);
    }

    /**
     * La page affichée dans l'autre langue (boutons FR / EN, balises hreflang).
     * Pour une page sans équivalent (page d'erreur, admin...), renvoie l'accueil dans cette langue.
     */
    public static function equivalent(string $langue, ?Request $request = null): string
    {
        $request ??= request();
        $nom = self::nomSansLangue($request->route()?->getName());

        if ($nom === null || ! Route::has($nom) || $request->route()?->methods() === ['POST']) {
            return self::route('accueil', langue: $langue);
        }

        $adresse = self::route($nom, $request->route()->parameters(), langue: $langue);
        $requete = $request->getQueryString();

        return $requete ? $adresse.'?'.$requete : $adresse;
    }

    /** Nom de route sans le préfixe de langue : « en.contact » → « contact ». */
    public static function nomSansLangue(?string $nom): ?string
    {
        if ($nom === null) {
            return null;
        }

        return str_starts_with($nom, self::PREFIXE_ANGLAIS.'.') ? substr($nom, strlen(self::PREFIXE_ANGLAIS) + 1) : $nom;
    }

    /** La page affichée est-elle une page publique qui existe dans les deux langues ? */
    public static function pageBilingue(?Request $request = null): bool
    {
        $request ??= request();
        $nom = self::nomSansLangue($request->route()?->getName());

        return $nom !== null && $request->isMethod('GET') && Route::has($nom) && Route::has(self::PREFIXE_ANGLAIS.'.'.$nom);
    }

    /**
     * Nom de la langue en français, pour les alertes envoyées à la direction
     * (ex. « Anglais » : répondre à cette famille en anglais).
     */
    public static function libelleFrancais(): string
    {
        return self::LIBELLES_FRANCAIS[app()->getLocale()] ?? app()->getLocale();
    }
}
