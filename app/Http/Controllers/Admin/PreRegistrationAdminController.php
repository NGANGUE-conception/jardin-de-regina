<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PreRegistrationsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreManualPreRegistrationRequest;
use App\Mail\SubmissionConfirmation;
use App\Models\PreRegistration;
use App\Support\EnvoiEmail;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PreRegistrationAdminController extends Controller
{
    public function export()
    {
        $nomFichier = 'pre-inscriptions-'.now()->format('Y-m-d').'.xlsx';

        return Excel::download(new PreRegistrationsExport, $nomFichier);
    }

    public function index()
    {
        return view('pages.admin.pre-inscriptions', [
            'dossiers' => PreRegistration::latest()->get()->map->toAdminArray()->values(),
        ]);
    }

    /**
     * Pré-inscription saisie à la main par la direction (demande reçue par téléphone, sur place...).
     * L'email de confirmation n'est envoyé à la famille que si la direction le demande.
     */
    public function store(StoreManualPreRegistrationRequest $request)
    {
        $donnees = $request->safe()->except(['envoyer_confirmation', 'famille_informee']);
        $donnees['care_days'] = $donnees['care_days'] ?? null;
        $donnees['special_needs'] = filled($donnees['special_needs_details'] ?? null);
        $donnees['dossier_number'] = PreRegistration::genererNumeroDossier();
        $donnees['rgpd_consent'] = true;
        $donnees['status'] = 'nouvelle';

        $dossier = PreRegistration::create($donnees);

        $message = "Dossier {$dossier->dossier_number} créé pour {$dossier->nomComplet()}.";

        if ($request->boolean('envoyer_confirmation') && $dossier->p1_email) {
            $envoye = EnvoiEmail::envoyer($dossier->p1_email, new SubmissionConfirmation(
                titre: 'Votre pré-inscription est bien enregistrée',
                numeroDossier: $dossier->dossier_number,
                intro: "Suite à notre échange, la demande de pré-inscription pour {$dossier->nomComplet()} a bien été enregistrée auprès du Jardin de Régina. Notre équipe étudie votre dossier et vous recontactera très prochainement.",
                lignes: [
                    'Enfant' => $dossier->nomComplet(),
                    "Date d'entrée souhaitée" => $dossier->entry_date->translatedFormat('j F Y'),
                    "Type d'accueil" => $dossier->accueilLabel(),
                ],
            ));

            $message .= $envoye
                ? " Email de confirmation envoyé à {$dossier->p1_email}."
                : " Attention : l'email de confirmation n'a pas pu être envoyé, prévenez la famille par téléphone.";
        }

        return redirect()
            ->route('admin.pre-inscriptions')
            ->with('success', $message)
            ->with('dossier_ouvert', $dossier->dossier_number);
    }

    public function updateStatus(Request $request, PreRegistration $dossier)
    {
        $request->validate([
            'status' => ['required', 'in:'.implode(',', PreRegistration::STATUTS)],
        ]);

        $dossier->update(['status' => $request->string('status')->toString()]);

        return back()->with('success', 'Statut du dossier mis à jour.');
    }

    public function updateNote(Request $request, PreRegistration $dossier)
    {
        $request->validate([
            'internal_note' => ['nullable', 'string', 'max:3000'],
        ]);

        $dossier->update(['internal_note' => $request->input('internal_note')]);

        return back()->with('success', 'Note interne enregistrée.');
    }

    /**
     * Suppression définitive (route réservée à l'admin connecté).
     */
    public function destroy(PreRegistration $dossier)
    {
        $numero = $dossier->dossier_number;
        $dossier->delete();

        return back()->with('success', "Dossier {$numero} supprimé définitivement.");
    }
}
