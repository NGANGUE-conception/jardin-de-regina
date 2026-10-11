<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Mesure d'audience du site public (onglet "Visites du site" de l'admin).
 *
 * Données anonymes : aucune adresse IP n'est enregistrée. Le visitor_id est un
 * identifiant aléatoire (cookie "jr_visiteur") qui ne permet pas de savoir qui
 * est la personne. Seules les visites qui se terminent par un formulaire sont
 * reliées à une identité, via site_visit_forms.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_visits', function (Blueprint $table) {
            $table->id();
            $table->uuid('visitor_id')->index();
            $table->boolean('is_returning')->default(false); // visiteur déjà venu auparavant

            $table->dateTime('started_at')->index();
            $table->dateTime('last_activity_at');

            // Provenance
            $table->string('landing_path');
            $table->string('source', 50); // google | facebook | direct | autre ... (voir SiteVisit::SOURCES)
            $table->string('referrer_host', 191)->nullable();
            $table->string('campaign', 100)->nullable(); // paramètre utm_campaign (flyer, QR code...)

            // Appareil
            $table->string('device', 20); // mobile | tablette | ordinateur
            $table->string('browser', 30)->nullable();
            $table->string('os', 30)->nullable();

            $table->unsignedInteger('pages_count')->default(0);
        });

        Schema::create('site_page_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_visit_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->dateTime('viewed_at');
        });

        Schema::create('site_visit_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_visit_id')->constrained()->cascadeOnDelete();
            $table->morphs('formulaire'); // pre_registration | corporate_request | job_application | contact_message
            $table->dateTime('submitted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_visit_forms');
        Schema::dropIfExists('site_page_views');
        Schema::dropIfExists('site_visits');
    }
};
