<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PreRegistrationsExport;
use App\Http\Controllers\Controller;
use App\Models\PreRegistration;
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
