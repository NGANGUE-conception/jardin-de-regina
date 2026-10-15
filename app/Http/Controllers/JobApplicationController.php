<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobApplicationRequest;
use App\Mail\NewSubmissionAlert;
use App\Mail\SubmissionConfirmation;
use App\Models\JobApplication;
use App\Support\EnvoiEmail;
use App\Support\SuiviVisites;

class JobApplicationController extends Controller
{
    public function store(StoreJobApplicationRequest $request, SuiviVisites $suivi)
    {
        $data = $request->validated();
        unset($data['rgpd_consent'], $data['documents']);

        $chemins = [];
        foreach ($request->file('documents', []) as $fichier) {
            $chemins[] = $fichier->store('candidatures', 'local');
        }

        $data['documents'] = $chemins;
        $data['application_number'] = JobApplication::genererNumero();
        $data['status'] = 'nouvelle';

        $candidature = JobApplication::create($data);
        $suivi->rattacherFormulaire($request, $candidature);

        EnvoiEmail::envoyer($candidature->email, new SubmissionConfirmation(
            titre: 'Votre candidature est bien reçue',
            numeroDossier: $candidature->application_number,
            intro: 'Merci pour votre candidature. Notre direction pédagogique l\'examinera avec soin sous 5 jours ouvrés.',
            lignes: [
                'Poste souhaité' => $candidature->poste,
            ],
        ));

        EnvoiEmail::envoyer(config('mail.admin_address'), new NewSubmissionAlert(
            type: 'Nouvelle candidature',
            numeroDossier: $candidature->application_number,
            lignes: [
                'Candidat' => $candidature->nomComplet(),
                'Poste souhaité' => $candidature->poste,
                'Téléphone' => $candidature->tel,
                'Email' => $candidature->email,
            ],
            lienAdmin: route('admin.candidatures'),
        ));

        return redirect()
            ->to(route('recrutement').'#candidature')
            ->with('success', "Merci pour votre candidature ! Notre direction pédagogique l'examinera avec soin sous 5 jours ouvrés.");
    }
}
