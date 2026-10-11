<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CorporateRequest;
use Illuminate\Http\Request;

class CorporateRequestAdminController extends Controller
{
    public function index()
    {
        return view('pages.admin.entreprises', [
            'demandes' => CorporateRequest::latest()->get()->map->toAdminArray()->values(),
        ]);
    }

    public function updateStatus(Request $request, CorporateRequest $demande)
    {
        $request->validate([
            'status' => ['required', 'in:'.implode(',', CorporateRequest::STATUTS)],
            'internal_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $demande->update([
            'status' => $request->string('status')->toString(),
            'internal_note' => $request->input('internal_note', $demande->internal_note),
        ]);

        return back()->with('success', 'Statut de la demande mis à jour.');
    }

    /**
     * Suppression définitive (route réservée à l'admin connecté).
     */
    public function destroy(CorporateRequest $demande)
    {
        $numero = $demande->request_number;
        $demande->delete();

        return back()->with('success', "Demande {$numero} supprimée définitivement.");
    }
}
