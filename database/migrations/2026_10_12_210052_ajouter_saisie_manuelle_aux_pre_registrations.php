<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Pré-inscriptions saisies à la main par la direction (demande reçue par téléphone, sur place...) :
 *  - l'email du parent devient facultatif (une famille peut ne pas le donner au téléphone) ;
 *  - "source" indique d'où vient la demande (site, téléphone, sur place, email, autre).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pre_registrations', function (Blueprint $table) {
            $table->string('p1_email')->nullable()->change();
            $table->string('source')->default('site')->after('status');
        });
    }

    public function down(): void
    {
        DB::table('pre_registrations')->whereNull('p1_email')->update(['p1_email' => '']);

        Schema::table('pre_registrations', function (Blueprint $table) {
            $table->dropColumn('source');
            $table->string('p1_email')->nullable(false)->default('')->change();
        });
    }
};
