<?php

use App\Models\SiteVisit;
use App\Support\SuiviVisites;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
| Mesure d'audience : suppression des visites du site de plus de 25 mois
| (durée de conservation maximale recommandée par la CNIL). Les pages vues et
| les liens vers les formulaires sont supprimés avec (suppression en cascade) ;
| les formulaires eux-mêmes (pré-inscriptions, etc.) ne sont jamais touchés.
*/
Artisan::command('visites:purger', function () {
    $limite = now()->subMonthsNoOverflow(SuiviVisites::CONSERVATION_MOIS);
    $nombre = SiteVisit::where('started_at', '<', $limite)->delete();

    $this->info("{$nombre} visite(s) antérieure(s) au {$limite->format('d/m/Y')} supprimée(s).");
})->purpose('Supprime les statistiques de visite du site de plus de 25 mois (RGPD)');

Schedule::command('visites:purger')->dailyAt('03:00');
