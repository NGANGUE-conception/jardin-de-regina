<?php

use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\CorporateRequestController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\PreferenceStatistiquesController;
use App\Http\Controllers\PreRegistrationController;
use App\Support\PagesPubliques;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Pages et formulaires du site public
|--------------------------------------------------------------------------
| Chargées deux fois par routes/web.php : en français (/contact, nom « contact »)
| et en anglais (/en/contact, nom « en.contact »). Toute nouvelle page publique
| ajoutée ici existe donc automatiquement dans les deux langues.
| Dans les vues, les liens se font avec route_langue('contact') (voir app/helpers.php).
*/

Route::get('/', function () {
    return view('pages.accueil');
})->name('accueil');

Route::get('/notre-projet', function () {
    return view('pages.notre-projet');
})->name('notre-projet');

Route::get('/fonctionnement', function () {
    return view('pages.fonctionnement');
})->name('fonctionnement');

Route::get('/tarifs', function () {
    return view('pages.tarifs');
})->name('tarifs');

Route::get('/berceaux-entreprise', function () {
    return view('pages.berceaux-entreprise');
})->name('berceaux-entreprise');
Route::post('/berceaux-entreprise/devis', [CorporateRequestController::class, 'store'])
    ->middleware(['throttle:formulaires-publics', 'anti-spam'])
    ->name('berceaux-entreprise.store');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');
Route::post('/contact', [ContactMessageController::class, 'store'])
    ->middleware(['throttle:formulaires-publics', 'anti-spam'])
    ->name('contact.store');

Route::get('/recrutement', function () {
    return view('pages.recrutement');
})->name('recrutement');
Route::post('/recrutement/candidature', [JobApplicationController::class, 'store'])
    ->middleware(['throttle:formulaires-publics', 'anti-spam'])
    ->name('recrutement.store');

Route::get('/pre-inscription', function () {
    return view('pages.pre-inscription');
})->name('pre-inscription');
Route::post('/pre-inscription', [PreRegistrationController::class, 'store'])
    ->middleware(['throttle:formulaires-publics', 'anti-spam'])
    ->name('pre-inscription.store');

Route::get('/pre-inscription/confirmation', function () {
    return view('pages.confirmation-preinscription', [
        'numeroDossier' => request('dossier'),
    ]);
})->name('confirmation-preinscription');

Route::get('/mentions-legales', function () {
    return view('pages.mentions-legales');
})->name('mentions-legales');

Route::get('/politique-de-confidentialite', function () {
    return view('pages.politique-de-confidentialite');
})->name('politique-de-confidentialite');

Route::get('/gestion-des-cookies', function () {
    return view('pages.gestion-des-cookies');
})->name('gestion-des-cookies');
Route::post('/gestion-des-cookies/statistiques', [PreferenceStatistiquesController::class, 'update'])->name('gestion-des-cookies.statistiques');

Route::get('/plan-du-site', function () {
    return view('pages.plan-du-site', ['rubriques' => PagesPubliques::parRubrique()]);
})->name('plan-du-site');
