<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applique la langue choisie par le visiteur avec les boutons FR / EN du menu.
 *
 * Le choix est mémorisé dans le cookie « jr_langue » (préférence demandée par le visiteur,
 * exemptée de consentement). Sans choix, le site est en français.
 * L'espace admin reste toujours en français.
 *
 * Middleware global (et non du groupe « web ») pour que les pages d'erreur, comme la 404,
 * s'affichent aussi dans la langue choisie : le cookie est donc exclu du chiffrement (bootstrap/app.php).
 */
class DefinirLangue
{
    public const COOKIE = 'jr_langue';

    public const LANGUES = ['fr', 'en'];

    public const PAR_DEFAUT = 'fr';

    public function handle(Request $request, Closure $next): Response
    {
        App::setLocale(self::langueDemandee($request));

        return $next($request);
    }

    public static function langueDemandee(Request $request): string
    {
        if ($request->is('admin', 'admin/*')) {
            return self::PAR_DEFAUT;
        }

        $langue = $request->cookie(self::COOKIE);

        return in_array($langue, self::LANGUES, true) ? $langue : self::PAR_DEFAUT;
    }
}
