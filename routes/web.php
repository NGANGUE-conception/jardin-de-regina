<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\ContactMessageAdminController;
use App\Http\Controllers\Admin\CorporateRequestAdminController;
use App\Http\Controllers\Admin\JobApplicationAdminController;
use App\Http\Controllers\Admin\ParametresController;
use App\Http\Controllers\Admin\PreRegistrationAdminController;
use App\Http\Controllers\Admin\SiteVisitAdminController;
use App\Http\Controllers\Admin\VisiteAdminController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\CorporateRequestController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\PreferenceStatistiquesController;
use App\Http\Controllers\PreRegistrationController;
use Illuminate\Support\Facades\Route;

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
Route::post('/berceaux-entreprise/devis', [CorporateRequestController::class, 'store'])->name('berceaux-entreprise.store');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');
Route::post('/contact', [ContactMessageController::class, 'store'])->name('contact.store');

Route::get('/recrutement', function () {
    return view('pages.recrutement');
})->name('recrutement');
Route::post('/recrutement/candidature', [JobApplicationController::class, 'store'])->name('recrutement.store');

Route::get('/pre-inscription', function () {
    return view('pages.pre-inscription');
})->name('pre-inscription');
Route::post('/pre-inscription', [PreRegistrationController::class, 'store'])->name('pre-inscription.store');

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
    return view('pages.bientot-disponible', ['titre' => 'Plan du site']);
})->name('plan-du-site');

/*
Espace admin Toutes ces pages lisent les vraies données enregistrées en base par les formulaires publics, et sont protégées par une connexion (voir plus bas).
*/

Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->middleware('throttle:connexion-admin') // 5 essais par minute (voir AppServiceProvider)
    ->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware('auth')->group(function () {
    Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin');
    Route::patch('/admin/dossiers/{dossier}/statut', [AdminDashboardController::class, 'updateStatus'])->name('admin.dossiers.statut');

    Route::get('/admin/pre-inscriptions', [PreRegistrationAdminController::class, 'index'])->name('admin.pre-inscriptions');
    Route::patch('/admin/pre-inscriptions/{dossier}/statut', [PreRegistrationAdminController::class, 'updateStatus'])->name('admin.pre-inscriptions.statut');
    Route::patch('/admin/pre-inscriptions/{dossier}/note', [PreRegistrationAdminController::class, 'updateNote'])->name('admin.pre-inscriptions.note');
    Route::get('/admin/pre-inscriptions/export', [PreRegistrationAdminController::class, 'export'])->name('admin.pre-inscriptions.export');

    // Journal des visites : onglet "Rendez-vous" (visites de la crèche programmées par la direction)
    Route::get('/admin/journal-des-visites', [VisiteAdminController::class, 'index'])->name('admin.journal-visites');
    Route::get('/admin/journal-des-visites/export', [VisiteAdminController::class, 'export'])->name('admin.visites.export');
    Route::post('/admin/journal-des-visites', [VisiteAdminController::class, 'store'])->name('admin.visites.store');
    Route::put('/admin/journal-des-visites/{visite}', [VisiteAdminController::class, 'update'])->name('admin.visites.update');
    Route::patch('/admin/journal-des-visites/{visite}/statut', [VisiteAdminController::class, 'updateStatus'])->name('admin.visites.statut');
    Route::delete('/admin/journal-des-visites/{visite}', [VisiteAdminController::class, 'destroy'])->name('admin.visites.destroy');

    // Journal des visites : onglet "Visites du site" (mesure d'audience anonyme du site public)
    Route::get('/admin/journal-des-visites/site', [SiteVisitAdminController::class, 'index'])->name('admin.visites-site');
    Route::get('/admin/journal-des-visites/site/export', [SiteVisitAdminController::class, 'export'])->name('admin.visites-site.export');
    Route::delete('/admin/journal-des-visites/site/{visite}', [SiteVisitAdminController::class, 'destroy'])->name('admin.visites-site.destroy');

    Route::get('/admin/demandes-entreprises', [CorporateRequestAdminController::class, 'index'])->name('admin.entreprises');
    Route::patch('/admin/demandes-entreprises/{demande}/statut', [CorporateRequestAdminController::class, 'updateStatus'])->name('admin.entreprises.statut');

    Route::get('/admin/candidatures', [JobApplicationAdminController::class, 'index'])->name('admin.candidatures');
    Route::patch('/admin/candidatures/{candidature}/statut', [JobApplicationAdminController::class, 'updateStatus'])->name('admin.candidatures.statut');
    Route::get('/admin/candidatures/{candidature}/documents/{index}', [JobApplicationAdminController::class, 'downloadDocument'])->name('admin.candidatures.document');

    Route::get('/admin/messages', [ContactMessageAdminController::class, 'index'])->name('admin.messages');
    Route::patch('/admin/messages/{message}/statut', [ContactMessageAdminController::class, 'updateStatus'])->name('admin.messages.statut');

    Route::get('/admin/parametres', [ParametresController::class, 'index'])->name('admin.parametres');
    // Modifier l'email ou le mot de passe exige d'avoir vérifié son identité dans les 10 dernières minutes.
    Route::post('/admin/parametres/verifier-identite', [ParametresController::class, 'verifierIdentite'])
        ->middleware('throttle:verification-identite') // 5 essais par minute (voir AppServiceProvider)
        ->name('admin.parametres.verifier-identite');
    Route::post('/admin/parametres/verrouiller', [ParametresController::class, 'verrouiller'])->name('admin.parametres.verrouiller');
    Route::put('/admin/parametres/email', [ParametresController::class, 'updateEmail'])->name('admin.parametres.email');
    Route::put('/admin/parametres/mot-de-passe', [ParametresController::class, 'updatePassword'])->name('admin.parametres.mot-de-passe');
});
