<?php

namespace App\Http\Controllers\Admin;

use App\Exports\VisitesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVisiteRequest;
use App\Models\CorporateRequest;
use App\Models\JobApplication;
use App\Models\PreRegistration;
use App\Models\Visite;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class VisiteAdminController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'du' => ['nullable', 'date'],
            'au' => ['nullable', 'date'],
        ]);

        $journees = $this->requeteFiltree($request)->get()
            ->groupBy(fn (Visite $visite) => $visite->visit_date->toDateString())
            ->map(function ($visites, $date) {
                $jour = Carbon::parse($date);

                return [
                    'date' => $this->libelleJour($jour),
                    'dateIso' => $date,
                    'dateComplete' => $jour->translatedFormat('l j F Y'),
                    'items' => $visites->map->toAdminArray()->values(),
                ];
            })
            ->values();

        return view('pages.admin.journal-visites', [
            'journees' => $journees,
            'dossiers' => $this->dossiersLiables(),
            'types' => Visite::TYPES,
            'filtres' => $request->only('du', 'au'),
        ]);
    }

    public function store(StoreVisiteRequest $request)
    {
        $data = $this->donneesVisite($request);
        $data['status'] ??= 'a_confirmer';

        Visite::create($data);

        return back()->with('success', 'Visite ajoutée au journal.');
    }

    public function update(StoreVisiteRequest $request, Visite $visite)
    {
        $data = $this->donneesVisite($request);
        $data['status'] ??= $visite->status;

        $visite->update($data);

        return back()->with('success', 'Visite mise à jour.');
    }

    public function updateStatus(Request $request, Visite $visite)
    {
        $request->validate([
            'status' => ['required', 'in:'.implode(',', Visite::STATUTS)],
        ]);

        $visite->update(['status' => $request->string('status')->toString()]);

        return back()->with('success', 'Statut de la visite mis à jour.');
    }

    public function destroy(Visite $visite)
    {
        $visite->delete();

        return back()->with('success', 'Visite supprimée du journal.');
    }

    public function export(Request $request)
    {
        $request->validate([
            'du' => ['nullable', 'date'],
            'au' => ['nullable', 'date'],
        ]);

        $periode = collect([
            $request->filled('du') ? 'du-'.$request->date('du')->format('Y-m-d') : null,
            $request->filled('au') ? 'au-'.$request->date('au')->format('Y-m-d') : null,
        ])->filter()->implode('-') ?: 'complet';

        return Excel::download(
            new VisitesExport($this->requeteFiltree($request)->get()),
            'journal-des-rendez-vous-'.$periode.'.xlsx',
        );
    }

    /**
     * Requête de base du journal, filtrée par période (?du=...&au=...).
     * Réutilisée par l'export Excel.
     */
    private function requeteFiltree(Request $request): Builder
    {
        return Visite::with('visitable')
            ->when($request->filled('du'), fn ($q) => $q->whereDate('visit_date', '>=', $request->date('du')))
            ->when($request->filled('au'), fn ($q) => $q->whereDate('visit_date', '<=', $request->date('au')))
            ->orderByDesc('visit_date')
            ->orderBy('visit_time');
    }

    /**
     * Transforme le champ "dossier" (ex. "pre_registration:12") en visitable_type / visitable_id.
     */
    private function donneesVisite(StoreVisiteRequest $request): array
    {
        $data = $request->safe()->except('dossier');
        $data['visitable_type'] = null;
        $data['visitable_id'] = null;

        if ($request->filled('dossier')) {
            [$type, $id] = explode(':', $request->input('dossier'));
            $modele = Relation::getMorphedModel($type);

            if (! $modele::whereKey($id)->exists()) {
                throw ValidationException::withMessages(['dossier' => "Ce dossier n'existe plus."]);
            }

            $data['visitable_type'] = $type;
            $data['visitable_id'] = (int) $id;
        }

        return $data;
    }

    private function libelleJour(Carbon $jour): string
    {
        return match (true) {
            $jour->isToday() => "Aujourd'hui",
            $jour->isTomorrow() => 'Demain',
            $jour->isYesterday() => 'Hier',
            default => ucfirst($jour->translatedFormat('l j F')),
        };
    }

    /**
     * Liste des dossiers qu'on peut rattacher à une visite (pour la liste déroulante du formulaire).
     */
    private function dossiersLiables(): array
    {
        $preInscriptions = PreRegistration::latest()->get()->map(fn ($d) => [
            'valeur' => 'pre_registration:'.$d->id,
            'groupe' => 'Pré-inscriptions',
            'libelle' => $d->dossier_number.' — '.$d->nomComplet(),
            'nom' => 'Famille '.$d->p1_nom,
            'tel' => $d->p1_tel,
            'email' => $d->p1_email,
            'type' => 'preinscription',
        ]);

        $entreprises = CorporateRequest::latest()->get()->map(fn ($d) => [
            'valeur' => 'corporate_request:'.$d->id,
            'groupe' => 'Demandes entreprises',
            'libelle' => $d->request_number.' — '.$d->company_name,
            'nom' => $d->contact_name.' ('.$d->company_name.')',
            'tel' => $d->contact_tel,
            'email' => $d->contact_email,
            'type' => 'entreprise',
        ]);

        $candidatures = JobApplication::latest()->get()->map(fn ($d) => [
            'valeur' => 'job_application:'.$d->id,
            'groupe' => 'Candidatures',
            'libelle' => $d->application_number.' — '.$d->nomComplet(),
            'nom' => $d->nomComplet(),
            'tel' => $d->tel,
            'email' => $d->email,
            'type' => 'candidature',
        ]);

        return $preInscriptions->concat($entreprises)->concat($candidatures)->values()->all();
    }
}
