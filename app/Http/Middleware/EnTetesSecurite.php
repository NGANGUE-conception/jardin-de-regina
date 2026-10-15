<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

/**
 * En-têtes de sécurité ajoutés à toutes les réponses du site (pages, admin, fichiers générés par Laravel).
 *
 *  - le site ne peut pas être affiché dans une page d'un autre site (protection contre le « clickjacking ») ;
 *  - le navigateur ne devine pas le type d'un fichier (un CV ne peut pas être exécuté comme une page) ;
 *  - l'adresse complète des pages n'est pas transmise aux sites externes (seulement le nom de domaine) ;
 *  - caméra, micro, géolocalisation et paiement sont désactivés (le site n'en a pas besoin) ;
 *  - Content-Security-Policy : scripts, styles, images et polices viennent uniquement du site lui-même ;
 *  - HTTPS imposé pendant un an une fois le site en ligne (uniquement en production et en HTTPS).
 */
class EnTetesSecurite
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=(), usb=(), interest-cohort=()');

        // Avec « npm run dev », les fichiers viennent du serveur Vite (autre port) : pas de CSP pour ne pas le bloquer.
        if (! Vite::isRunningHot()) {
            $response->headers->set('Content-Security-Policy', $this->politiqueDeContenu());
        }

        if ($request->isSecure() && app()->isProduction()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }

    /**
     * 'unsafe-eval' est nécessaire à Alpine.js (il interprète les attributs x-data, @click...),
     * 'unsafe-inline' aux petites fonctions <script> des pages et aux styles intégrés.
     */
    private function politiqueDeContenu(): string
    {
        return implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval'",
            "style-src 'self' 'unsafe-inline'",
            "img-src 'self' data:",
            "font-src 'self' data:",
            "connect-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
        ]);
    }
}
