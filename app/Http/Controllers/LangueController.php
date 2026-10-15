<?php

namespace App\Http\Controllers;

use App\Http\Middleware\DefinirLangue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Boutons FR / EN du menu : mémorise la langue choisie puis revient sur la page affichée.
 */
class LangueController extends Controller
{
    /** Durée de mémorisation du choix : 1 an. */
    private const DUREE_MINUTES = 60 * 24 * 365;

    public function changer(Request $request, string $langue): RedirectResponse
    {
        return redirect()->to($this->pagePrecedente($request))
            ->withCookie(cookie(DefinirLangue::COOKIE, $langue, self::DUREE_MINUTES, httpOnly: true));
    }

    /**
     * Retour sur la page d'où vient le clic, uniquement si elle fait partie du site
     * (jamais vers un site extérieur, même si le lien a été posé ailleurs).
     */
    private function pagePrecedente(Request $request): string
    {
        $precedente = (string) $request->headers->get('referer');
        $hote = parse_url($precedente, PHP_URL_HOST);

        if ($precedente === '' || $hote !== $request->getHost() || str_contains($precedente, '/langue/')) {
            return route('accueil');
        }

        return $precedente;
    }
}
