<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Réglages modifiables depuis l'espace admin (Paramètres → Établissement) : téléphone, email, adresse, horaires...
 * Une ligne par réglage. Tant qu'un réglage n'a jamais été modifié, la valeur de config/creche.php s'applique.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parametres', function (Blueprint $table) {
            $table->string('cle', 50)->primary();
            $table->text('valeur')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parametres');
    }
};
