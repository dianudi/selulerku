<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceHistory>
 */
class ServiceHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create()->id,
            'customer_id' => Customer::factory()->create()->id,
            'invoice_number' => $this->faker->unique()->ean13(),
            'warranty_expired_at' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
            'total_revision' => $this->faker->numberBetween(1, 10),
            'status' => $this->faker->randomElement(['pending', 'on_process', 'done']),
        ];
    }
}
