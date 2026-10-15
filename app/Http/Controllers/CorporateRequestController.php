<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCorporateRequestRequest;
use App\Mail\NewSubmissionAlert;
use App\Mail\SubmissionConfirmation;
use App\Models\CorporateRequest;
use App\Support\EnvoiEmail;
use App\Support\LangueSite;
use App\Support\SuiviVisites;

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

        EnvoiEmail::envoyer($demande->contact_email, new SubmissionConfirmation(
            titre: __('Votre demande de berceau entreprise est bien reçue'),
            numeroDossier: $demande->request_number,
            intro: __('Merci d\'avoir contacté Le Jardin de Régina au nom de :entreprise. Notre équipe étudie votre demande et revient vers vous très prochainement.', ['entreprise' => $demande->company_name]),
            lignes: [
                __('Entreprise') => $demande->company_name,
                __('Berceaux souhaités') => $demande->berceaux_count ? __($demande->berceaux_count) : __('Non précisé'),
            ],
        ));

        EnvoiEmail::envoyer(config('mail.admin_address'), new NewSubmissionAlert(
            type: 'Nouvelle demande entreprise',
            numeroDossier: $demande->request_number,
            lignes: [
                'Entreprise' => $demande->company_name,
                'Contact' => $demande->contact_name,
                'Téléphone' => $demande->contact_tel,
                'Email' => $demande->contact_email,
                'Langue du formulaire' => LangueSite::libelleFrancais(),
            ],
            lienAdmin: route('admin.entreprises'),
        ), 'fr');

        return redirect()
            ->to(route('berceaux-entreprise').'#devis-form')
            ->with('success', __('Merci, votre demande de devis a bien été envoyée. Notre équipe revient vers vous très prochainement.'));
    }
}
