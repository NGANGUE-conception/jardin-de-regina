<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePreRegistrationRequest;
use App\Mail\NewSubmissionAlert;
use App\Mail\SubmissionConfirmation;
use App\Models\PreRegistration;
use App\Support\EnvoiEmail;
use App\Support\LangueSite;
use App\Support\SuiviVisites;

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

        EnvoiEmail::envoyer($dossier->p1_email, new SubmissionConfirmation(
            titre: __('Votre pré-inscription est bien reçue'),
            numeroDossier: $dossier->dossier_number,
            intro: __('Merci d\'avoir déposé une demande de pré-inscription pour :enfant auprès du Jardin de Régina. Notre équipe étudie votre dossier et vous recontactera très prochainement.', ['enfant' => $dossier->nomComplet()]),
            lignes: [
                __('Enfant') => $dossier->nomComplet(),
                __("Date d'entrée souhaitée") => $dossier->entry_date->translatedFormat('j F Y'),
                __("Type d'accueil") => $dossier->accueilLabel(),
            ],
        ));

        EnvoiEmail::envoyer(config('mail.admin_address'), new NewSubmissionAlert(
            type: 'Nouvelle pré-inscription',
            numeroDossier: $dossier->dossier_number,
            lignes: [
                'Enfant' => $dossier->nomComplet(),
                'Parent' => trim($dossier->p1_prenom.' '.$dossier->p1_nom),
                'Téléphone' => $dossier->p1_tel,
                'Email' => $dossier->p1_email,
                'Langue du formulaire' => LangueSite::libelleFrancais(),
            ],
            lienAdmin: route('admin.pre-inscriptions'),
        ), 'fr');

        return redirect()
            ->route('confirmation-preinscription', ['dossier' => $dossier->dossier_number]);
    }
}
