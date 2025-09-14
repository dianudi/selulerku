<?php

namespace Tests\Feature;

use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Tests\TestCase;

class ProductCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_or_admin_can_visit_product_category_page()
    {
        $this->actingAs(User::factory()->superadmin()->create());
        $response = $this->get(route('productcategories.index'));
        $response->assertStatus(200);
        $response->assertViewIs('productCategories.index');
    }

    public function test_superadmin_or_admin_can_store_product_category()
    {
        $this->actingAs(User::factory()->superadmin()->create());
        $response = $this->post(route('productcategories.store'), ['name' => 'test', 'icon' => File::fake()->image('test.jpg')], ['accept' => 'text/html']);
        $response->assertStatus(302);
        $response->assertRedirect(route('productcategories.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('product_categories', ['name' => 'test']);
        // json ajax
        $response = $this->post(route('productcategories.store'), ['name' => 'test2', 'icon' => File::fake()->image('test.jpg')], ['accept' => 'application/json']);
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Product category created successfully.']);
        $this->assertDatabaseHas('product_categories', ['name' => 'test2']);
    }

    public function test_superadmin_or_admin_cannot_store_product_category_with_invalid_data()
    {
        $this->actingAs(User::factory()->superadmin()->create());
        $response = $this->post(route('productcategories.store'), ['name' => ''], ['referer' => route('productcategories.index')]);
        $response->assertStatus(302);
        $response->assertRedirect(route('productcategories.index'));
        $response->assertSessionHasErrors(['name']);
    }

    public function test_superadmin_or_admin_can_update_product_category()
    {
        $this->actingAs(User::factory()->superadmin()->create());
        $productCategory = ProductCategory::factory()->create();
        $response = $this->put(route('productcategories.update', $productCategory), ['name' => 'updated'], ['accept' => 'text/html']);
        $response->assertStatus(302);
        $response->assertRedirect(route('productcategories.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('product_categories', ['name' => 'updated']);
        // json ajax
        $response = $this->put(route('productcategories.update', $productCategory), ['name' => 'updated2', 'icon' => File::fake()->image('test.jpg')], ['accept' => 'application/json']);
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Product category updated successfully.']);
        $this->assertDatabaseHas('product_categories', ['name' => 'updated2']);
    }

    public function test_superadmin_or_admin_cannot_update_product_category_with_invalid_data()
    {
        $this->actingAs(User::factory()->superadmin()->create());
        $productCategory = ProductCategory::factory()->create();
        $response = $this->put(route('productcategories.update', $productCategory), ['name' => ''], ['referer' => route('productcategories.index')]);
        $response->assertStatus(302);
        $response->assertRedirect(route('productcategories.index'));
        $response->assertSessionHasErrors(['name']);
    }

    public function test_superadmin_or_admin_can_delete_product_category()
    {
        $this->actingAs(User::factory()->superadmin()->create());
        $productCategory = ProductCategory::factory()->create();
        $response = $this->delete(route('productcategories.destroy', $productCategory->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('productcategories.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('product_categories', ['id' => $productCategory->id]);
    }

    public function test_superadmin_or_admin_cannot_delete_product_category_with_products()
    {
        $this->actingAs(User::factory()->superadmin()->create());
        $productCategory = ProductCategory::factory()->hasProducts(1)->create();
        $response = $this->delete(route('productcategories.destroy', $productCategory->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('productcategories.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('product_categories', ['id' => $productCategory->id]);
    }
}
