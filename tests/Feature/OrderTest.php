<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_superadmin_can_visit_orders_page()
    {
        $this->actingAs(User::factory()->superadmin()->create());
        $res = $this->get(route('orders.index'));
        $res->assertStatus(200);
        $res->assertViewIs('orders.index');
    }

    public function test_admin_can_visit_orders_page()
    {
        $this->actingAs(User::factory()->create());
        $res = $this->get(route('orders.index'));
        $res->assertStatus(200);
        $res->assertViewIs('orders.index');
    }

    public function test_admin_can_visit_create_order_page()
    {
        $this->actingAs(User::factory()->create());
        $res = $this->get(route('orders.create'));
        $res->assertStatus(200);
        $res->assertViewIs('orders.create');
    }

    public function test_admin_can_store_order()
    {
        $this->actingAs(User::factory()->create());
        $res = $this->post(route('orders.store'), [
            'customer_id' => Customer::factory()->create()->id,
            'status' => 'paid',
            'details' => (function () {
                $detail = collect([]);
                for ($i = 0; $i < 5; $i++) {
                    $detail->push([
                        'product_id' => Product::factory()->create()->id,
                        'quantity' => random_int(1, 10),
                    ]);
                }
                return $detail->toArray();
            })(),

        ], ['referer' => route('orders.create')]);
        $res->assertRedirect(route('orders.index'));
        $res->assertSessionHas('success');
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_details', 5);
    }

    public function test_admin_cannot_store_order_with_invalid_data()
    {
        $this->actingAs(User::factory()->create());
        $res = $this->post(route('orders.store'), [
            'customer_id' => '',
            'status' => 'paid',
            'order_details' => [],
        ], ['referer' => route('orders.create')]);
        $res->assertRedirect(route('orders.create'));
        $res->assertSessionHasErrors('customer_id');
    }

    public function test_admin_can_visit_show_order_page()
    {
        $order = OrderDetail::factory()->create();
        $this->actingAs(User::factory()->create());
        $res = $this->get(route('orders.show', $order));
        $res->assertStatus(200);
        $res->assertViewIs('orders.show');
    }

    public function test_admin_can_visit_edit_order_page()
    {
        $order = OrderDetail::factory()->create();
        $this->actingAs(User::factory()->create());
        $res = $this->get(route('orders.edit', $order));
        $res->assertStatus(200);
        $res->assertViewIs('orders.edit');
    }

    public function test_admin_can_update_order()
    {
        $order = Order::factory()->create();
        $order->details()->create([
            'product_id' => Product::factory()->create()->id,
            'quantity' => random_int(1, 100),
            'immutable_price' => Product::select('price')->where('id', Product::factory()->create()->id)->first()->price
        ]);
        $this->actingAs(User::factory()->create());
        $res = $this->put(route('orders.update', $order), [
            'customer_id' => $order->customer_id,
            'status' => 'unpaid',
            'details' => (function () {
                $detail = collect([]);
                for ($i = 0; $i < 10; $i++) {
                    $detail->push([
                        'product_id' => Product::factory()->create()->id,
                        'quantity' => random_int(1, 100),
                    ]);
                }
                return $detail->toArray();
            })()
        ], ['referer' => route('orders.edit', $order)]);
        $res->assertRedirect(route('orders.index'));
        $res->assertSessionHas('success');
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseHas('orders', ['status' => 'unpaid']);
        $this->assertDatabaseCount('order_details', 10);
    }

    public function test_admin_cannot_update_order_with_invalid_data()
    {
        $order = OrderDetail::factory()->create();
        $this->actingAs(User::factory()->create());
        $res = $this->put(route('orders.update', $order), [
            'customer_id' => '',
            'status' => 'paid',
            'order_details' => [],
        ], ['referer' => route('orders.edit', $order)]);
        $res->assertRedirect(route('orders.edit', $order));
        $res->assertSessionHasErrors('customer_id');
    }

    public function test_admin_can_delete_order()
    {
        $order = Order::factory()->createdAfter(now()->subDays(-1))->create();
        $order->details()->create([
            'product_id' => Product::factory()->create()->id,
            'quantity' => random_int(1, 100),
            'immutable_price' => Product::select('price')->where('id', Product::factory()->create()->id)->first()->price
        ]);
        $this->actingAs(User::factory()->create());
        $res = $this->delete(route('orders.destroy', $order));
        $res->assertRedirect(route('orders.index'));
        $res->assertSessionHas('success');
        $this->assertDatabaseCount('orders', 0);
    }
}
