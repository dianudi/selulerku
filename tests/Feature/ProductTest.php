<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Testing\File;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_visit_products_page()
    {
        $this->actingAs(User::factory()->create());
        $response = $this->get(route('products.index'));
        $response->assertStatus(200);
        $response->assertViewIs('products.index');
    }

    public function test_user_can_search_products()
    {
        Product::factory(10)->create();
        $this->actingAs(User::factory()->create());
        $response = $this->get(route('products.index', ['search' => 'a']));
        $response->assertStatus(200);
        $response->assertViewIs('products.index');
    }

    public function test_user_can_visit_create_product_page()
    {
        $this->actingAs(User::factory()->create());
        $response = $this->get(route('products.create'));
        $response->assertStatus(200);
        $response->assertViewIs('products.create');
    }

    public function test_user_can_store_product()
    {
        $this->actingAs(User::factory()->create());
        $response = $this->post(route('products.store'), [
            'name' => 'test',
            'description' => 'test',
            'sku' => fake()->unique()->ean13(),
            'quantity' => 10,
            'price' => 100,
            'image' => File::fake()->image('test.jpg'),
            'product_category_id' => ProductCategory::factory()->create()->id,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('products', ['name' => 'test']);
    }

    public function test_user_cannot_store_product_with_invalid_data()
    {
        $this->actingAs(User::factory()->create());
        $response = $this->post(route('products.store'), ['name' => ''], ['referer' => route('products.index')]);
        $response->assertStatus(302);
        $response->assertRedirect(route('products.index'));
        $response->assertSessionHasErrors(['name']);
    }

    public function test_user_can_visit_show_product_page()
    {
        $product = Product::factory()->create();
        $this->actingAs(User::factory()->create());
        $response = $this->get(route('products.show', $product));
        $response->assertStatus(200);
        $response->assertViewIs('products.show');
    }

    public function test_user_can_visit_edit_product_page()
    {
        $product = Product::factory()->create();
        $this->actingAs(User::factory()->create());
        $response = $this->get(route('products.edit', $product));
        $response->assertStatus(200);
        $response->assertViewIs('products.edit');
    }

    public function test_user_can_update_product()
    {
        $product = Product::factory()->create();
        $this->actingAs(User::factory()->create());
        $response = $this->put(route('products.update', $product), [
            'name' => 'updated',
            'description' => 'updated',
            'sku' => fake()->unique()->ean13(),
            'quantity' => 10,
            'price' => 100,
            'image' => File::fake()->image('test.jpg'),
            'product_category_id' => ProductCategory::factory()->create()->id,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('products', ['name' => 'updated']);
    }

    public function test_user_cannot_update_product_with_invalid_data()
    {
        $product = Product::factory()->create();
        $this->actingAs(User::factory()->create());
        $response = $this->put(route('products.update', $product), ['name' => ''], ['referer' => route('products.index')]);
        $response->assertStatus(302);
        $response->assertRedirect(route('products.index'));
        $response->assertSessionHasErrors(['name']);
    }

    public function test_user_can_delete_product()
    {
        $product = Product::factory()->create();
        $this->actingAs(User::factory()->create());
        $response = $this->delete(route('products.destroy', $product->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
