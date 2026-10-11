<?php

namespace App\Providers;

use App\Models\ContactMessage;
use App\Models\CorporateRequest;
use App\Models\JobApplication;
use App\Models\PreRegistration;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Noms courts enregistrés dans les colonnes "..._type" des relations polymorphes
        // (visites programmées et formulaires envoyés pendant une visite du site).
        Relation::enforceMorphMap([
            'pre_registration' => PreRegistration::class,
            'corporate_request' => CorporateRequest::class,
            'job_application' => JobApplication::class,
            'contact_message' => ContactMessage::class,
        ]);

        // Protection contre les tentatives de devinette du mot de passe admin :
        // 5 essais par minute, avec un message clair au lieu d'une page d'erreur.
        RateLimiter::for('connexion-admin', fn (Request $request) => Limit::perMinute(5)
            ->by('connexion|'.strtolower((string) $request->input('email')).'|'.$request->ip())
            ->response(fn () => back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Trop de tentatives de connexion. Patientez une minute avant de réessayer.'])));

        RateLimiter::for('verification-identite', fn (Request $request) => Limit::perMinute(5)
            ->by('verification|'.$request->user()?->getAuthIdentifier().'|'.$request->ip())
            ->response(fn () => redirect()
                ->route('admin.parametres', ['onglet' => 'compte'])
                ->withErrors(['verification_identite' => 'Trop de tentatives. Patientez une minute avant de réessayer.'], 'identite')));
    }
}
