<?php

namespace Database\Factories;

use App\Models\ServiceHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceDetail>
 */
class ServiceDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'service_history_id' => ServiceHistory::factory()->create()->id,
            'kind' => $this->faker->randomElement(['maintenance', 'repair']),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->numberBetween(1000, 10000),
            'image' => $this->faker->imageUrl(),
        ];
    }

    public function forServiceHistory(ServiceHistory $serviceHistory)
    {
        return $this->state(function (array $attributes) use ($serviceHistory) {
            return [
                'service_history_id' => $serviceHistory->id,
            ];
        });
    }
}
