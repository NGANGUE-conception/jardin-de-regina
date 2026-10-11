<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCorporateRequestRequest;
use App\Mail\NewSubmissionAlert;
use App\Mail\SubmissionConfirmation;
use App\Models\CorporateRequest;
use App\Support\SuiviVisites;
use Illuminate\Support\Facades\Mail;

class CorporateRequestController extends Controller
{
    public function store(StoreCorporateRequestRequest $request, SuiviVisites $suivi)
    {
        $data = $request->validated();
        unset($data['rgpd_consent']);
        $data['request_number'] = CorporateRequest::genererNumero();
        $data['status'] = 'nouvelle';

        $demande = CorporateRequest::create($data);
        $suivi->rattacherFormulaire($request, $demande);

        Mail::to($demande->contact_email)->send(new SubmissionConfirmation(
            titre: 'Votre demande de berceau entreprise est bien reçue',
            numeroDossier: $demande->request_number,
            intro: "Merci d'avoir contacté Le Jardin de Régina au nom de {$demande->company_name}. Notre équipe étudie votre demande et revient vers vous très prochainement.",
            lignes: [
                'Entreprise' => $demande->company_name,
                'Berceaux souhaités' => $demande->berceaux_count ?: 'Non précisé',
            ],
        ));

        Mail::to(config('mail.admin_address'))->send(new NewSubmissionAlert(
            type: 'Nouvelle demande entreprise',
            numeroDossier: $demande->request_number,
            lignes: [
                'Entreprise' => $demande->company_name,
                'Contact' => $demande->contact_name,
                'Téléphone' => $demande->contact_tel,
                'Email' => $demande->contact_email,
            ],
            lienAdmin: route('admin.entreprises'),
        ));

        return redirect()
            ->to(route('berceaux-entreprise').'#devis-form')
            ->with('success', 'Merci, votre demande de devis a bien été envoyée. Notre équipe revient vers vous très prochainement.');
    }
}
