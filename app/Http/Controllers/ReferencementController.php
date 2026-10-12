<?php

namespace App\Http\Controllers;

use App\Support\PagesPubliques;
use App\Support\Referencement;
use Illuminate\Http\Response;

/**
 * Fichiers lus par les moteurs de recherche : sitemap.xml (liste des pages) et robots.txt (consignes d'exploration).
 * Générés à la volée pour toujours refléter le domaine du site (APP_URL) et la liste à jour des pages.
 */
class ReferencementController extends Controller
{
    public function sitemap(): Response
    {
        return response()
            ->view('referencement.sitemap', ['pages' => PagesPubliques::pourSitemap()])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        // En dehors du site en ligne (ordinateur local, version de test), on interdit tout aux robots :
        // une copie de travail ne doit jamais apparaître dans Google.
        if (! app()->environment('production')) {
            $contenu = "# Version de travail : exploration interdite.\nUser-agent: *\nDisallow: /\n";
        } else {
            $contenu = implode("\n", [
                'User-agent: *',
                'Disallow: /admin',
                '',
                'Sitemap: '.Referencement::url('sitemap.xml'),
                '',
            ]);
        }

        return response($contenu, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
