<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewSubmissionAlert extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  string  $type  Nature de la demande (ex. "Nouvelle pré-inscription")
     * @param  string  $numeroDossier  Référence du dossier
     * @param  array<string, string>  $lignes  Récapitulatif clé => valeur
     * @param  string  $lienAdmin  URL absolue vers la fiche dans l'espace admin
     */
    public function __construct(
        public string $type,
        public string $numeroDossier,
        public array $lignes = [],
        public string $lienAdmin = '',
    ) {}

    public function build(): self
    {
        return $this
            ->subject('[Le Jardin de Régina] '.$this->type.' — '.$this->numeroDossier)
            ->view('emails.new-submission-alert');
    }
}
