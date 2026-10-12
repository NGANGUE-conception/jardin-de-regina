<?php

namespace Database\Factories;

use App\Models\PreRegistration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Dossiers de pré-inscription fictifs, pour les tests automatiques.
 *
 * @extends Factory<PreRegistration>
 */
class PreRegistrationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'dossier_number' => sprintf('#JR-%d-%04d', now()->year, fake()->unique()->numberBetween(1, 9999)),
            'child_last_name' => fake()->lastName(),
            'child_first_name' => fake()->firstName(),
            'child_gender' => fake()->randomElement(['Fille', 'Garçon']),
            'child_birthdate' => fake()->dateTimeBetween('-2 years', '-3 months')->format('Y-m-d'),
            'entry_date' => fake()->dateTimeBetween('+1 month', '+8 months')->format('Y-m-d'),
            'care_type' => 'regulier',
            'care_days' => ['lundi', 'mardi', 'jeudi'],
            'special_needs' => false,
            'p1_civilite' => 'Madame',
            'p1_nom' => fake()->lastName(),
            'p1_prenom' => fake()->firstName(),
            'p1_email' => fake()->unique()->safeEmail(),
            'p1_tel' => '06 12 34 56 78',
            'p1_cp' => '94140',
            'p1_ville' => 'Alfortville',
            'rgpd_consent' => true,
            'status' => 'nouvelle',
        ];
    }
}
