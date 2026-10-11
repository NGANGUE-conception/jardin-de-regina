<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('corporate_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();

            $table->string('company_name');
            $table->string('sector')->nullable();
            $table->string('employees_count')->nullable();
            $table->string('berceaux_count')->nullable();

            $table->string('contact_name');
            $table->string('contact_role')->nullable();
            $table->string('contact_email');
            $table->string('contact_tel');

            $table->text('message')->nullable();

            $table->string('status')->default('nouvelle'); // nouvelle | en_discussion | signee | refusee
            $table->text('internal_note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('corporate_requests');
    }
};
