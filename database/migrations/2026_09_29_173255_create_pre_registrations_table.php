<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pre_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('dossier_number')->unique();

            // Enfant
            $table->string('child_last_name');
            $table->string('child_first_name');
            $table->string('child_gender');
            $table->date('child_birthdate');
            $table->date('entry_date');
            $table->string('care_type'); // regulier | occasionnel | urgence
            $table->json('care_days')->nullable(); // ['lundi', 'mardi', ...]
            $table->boolean('special_needs')->default(false);
            $table->text('special_needs_details')->nullable();

            // Parent 1 (référent principal)
            $table->string('p1_civilite')->nullable();
            $table->string('p1_nom');
            $table->string('p1_prenom');
            $table->string('p1_email');
            $table->string('p1_tel');
            $table->string('p1_fixe')->nullable();
            $table->string('p1_profession')->nullable();
            $table->string('p1_employeur')->nullable();
            $table->string('p1_cp')->nullable();
            $table->string('p1_ville')->nullable();
            $table->string('p1_adresse')->nullable();
            $table->boolean('single_parent')->default(false);

            // Parent 2 (facultatif)
            $table->string('p2_civilite')->nullable();
            $table->string('p2_nom')->nullable();
            $table->string('p2_prenom')->nullable();
            $table->string('p2_email')->nullable();
            $table->string('p2_tel')->nullable();
            $table->string('p2_profession')->nullable();
            $table->string('p2_employeur')->nullable();

            $table->text('message')->nullable();
            $table->boolean('newsletter_opt_in')->default(false);
            $table->boolean('rgpd_consent')->default(false);

            // Suivi admin
            $table->string('status')->default('nouvelle'); // nouvelle | attente | acceptee | refusee
            $table->text('internal_note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pre_registrations');
    }
};
