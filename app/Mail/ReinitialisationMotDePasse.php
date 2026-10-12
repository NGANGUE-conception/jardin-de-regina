<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Email envoyé à l'administrateur qui a demandé à réinitialiser son mot de passe.
 */
class ReinitialisationMotDePasse extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  string  $lien  Adresse de la page « Choisir un nouveau mot de passe » (contient le jeton secret)
     * @param  string  $nom  Nom du compte administrateur
     * @param  int  $dureeMinutes  Durée de validité du lien
     */
    public function __construct(
        public string $lien,
        public string $nom,
        public int $dureeMinutes,
    ) {}

    public function build(): self
    {
        return $this
            ->subject('Réinitialisation de votre mot de passe — Espace admin')
            ->view('emails.reinitialisation-mot-de-passe');
    }
}
