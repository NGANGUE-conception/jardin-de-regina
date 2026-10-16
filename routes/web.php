<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\ContactMessageAdminController;
use App\Http\Controllers\Admin\CorporateRequestAdminController;
use App\Http\Controllers\Admin\JobApplicationAdminController;
use App\Http\Controllers\Admin\MotDePasseOublieController;
use App\Http\Controllers\Admin\ParametresController;
use App\Http\Controllers\Admin\PreRegistrationAdminController;
use App\Http\Controllers\Admin\SiteVisitAdminController;
use App\Http\Controllers\Admin\VisiteAdminController;
use App\Http\Controllers\ReferencementController;
use Illuminate\Support\Facades\Route;

// Site public : en français (adresses d'origine), puis en anglais sous /en (voir routes/publiques.php).
Route::group([], base_path('routes/publiques.php'));
Route::prefix('en')->name('en.')->group(base_path('routes/publiques.php'));

// Fichiers pour les moteurs de recherche (générés à la volée, voir ReferencementController).
Route::get('/sitemap.xml', [ReferencementController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [ReferencementController::class, 'robots'])->name('robots');

/*
Espace admin Toutes ces pages lisent les vraies données enregistrées en base par les formulaires publics, et sont protégées par une connexion (voir plus bas).
*/

Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->middleware('throttle:connexion-admin') // 5 essais par minute (voir AppServiceProvider)
    ->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Mot de passe oublié (uniquement sans être connecté) : demande du lien par email, puis choix du nouveau mot de passe.
Route::middleware('guest')->group(function () {
    Route::get('/admin/mot-de-passe-oublie', [MotDePasseOublieController::class, 'demande'])->name('admin.password.request');
    Route::post('/admin/mot-de-passe-oublie', [MotDePasseOublieController::class, 'envoyerLien'])
        ->middleware('throttle:mot-de-passe-oublie') // voir AppServiceProvider
        ->name('admin.password.email');
    Route::get('/admin/reinitialiser-mot-de-passe/{token}', [MotDePasseOublieController::class, 'formulaire'])->name('admin.password.reset');
    Route::post('/admin/reinitialiser-mot-de-passe', [MotDePasseOublieController::class, 'reinitialiser'])
        ->middleware('throttle:reinitialisation-mot-de-passe') // compteur séparé : quelques fautes de saisie ne bloquent pas
        ->name('admin.password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin');
    Route::patch('/admin/dossiers/{dossier}/statut', [AdminDashboardController::class, 'updateStatus'])->name('admin.dossiers.statut');

    Route::get('/admin/pre-inscriptions', [PreRegistrationAdminController::class, 'index'])->name('admin.pre-inscriptions');
    Route::post('/admin/pre-inscriptions', [PreRegistrationAdminController::class, 'store'])->name('admin.pre-inscriptions.store');
    Route::patch('/admin/pre-inscriptions/{dossier}/statut', [PreRegistrationAdminController::class, 'updateStatus'])->name('admin.pre-inscriptions.statut');
    Route::patch('/admin/pre-inscriptions/{dossier}/note', [PreRegistrationAdminController::class, 'updateNote'])->name('admin.pre-inscriptions.note');
    Route::delete('/admin/pre-inscriptions/{dossier}', [PreRegistrationAdminController::class, 'destroy'])->name('admin.pre-inscriptions.destroy');
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
    Route::delete('/admin/demandes-entreprises/{demande}', [CorporateRequestAdminController::class, 'destroy'])->name('admin.entreprises.destroy');

    Route::get('/admin/candidatures', [JobApplicationAdminController::class, 'index'])->name('admin.candidatures');
    Route::patch('/admin/candidatures/{candidature}/statut', [JobApplicationAdminController::class, 'updateStatus'])->name('admin.candidatures.statut');
    Route::delete('/admin/candidatures/{candidature}', [JobApplicationAdminController::class, 'destroy'])->name('admin.candidatures.destroy');
    Route::get('/admin/candidatures/{candidature}/documents/{index}', [JobApplicationAdminController::class, 'downloadDocument'])->name('admin.candidatures.document');

    Route::get('/admin/messages', [ContactMessageAdminController::class, 'index'])->name('admin.messages');
    Route::patch('/admin/messages/{message}/statut', [ContactMessageAdminController::class, 'updateStatus'])->name('admin.messages.statut');
    Route::delete('/admin/messages/{message}', [ContactMessageAdminController::class, 'destroy'])->name('admin.messages.destroy');

    Route::get('/admin/parametres', [ParametresController::class, 'index'])->name('admin.parametres');
    Route::put('/admin/parametres/etablissement', [ParametresController::class, 'updateEtablissement'])->name('admin.parametres.etablissement');
    // Modifier l'email ou le mot de passe exige d'avoir vérifié son identité dans les 10 dernières minutes.
    Route::post('/admin/parametres/verifier-identite', [ParametresController::class, 'verifierIdentite'])
        ->middleware('throttle:verification-identite') // 5 essais par minute (voir AppServiceProvider)
        ->name('admin.parametres.verifier-identite');
    Route::post('/admin/parametres/verrouiller', [ParametresController::class, 'verrouiller'])->name('admin.parametres.verrouiller');
    Route::put('/admin/parametres/email', [ParametresController::class, 'updateEmail'])->name('admin.parametres.email');
    Route::put('/admin/parametres/mot-de-passe', [ParametresController::class, 'updatePassword'])->name('admin.parametres.mot-de-passe');
});
