<?php

namespace App\Http\Middleware;

use App\Support\SuiviVisites;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Compte les pages publiques vues par les internautes (onglet "Visites du site" de l'admin).
 * Le comptage se fait après l'affichage de la page et ne peut jamais la faire planter.
 */
class SuivreVisitesSite
{
    public function __construct(private SuiviVisites $suivi) {}

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            if ($this->suivi->doitSuivre($request, $response)) {
                $this->suivi->enregistrerPage($request, $response);
            }
        } catch (Throwable $e) {
            report($e);
        }

        return $response;
    }
}
