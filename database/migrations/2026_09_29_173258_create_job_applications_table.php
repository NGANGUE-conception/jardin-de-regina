<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number')->unique();

            $table->string('nom');
            $table->string('prenom');
            $table->string('email');
            $table->string('tel');
            $table->string('poste');
            $table->text('message');
            $table->json('documents')->nullable(); // chemins de stockage des fichiers (CV, lettre...)

            $table->string('status')->default('nouvelle'); // nouvelle | entretien_prevu | retenue | refusee
            $table->text('internal_note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
