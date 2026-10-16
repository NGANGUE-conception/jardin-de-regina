<?php

use App\Support\LangueSite;

if (! function_exists('route_langue')) {
    /**
     * Lien vers une page publique dans la langue affichée : route_langue('contact')
     * donne /contact sur le site français et /en/contact sur le site anglais.
     *
     * @param  array<string, mixed>|string|int  $parametres
     */
    function route_langue(string $nom, array|string|int $parametres = [], bool $absolue = true): string
    {
        return LangueSite::route($nom, $parametres, $absolue);
    }
}
