<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();

            // Quand
            $table->date('visit_date');
            $table->time('visit_time');

            // Qui / quoi
            $table->string('visitor_name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('type');                         // preinscription, suivi, entreprise, candidature et autre

            // Dossier lié (optionnel) : une pré-inscription, une demande entreprise ou une candidature
            $table->nullableMorphs('visitable');

            // Suivi admin
            $table->string('status')->default('a_confirmer'); // a_confirmer | confirmee | realisee | annulee | absent
            $table->text('internal_note')->nullable();      // compte rendu de la visite

            $table->timestamps();

            $table->index(['visit_date', 'visit_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
