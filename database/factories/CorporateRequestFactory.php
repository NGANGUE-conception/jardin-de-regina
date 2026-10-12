<?php

namespace Database\Factories;

use App\Models\CorporateRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Demandes de berceaux entreprise fictives, pour les tests automatiques.
 *
 * @extends Factory<CorporateRequest>
 */
class CorporateRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'request_number' => sprintf('#JR-ENT-%04d', fake()->unique()->numberBetween(1, 9999)),
            'company_name' => fake()->company(),
            'sector' => 'Conseil',
            'employees_count' => '20 à 50 salariés',
            'berceaux_count' => '2 à 3 berceaux',
            'contact_name' => fake()->name(),
            'contact_role' => 'DRH',
            'contact_email' => fake()->unique()->safeEmail(),
            'contact_tel' => '01 43 00 00 00',
            'status' => 'nouvelle',
        ];
    }
}
