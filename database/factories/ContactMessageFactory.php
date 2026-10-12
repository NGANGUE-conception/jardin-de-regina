<?php

namespace Database\Factories;

use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Messages de contact fictifs, pour les tests automatiques.
 *
 * @extends Factory<ContactMessage>
 */
class ContactMessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'message_number' => sprintf('#JR-MSG-%04d', fake()->unique()->numberBetween(1, 9999)),
            'fullname' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '06 11 22 33 44',
            'subject' => 'Demander une visite privée',
            'message' => 'Bonjour, nous aimerions visiter la crèche.',
            'status' => 'non_lu',
        ];
    }
}
