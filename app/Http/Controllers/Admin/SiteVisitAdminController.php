<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SiteVisitsExport;
use App\Http\Controllers\Controller;
use App\Models\SiteVisit;
use App\Support\StatistiquesVisites;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SiteVisitAdminController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['formulaires' => ['nullable', 'boolean']]);

        $stats = StatistiquesVisites::depuisRequete($request);

        $liste = $stats->requete()
            ->when($request->boolean('formulaires'), fn ($q) => $q->has('formulaires'))
            ->with(['pageViews', 'formulaires.formulaire'])
            ->paginate(25)
            ->withQueryString();

        return view('pages.admin.visites-site', [
            'stats' => $stats,
            'resume' => $stats->resume(),
            'serie' => $stats->serie(),
            'sources' => $stats->sources(),
            'appareils' => $stats->appareils(),
            'pages' => $stats->pages(),
            'liste' => $liste,
            'visites' => collect($liste->items())->map->toAdminArray()->values(),
            'seulementFormulaires' => $request->boolean('formulaires'),
        ]);
    }

    public function export(Request $request)
    {
        $stats = StatistiquesVisites::depuisRequete($request);

        $nomFichier = 'visites-du-site-'.$stats->debut->format('Y-m-d').'-au-'.$stats->fin->format('Y-m-d').'.xlsx';

        return Excel::download(new SiteVisitsExport($stats), $nomFichier);
    }

    public function destroy(SiteVisit $visite)
    {
        $visite->delete();

        return back()->with('success', 'Visite supprimée des statistiques.');
    }
}
