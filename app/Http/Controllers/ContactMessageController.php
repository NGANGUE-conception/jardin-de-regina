<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Mail\NewSubmissionAlert;
use App\Mail\SubmissionConfirmation;
use App\Models\ContactMessage;
use App\Support\SuiviVisites;
use Illuminate\Support\Facades\Mail;

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

        Mail::to($message->email)->send(new SubmissionConfirmation(
            titre: 'Votre message est bien reçu',
            numeroDossier: $message->message_number,
            intro: 'Merci pour votre message. Notre équipe vous répondra sous 48h ouvrées.',
            lignes: [
                'Objet' => $message->subject,
            ],
        ));

        Mail::to(config('mail.admin_address'))->send(new NewSubmissionAlert(
            type: 'Nouveau message de contact',
            numeroDossier: $message->message_number,
            lignes: [
                'Nom' => $message->fullname,
                'Objet' => $message->subject,
                'Téléphone' => $message->phone,
                'Email' => $message->email,
            ],
            lienAdmin: route('admin.messages'),
        ));

        return redirect()
            ->to(route('contact').'#contact-form')
            ->with('success', 'Merci, votre message a bien été transmis. Nous vous répondrons sous 48h ouvrées.');
    }
}
