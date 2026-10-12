<?php

namespace Database\Factories;

use App\Models\JobApplication;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Candidatures fictives, pour les tests automatiques.
 *
 * @extends Factory<JobApplication>
 */
class JobApplicationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'application_number' => sprintf('#JR-CAN-%04d', fake()->unique()->numberBetween(1, 9999)),
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'email' => fake()->unique()->safeEmail(),
            'tel' => '06 98 76 54 32',
            'poste' => 'Auxiliaire de Puériculture (AP)',
            'message' => 'Je souhaite rejoindre votre équipe.',
            'documents' => [],
            'status' => 'nouvelle',
        ];
    }
}
