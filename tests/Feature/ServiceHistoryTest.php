<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\ServiceDetail;
use App\Models\ServiceHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServiceHistoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_user_can_visit_service_histories_page()
    {
        $this->actingAs(User::factory()->create());
        $serviceHistory = ServiceHistory::factory()->create();
        ServiceDetail::factory(5)->forServiceHistory($serviceHistory)->create();
        $response = $this->get(route('servicehistories.index'));
        $response->assertStatus(200);
        $response->assertViewIs('serviceHistories.index');
    }

    public function test_user_can_visit_create_service_history_page()
    {
        $this->actingAs(User::factory()->create());
        $response = $this->get(route('servicehistories.create'));
        $response->assertStatus(200);
        $response->assertViewIs('serviceHistories.create');
    }

    public function test_user_can_store_service_history()
    {
        $this->actingAs(User::factory()->create());
        $customerId = Customer::factory()->create()->id;
        $response = $this->post(route('servicehistories.store'), [
            'customer_id' => $customerId,
            'warranty_expired_at' => $this->faker->date(),
            'status' => $this->faker->randomElement(['pending', 'done']),
            'details' => [
                [
                    'kind' => $this->faker->randomElement(['maintenance', 'repair']),
                    'description' => $this->faker->sentence(),
                    'price' => $this->faker->numberBetween(1000, 10000),
                ]
            ]
        ], ['accept' => 'application/json']);
        $response->assertJson(['message' => 'Service History created successfully.']);
        // $response->assertRedirect(route('servicehistories.index'));
        $this->assertDatabaseHas('service_histories', [
            'customer_id' => $customerId
        ]);
    }

    public function test_user_cannot_store_service_with_invalid_data()
    {
        $this->actingAs(User::factory()->create());
        $response = $this->post(route('servicehistories.store'), [
            'customer_id' => Customer::factory()->create()->id,
            'invoice_number' => $this->faker->randomNumber(),
            'warranty_expired_at' => $this->faker->date(),
            'status' => $this->faker->randomElement(['pending', 'done']),
            'details' => [
                [
                    'kind' => $this->faker->randomElement(['maintenance', 'repair']),
                    'description' => $this->faker->sentence(),
                    'price' => $this->faker->numberBetween(1000, 10000),
                ]
            ]
        ], ['referer' => route('servicehistories.create')]);
        // $response->assertRedirect(route('servicehistories.create'));
        $this->assertDatabaseMissing('service_histories', [
            'invoice_number' => $this->faker->randomNumber()
        ]);
    }

    public function test_user_can_visit_show_service_history_page()
    {
        $this->actingAs(User::factory()->create());
        $serviceHistory = ServiceHistory::factory()->create();
        ServiceDetail::factory(5)->forServiceHistory($serviceHistory)->create();
        $response = $this->get(route('servicehistories.show', $serviceHistory));
        $response->assertStatus(200);
        $response->assertViewIs('serviceHistories.show');
    }

    public function test_user_can_visit_edit_service_history_page()
    {
        $this->actingAs(User::factory()->create());
        $serviceHistory = ServiceHistory::factory()->create();
        ServiceDetail::factory(5)->forServiceHistory($serviceHistory)->create();
        $response = $this->get(route('servicehistories.edit', $serviceHistory));
        $response->assertStatus(200);
        $response->assertViewIs('serviceHistories.edit');
    }

    public function test_user_can_update_service_history()
    {
        $this->actingAs(User::factory()->create());
        $serviceHistory = ServiceHistory::factory()->create();
        ServiceDetail::factory(5)->forServiceHistory($serviceHistory)->create();
        $totalRevision = $this->faker->randomNumber();
        $response = $this->put(route('servicehistories.update', $serviceHistory), [
            'total_revision' => $totalRevision,
            'status' => $this->faker->randomElement(['pending', 'done']),
            'details' => [
                [
                    'kind' => $this->faker->randomElement(['maintenance', 'repair']),
                    'description' => $this->faker->sentence(),
                    'price' => $this->faker->numberBetween(1000, 10000),
                ]
            ]
        ], ['referer' => route('servicehistories.edit', $serviceHistory->id), 'accept' => 'application/json']);
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Service History updated successfully.']);
        $this->assertDatabaseHas('service_histories', [
            'total_revision' => $totalRevision,
        ]);
    }

    public function test_user_cannot_update_service_history_with_invalid_data()
    {
        $this->actingAs(User::factory()->create());
        $serviceHistory = ServiceHistory::factory()->create();
        $response = $this->put(route('servicehistories.update', $serviceHistory), [
            'total_revision' => $this->faker->randomNumber(),
            'status' => $this->faker->randomElement(['pending', 'done']),
            'details' => [
                [
                    'kind' => $this->faker->randomElement(['maintenance', 'repair']),
                    'description' => $this->faker->sentence(),
                    'price' => $this->faker->numberBetween(1000, 10000),
                ]
            ]
        ], ['referer' => route('servicehistories.edit', $serviceHistory->id), 'accept' => 'application/json']);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('service_histories', [
            'total_revision' => $this->faker->randomNumber(),
        ]);
    }

    public function test_user_can_delete_service_history()
    {
        $this->actingAs(User::factory()->create());
        $serviceHistory = ServiceHistory::factory()->create();
        $response = $this->delete(route('servicehistories.destroy', $serviceHistory));
        $response->assertRedirect(route('servicehistories.index'));
        $this->assertDatabaseMissing('service_histories', [
            'id' => $serviceHistory->id,
        ]);
        $this->assertDatabaseMissing('service_details', [
            'service_history_id' => $serviceHistory->id
        ]);
    }

    public function test_user_can_print_receipt()
    {
        $this->actingAs(User::factory()->create());
        $serviceHistory = ServiceHistory::factory()->create();
        ServiceDetail::factory(5)->forServiceHistory($serviceHistory)->create();
        $response = $this->get(route('servicehistories.print', $serviceHistory));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
