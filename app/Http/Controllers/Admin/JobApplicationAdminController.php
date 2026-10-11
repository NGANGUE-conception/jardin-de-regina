<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class JobApplicationAdminController extends Controller
{
    public function index()
    {
        return view('pages.admin.candidatures', [
            'candidatures' => JobApplication::latest()->get()->map->toAdminArray()->values(),
        ]);
    }

    public function updateStatus(Request $request, JobApplication $candidature)
    {
        $request->validate([
            'status' => ['required', 'in:'.implode(',', JobApplication::STATUTS)],
        ]);

        $candidature->update(['status' => $request->string('status')->toString()]);

        return back()->with('success', 'Statut de la candidature mis à jour.');
    }

    public function downloadDocument(JobApplication $candidature, int $index): StreamedResponse
    {
        $chemin = $candidature->documents[$index] ?? null;

        abort_if(! $chemin || ! Storage::disk('local')->exists($chemin), 404);

        return Storage::disk('local')->download($chemin);
    }

    /**
     * Suppression définitive (route réservée à l'admin connecté).
     */
    public function destroy(JobApplication $candidature)
    {
        $numero = $candidature->application_number;
        $candidature->delete();

        return back()->with('success', "Candidature {$numero} et ses documents supprimés définitivement.");
    }
}
