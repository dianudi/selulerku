<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_access_user_management()
    {
        $user = User::factory()->superadmin()->create();
        $this->actingAs($user);
        $res = $this->get(route('users.index'));
        $res->assertStatus(200);
        $res->assertViewIs('users.index');
    }

    public function test_superadmin_can_search_users()
    {
        User::factory(10)->create();
        $user = User::factory()->superadmin()->create();
        $this->actingAs($user);
        $res = $this->get(route('users.index', ['search' => 'a']));
        $res->assertStatus(200);
        $res->assertViewIs('users.index');
    }

    public function test_superadmin_can_visit_create_user_page()
    {
        $user = User::factory()->superadmin()->create();
        $this->actingAs($user);
        $res = $this->get(route('users.create'));
        $res->assertStatus(200);
        $res->assertViewIs('users.create');
    }

    public function test_superadmin_can_create_user()
    {
        $user = User::factory()->superadmin()->create();
        $this->actingAs($user);
        $res = $this->post(route('users.store'), [
            'name' => 'John Doe',
            'email' => 'xgX2o@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin',
        ], ['referer' => route('users.create')]);
        $res->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'xgX2o@example.com',
        ]);
    }

    public function test_superadmin_cannot_create_user_with_invalid_data()
    {
        $user = User::factory()->superadmin()->create();
        $this->actingAs($user);
        $res = $this->post(route('users.store'), [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'password',
            'password_confirmation' => 'password',
        ], ['referer' => route('users.create')]);
        $res->assertRedirect(route('users.create'));
        $res->assertSessionHasErrors('email');
    }

    public function test_superadmin_can_delete_user()
    {
        $user = User::factory()->superadmin()->create();
        $this->actingAs($user);
        $user = User::factory()->create();
        $res = $this->delete(route('users.destroy', $user));
        $res->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_not_superadmin_cannot_access_user_management()
    {
        $user = User::factory()->cashier()->create();
        $this->actingAs($user);
        $res = $this->get(route('users.index'));
        $res->assertStatus(403);
    }

    public function test_superadmin_can_deactivate_user()
    {
        $admin = User::factory()->superadmin()->create();
        $this->actingAs($admin);
        $user = User::factory()->create();
        $res = $this->patch(route('users.activate', $user));
        $res->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'active' => false,
        ]);
    }

    public function test_admin_cannot_delete_user_if_has_orders()
    {
        $admin = User::factory()->superadmin()->create();
        $this->actingAs($admin);
        $customer = Customer::factory()->create(['user_id' => $admin->id]);
        $cashier = User::factory()->cashier()->create();
        $order = Order::factory()->forUser($cashier)->forCustomer($customer)->createdAfter(now()->subDays(-1))->create();
        $res = $this->delete(route('users.destroy', $cashier), [
            'referer' => route('users.index'),
        ]);
        $res->assertRedirect(route('users.index'));
        $res->assertSessionHas('error');
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
        ]);
    }
}
