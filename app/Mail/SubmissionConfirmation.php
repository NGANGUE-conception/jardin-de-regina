<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubmissionConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  string  $titre  Titre affiché en haut de l'email (ex. "Votre pré-inscription est bien reçue")
     * @param  string  $numeroDossier  Référence du dossier (ex. #JR-2026-0033)
     * @param  string  $intro  Court paragraphe d'introduction
     * @param  array<string, string>  $lignes  Récapitulatif clé => valeur affiché dans l'email
     */
    public function __construct(
        public string $titre,
        public string $numeroDossier,
        public string $intro,
        public array $lignes = [],
    ) {}

    public function build(): self
    {
        return $this
            ->subject($this->titre.' — '.$this->numeroDossier)
            ->view('emails.submission-confirmation');
    }
}
