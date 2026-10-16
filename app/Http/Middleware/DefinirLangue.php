<?php

namespace App\Http\Middleware;

use App\Support\LangueSite;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applique la langue de la page demandée : anglais pour les adresses en /en/..., français sinon.
 *
 * Middleware global (et non du groupe « web ») pour que les pages d'erreur, comme une 404 sous /en,
 * s'affichent aussi dans la bonne langue.
 */
class DefinirLangue
{
    public function handle(Request $request, Closure $next): Response
    {
        App::setLocale(LangueSite::deLaRequete($request));

        return $next($request);
    }
}
