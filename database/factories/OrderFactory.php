<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->superadmin()->create()->id,
            'customer_id' => Customer::factory()->create()->id,
            'invoice_number' => fake()->ean13(),
            'status' => 'paid',

        ];
    }

    public function forCustomer(Customer $customer)
    {
        return $this->state(function (array $attributes) use ($customer) {
            return [
                'customer_id' => $customer->id,
            ];
        });
    }

    public function forUser(User $user)
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id,
            ];
        });
    }

    public function createdAfter($created_at)
    {
        return $this->state(function (array $attributes) use ($created_at) {
            return [
                'created_at' => $created_at,
            ];
        });
    }
}
