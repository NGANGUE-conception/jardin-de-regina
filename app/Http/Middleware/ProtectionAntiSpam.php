<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Protection anti-spam des formulaires publics (pré-inscription, contact, devis, candidature),
 * sans CAPTCHA. Les champs sont ajoutés aux formulaires par le composant <x-anti-spam />.
 *
 *  1. Champ piège invisible : un humain ne le voit pas, un robot le remplit.
 *     → la demande est ignorée en silence (le robot croit avoir réussi).
 *  2. Délai minimum entre l'affichage du formulaire et son envoi (heure chiffrée).
 *     → un envoi trop rapide n'est pas perdu : la personne est invitée à renvoyer.
 *
 * La limite du nombre d'envois par heure est gérée à part (RateLimiter "formulaires-publics").
 */
class ProtectionAntiSpam
{
    public const CHAMP_PIEGE = 'jr_site_web';

    public const CHAMP_HEURE = 'jr_affiche_a';

    private const DELAI_MINIMUM_SECONDES = 2;

    /**
     * Où renvoyer un robot pris au piège : la même page qu'un envoi réussi, pour qu'il ne voie aucune différence.
     */
    private const FAUX_SUCCES = [
        'pre-inscription.store' => ['route' => 'confirmation-preinscription', 'ancre' => '', 'message' => null],
        'contact.store' => ['route' => 'contact', 'ancre' => '#contact-form', 'message' => 'Merci, votre message a bien été transmis. Nous vous répondrons sous 48h ouvrées.'],
        'berceaux-entreprise.store' => ['route' => 'berceaux-entreprise', 'ancre' => '#devis-form', 'message' => 'Merci, votre demande de devis a bien été envoyée. Notre équipe revient vers vous très prochainement.'],
        'recrutement.store' => ['route' => 'recrutement', 'ancre' => '#candidature', 'message' => "Merci pour votre candidature ! Notre direction pédagogique l'examinera avec soin sous 5 jours ouvrés."],
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $formulaire = (string) $request->route()?->getName();

        // 1. Champ piège rempli : c'est un robot.
        if (filled($request->input(self::CHAMP_PIEGE))) {
            Log::info('Anti-spam : envoi ignoré (champ piège rempli).', ['formulaire' => $formulaire]);

            return $this->fauxSucces($formulaire);
        }

        // 2. Heure d'affichage absente, illisible ou envoi trop rapide.
        $afficheA = $this->heureAffichage($request);

        if ($afficheA === null || now()->getTimestamp() - $afficheA < self::DELAI_MINIMUM_SECONDES) {
            Log::info('Anti-spam : envoi refusé (délai ou jeton invalide).', ['formulaire' => $formulaire]);

            return back()
                ->withInput($request->except(['_token', self::CHAMP_PIEGE, self::CHAMP_HEURE, 'documents']))
                ->withErrors(['anti_spam' => __('Merci de vérifier vos informations puis de renvoyer le formulaire.')]);
        }

        return $next($request);
    }

    private function heureAffichage(Request $request): ?int
    {
        try {
            $valeur = decrypt((string) $request->input(self::CHAMP_HEURE));
        } catch (DecryptException) {
            return null;
        }

        return is_int($valeur) ? $valeur : null;
    }

    private function fauxSucces(string $formulaire): Response
    {
        $cible = self::FAUX_SUCCES[$formulaire] ?? null;

        if (! $cible) {
            return redirect('/');
        }

        $reponse = redirect()->to(route($cible['route']).$cible['ancre']);

        return $cible['message'] ? $reponse->with('success', __($cible['message'])) : $reponse;
    }
}
