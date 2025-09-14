<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_visit_customers_page()
    {
        $this->actingAs(User::factory()->create());
        $res = $this->get(route('customers.index'));
        $res->assertStatus(200);
        $res->assertViewIs('customers.index');
    }

    public function test_user_can_search_customers()
    {
        Customer::factory(10)->create();
        $this->actingAs(User::factory()->create());
        $res = $this->get(route('customers.index', ['search' => 'a']));
        $res->assertStatus(200);
        $res->assertViewIs('customers.index');
    }

    public function test_user_can_store_customer()
    {
        $this->actingAs(User::factory()->create());
        $res = $this->post(route('customers.store'), [
            'name' => 'John Doe',
            'phone_number' => '1234567890',
            'address' => '123 Main St',
        ], ['accept' => 'application/json']);
        $res->assertStatus(200);
        $res->assertJsonStructure(['message', 'customer']);
        $this->assertDatabaseHas('customers', [
            'name' => 'John Doe',
        ]);
    }

    public function test_user_can_visit_customer_detail_page()
    {
        $customer = Customer::factory()->create();
        $this->actingAs(User::factory()->create());
        $res = $this->get(route('customers.show', $customer));
        $res->assertStatus(200);
        $res->assertViewIs('customers.show');
    }

    public function test_user_can_visit_customer_edit_page()
    {
        $customer = Customer::factory()->create();
        $this->actingAs(User::factory()->create());
        $res = $this->get(route('customers.edit', $customer));
        $res->assertStatus(200);
        $res->assertViewIs('customers.edit');
    }

    public function test_user_can_update_customer()
    {
        $customer = Customer::factory()->create();
        $this->actingAs(User::factory()->create());
        $res = $this->put(route('customers.update', $customer), [
            'name' => 'John Doe',
            'phone_number' => '1234567890',
            'address' => '123 Main St',
        ]);
        $res->assertRedirect(route('customers.index'));
        $customer->refresh();
        $this->assertEquals('John Doe', $customer->name);
        $this->assertEquals('123 Main St', $customer->address);
    }

    public function test_user_cannot_update_customer_with_invalid_data()
    {
        $customer = Customer::factory()->create();
        $this->actingAs(User::factory()->create());
        $res = $this->put(route('customers.update', $customer), [
            'name' => '',
            'phone' => '1234567890',
            'address' => '123 Main St',
        ], ['referer' => route('customers.edit', $customer)]);
        $res->assertRedirect(route('customers.edit', $customer));
        $res->assertSessionHasErrors('name');
    }

    public function test_user_can_delete_customer()
    {
        $customer = Customer::factory()->create();
        $this->actingAs(User::factory()->create());
        $res = $this->delete(route('customers.destroy', $customer));
        $res->assertRedirect(route('customers.index'));
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }
}
