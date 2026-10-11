<?php

namespace App\Http\Controllers;

use App\Support\SuiviVisites;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

/**
 * Droit d'opposition à la mesure d'audience (page "Gestion des cookies").
 */
class PreferenceStatistiquesController extends Controller
{
    public function update(Request $request, SuiviVisites $suivi)
    {
        $request->validate([
            'choix' => ['required', 'in:refuser,accepter'],
        ]);

        if ($request->input('choix') === 'refuser') {
            $suivi->oublierVisiteEnCours($request);

            Cookie::queue(SuiviVisites::COOKIE_REFUS, '1', SuiviVisites::DUREE_COOKIE_MINUTES);
            Cookie::queue(Cookie::forget(SuiviVisites::COOKIE_VISITEUR));

            $message = 'C\'est noté : vos visites ne seront plus comptabilisées dans nos statistiques.';
        } else {
            Cookie::queue(Cookie::forget(SuiviVisites::COOKIE_REFUS));

            $message = 'Merci ! Vos visites seront de nouveau comptabilisées, de façon anonyme.';
        }

        return redirect()
            ->to(route('gestion-des-cookies').'#section-4')
            ->with('statistiques', $message);
    }
}
