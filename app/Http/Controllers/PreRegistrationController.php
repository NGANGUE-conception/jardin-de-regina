<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePreRegistrationRequest;
use App\Mail\NewSubmissionAlert;
use App\Mail\SubmissionConfirmation;
use App\Models\PreRegistration;
use App\Support\SuiviVisites;
use Illuminate\Support\Facades\Mail;

class PreRegistrationController extends Controller
{
    public function store(StorePreRegistrationRequest $request, SuiviVisites $suivi)
    {
        $data = $request->validated();
        $data['dossier_number'] = PreRegistration::genererNumeroDossier();
        $data['special_needs'] = $request->boolean('special_needs');
        $data['single_parent'] = $request->boolean('single_parent');
        $data['newsletter_opt_in'] = $request->boolean('newsletter_opt_in');
        $data['rgpd_consent'] = true;
        $data['status'] = 'nouvelle';

        $dossier = PreRegistration::create($data);
        $suivi->rattacherFormulaire($request, $dossier);

        Mail::to($dossier->p1_email)->send(new SubmissionConfirmation(
            titre: 'Votre pré-inscription est bien reçue',
            numeroDossier: $dossier->dossier_number,
            intro: "Merci d'avoir déposé une demande de pré-inscription pour {$dossier->nomComplet()} auprès du Jardin de Régina. Notre équipe étudie votre dossier et vous recontactera très prochainement.",
            lignes: [
                'Enfant' => $dossier->nomComplet(),
                "Date d'entrée souhaitée" => $dossier->entry_date->translatedFormat('j F Y'),
                "Type d'accueil" => $dossier->accueilLabel(),
            ],
        ));

        Mail::to(config('mail.admin_address'))->send(new NewSubmissionAlert(
            type: 'Nouvelle pré-inscription',
            numeroDossier: $dossier->dossier_number,
            lignes: [
                'Enfant' => $dossier->nomComplet(),
                'Parent' => trim($dossier->p1_prenom.' '.$dossier->p1_nom),
                'Téléphone' => $dossier->p1_tel,
                'Email' => $dossier->p1_email,
            ],
            lienAdmin: route('admin.pre-inscriptions'),
        ));

        return redirect()
            ->route('confirmation-preinscription', ['dossier' => $dossier->dossier_number]);
    }
}
