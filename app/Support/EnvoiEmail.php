<?php

namespace App\Support;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Envoi d'un email qui ne bloque jamais la page en cours.
 *
 * Les formulaires enregistrent d'abord la demande, puis envoient les emails. Si le service d'envoi
 * est en panne (Brevo indisponible, mauvais réglage...), la demande reste enregistrée et la personne
 * voit quand même la confirmation : l'incident est seulement noté dans storage/logs/laravel.log.
 * Sans cela, la famille verrait une page d'erreur et renverrait le formulaire (doublons).
 */
class EnvoiEmail
{
    /**
     * @param  string|null  $langue  langue de l'email (ex. 'fr' pour les alertes de la direction) ; par défaut celle du site affiché
     * @return bool true si l'email est parti, false si l'envoi a échoué
     */
    public static function envoyer(?string $destinataire, Mailable $email, ?string $langue = null): bool
    {
        if (blank($destinataire)) {
            return false;
        }

        try {
            Mail::to($destinataire)->locale($langue ?? app()->getLocale())->send($email);

            return true;
        } catch (Throwable $erreur) {
            Log::error('Échec de l\'envoi d\'un email', [
                'email' => $email::class,
                'destinataire' => $destinataire,
                'exception' => $erreur,
            ]);

            return false;
        }
    }
}
