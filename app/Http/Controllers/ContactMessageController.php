<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Mail\NewSubmissionAlert;
use App\Mail\SubmissionConfirmation;
use App\Models\ContactMessage;
use App\Support\EnvoiEmail;
use App\Support\LangueSite;
use App\Support\SuiviVisites;

class ContactMessageController extends Controller
{
    public function store(StoreContactMessageRequest $request, SuiviVisites $suivi)
    {
        $data = $request->validated();
        unset($data['rgpd_consent']);
        $data['message_number'] = ContactMessage::genererNumero();
        $data['status'] = 'non_lu';

        $message = ContactMessage::create($data);
        $suivi->rattacherFormulaire($request, $message);

        EnvoiEmail::envoyer($message->email, new SubmissionConfirmation(
            titre: __('Votre message est bien reçu'),
            numeroDossier: $message->message_number,
            intro: __('Merci pour votre message. Notre équipe vous répondra sous 48h ouvrées.'),
            lignes: [
                __('Objet') => __($message->subject),
            ],
        ));

        EnvoiEmail::envoyer(config('mail.admin_address'), new NewSubmissionAlert(
            type: 'Nouveau message de contact',
            numeroDossier: $message->message_number,
            lignes: [
                'Nom' => $message->fullname,
                'Objet' => $message->subject,
                'Téléphone' => $message->phone,
                'Email' => $message->email,
                'Langue du formulaire' => LangueSite::libelleFrancais(),
            ],
            lienAdmin: route('admin.messages'),
        ), 'fr');

        return redirect()
            ->to(route_langue('contact').'#contact-form')
            ->with('success', __('Merci, votre message a bien été transmis. Nous vous répondrons sous 48h ouvrées.'));
    }
}
