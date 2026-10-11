<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PreRegistration;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $dossiers = PreRegistration::latest()->get();

        $nouvelles = $dossiers->where('status', 'nouvelle')->count();
        $attente = $dossiers->where('status', 'attente')->count();
        $acceptees = $dossiers->where('status', 'acceptee')->count();

        $stats = [
            'nouvelles' => $nouvelles,
            'nouvellesSemaine' => $dossiers->where('status', 'nouvelle')
                ->where('created_at', '>=', now()->subWeek())
                ->count(),
            'attente' => $attente,
            'acceptees' => $acceptees,
            'remplissagePct' => $acceptees > 0 ? (int) round(min($acceptees, 12) / 12 * 100) : 0,
        ];

        return view('pages.admin.dashboard', [
            'dossiers' => $dossiers->map->toAdminArray()->values(),
            'stats' => $stats,
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
}
