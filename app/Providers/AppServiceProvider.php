<?php

namespace App\Providers;

use App\Models\ContactMessage;
use App\Models\CorporateRequest;
use App\Models\JobApplication;
use App\Models\PreRegistration;
use App\Support\Etablissement;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Coordonnées de l'établissement (lues une seule fois par page).
        $this->app->singleton(Etablissement::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Coordonnées de l'établissement disponibles dans toutes les vues : {{ $etab->telephone() }}, etc.
        View::share('etab', $this->app->make(Etablissement::class));

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

        // Formulaires publics (pré-inscription, contact, devis, candidature) : 10 envois par heure
        // et par formulaire depuis une même connexion. Assez large pour une famille ou une entreprise
        // qui partage la même connexion, mais bloque les envois en masse.
        RateLimiter::for('formulaires-publics', fn (Request $request) => Limit::perHour(10)
            ->by('formulaire|'.$request->route()?->getName().'|'.$request->ip())
            ->response(fn () => back()
                ->withInput($request->except(['_token', 'documents', 'jr_site_web', 'jr_affiche_a']))
                ->withErrors(['anti_spam' => __('Vous avez envoyé plusieurs demandes en peu de temps. Merci de patienter un peu avant de réessayer, ou appelez-nous au :telephone.', [
                    'telephone' => app(Etablissement::class)->telephone(),
                ])])));

        // Mot de passe oublié : 5 demandes de lien par quart d'heure (évite d'inonder la boîte mail de l'admin).
        RateLimiter::for('mot-de-passe-oublie', fn (Request $request) => Limit::perMinutes(15, 5)
            ->by('mot-de-passe-oublie|'.$request->ip())
            ->response(fn () => back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Trop de demandes en peu de temps. Patientez quelques minutes avant de réessayer.'])));

        RateLimiter::for('reinitialisation-mot-de-passe', fn (Request $request) => Limit::perMinutes(15, 10)
            ->by('reinitialisation|'.$request->ip())
            ->response(fn () => back()
                ->withErrors(['password' => 'Trop d\'essais en peu de temps. Patientez quelques minutes avant de réessayer.'])));

        RateLimiter::for('verification-identite', fn (Request $request) => Limit::perMinute(5)
            ->by('verification|'.$request->user()?->getAuthIdentifier().'|'.$request->ip())
            ->response(fn () => redirect()
                ->route('admin.parametres', ['onglet' => 'compte'])
                ->withErrors(['verification_identite' => 'Trop de tentatives. Patientez une minute avant de réessayer.'], 'identite')));
    }
}
