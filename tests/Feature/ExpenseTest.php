<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_superadmin_can_visit_expense(): void
    {
        $user = User::factory()->superadmin()->create();
        $this->actingAs($user);
        $res = $this->get(route('expenses.index'));
        $res->assertStatus(200);
        $res->assertViewIs('expenses.index');
    }

    public function test_superadmin_can_search_expense(): void
    {
        $user = User::factory()->superadmin()->create();
        $this->actingAs($user);
        $res = $this->get(route('expenses.index', ['search' => 'Test']));
        $res->assertStatus(200);
        $res->assertViewIs('expenses.index');
    }

    public function test_superadmin_can_visit_expense_create(): void
    {
        $user = User::factory()->superadmin()->create();
        $this->actingAs($user);
        $res = $this->get(route('expenses.create'));
        $res->assertStatus(200);
        $res->assertViewIs('expenses.create');
    }

    public function test_superadmin_can_create_expense(): void
    {
        $this->actingAs(User::factory()->superadmin()->create());
        $res = $this->post(route('expenses.store'), [
            'description' => 'Test Expense',
            'category' => 'Test Category',
            'amount' => 100.00,
            'expense_date' => '2023-09-25',
            'payment_method' => 'Test Method',
            'receipt_image_path' => File::fake()->image('test.jpg'),
        ]);
        $res->assertStatus(302);
        $res->assertRedirect(route('expenses.index'));
        $res->assertSessionHas('success');
        $this->assertDatabaseHas('expenses', ['description' => 'Test Expense']);
    }

    public function test_superadmin_cannot_create_expense_with_invalid_data(): void
    {
        $this->actingAs(User::factory()->superadmin()->create());
        $res = $this->post(route('expenses.store'), [
            'description' => '',
            'category' => 'Test Category',
            'amount' => 100.00,
            'expense_date' => '2023-09-25',
            'payment_method' => 'Test Method',
            'receipt_image_path' => File::fake()->image('test.jpg'),
        ], ['referer' => route('expenses.create')]);
        $res->assertStatus(302);
        $res->assertRedirect(route('expenses.create'));
        $res->assertSessionHasErrors('description');
        $this->assertDatabaseMissing('expenses', ['description' => 'Test Expense']);
    }

    public function test_superadmin_can_visit_expense_show(): void
    {
        $user = User::factory()->superadmin()->create();
        $expense = Expense::factory()->forUser($user)->create();
        $this->actingAs($user);
        $res = $this->get(route('expenses.show', $expense));
        $res->assertStatus(200);
        $res->assertViewIs('expenses.show');
    }

    public function test_superadmin_can_visit_expense_edit(): void
    {
        $user = User::factory()->superadmin()->create();
        $expense = Expense::factory()->forUser($user)->create();
        $this->actingAs($user);
        $res = $this->get(route('expenses.edit', $expense));
        $res->assertStatus(200);
        $res->assertViewIs('expenses.edit');
    }

    public function test_superadmin_can_update_expense(): void
    {
        $user = User::factory()->superadmin()->create();
        $expense = Expense::factory()->forUser($user)->create();
        $this->actingAs($user);
        $res = $this->put(route('expenses.update', $expense), [
            'description' => 'Updated Description',
            'category' => 'Updated Category',
            'amount' => 200.00,
            'expense_date' => '2023-09-26',
            'payment_method' => 'Updated Method',
            'receipt_image_path' => File::fake()->image('test.jpg'),
        ]);
        $res->assertStatus(302);
        $res->assertRedirect(route('expenses.index'));
        $res->assertSessionHas('success');
        $this->assertDatabaseHas('expenses', ['description' => 'Updated Description']);
    }

    public function test_superadmin_cannot_update_expense_after_2_days(): void
    {
        $user = User::factory()->superadmin()->create();
        $expense = Expense::factory()->forUser($user)->create(['created_at' => now()->subDays(25)]);
        $this->actingAs($user);
        $res = $this->put(route('expenses.update', $expense), [
            'description' => 'Updated Description',
            'category' => 'Updated Category',
            'amount' => 200.00,
            'expense_date' => '2023-09-26',
            'payment_method' => 'Updated Method',
            'receipt_image_path' => File::fake()->image('test.jpg'),
        ], ['referer' => route('expenses.edit', $expense)]);
        $res->assertStatus(302);
        $res->assertRedirect(route('expenses.edit', $expense));
        $res->assertSessionHas('error');
        $this->assertDatabaseMissing('expenses', ['description' => 'Updated Description']);
    }

    public function test_superadmin_cannot_update_expense_with_invalid_data(): void
    {
        $user = User::factory()->superadmin()->create();
        $expense = Expense::factory()->forUser($user)->create();
        $this->actingAs($user);
        $res = $this->put(route('expenses.update', $expense), [
            'description' => '',
            'category' => 'Updated Category',
            'amount' => 200.00,
            'expense_date' => '2023-09-26',
            'payment_method' => 'Updated Method',
            'receipt_image_path' => File::fake()->image('test.jpg'),
        ], ['referer' => route('expenses.edit', $expense)]);
        $res->assertStatus(302);
        $res->assertRedirect(route('expenses.edit', $expense));
        $res->assertSessionHasErrors('description');
        $this->assertDatabaseMissing('expenses', ['description' => 'Updated Description']);
    }

    public function test_superadmin_can_delete_expense(): void
    {
        $user = User::factory()->superadmin()->create();
        $expense = Expense::factory()->forUser($user)->create();
        $this->actingAs($user);
        $res = $this->delete(route('expenses.destroy', $expense));
        $res->assertStatus(302);
        $res->assertRedirect(route('expenses.index'));
        $res->assertSessionHas('success');
        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
    }

    public function test_superadmin_cannot_delete_expense_after_24_hours(): void
    {
        $user = User::factory()->superadmin()->create();
        $expense = Expense::factory()->forUser($user)->create(['created_at' => now()->subDays(25)]);
        $this->actingAs($user);
        $res = $this->delete(route('expenses.destroy', $expense));
        $res->assertStatus(302);
        $res->assertRedirect(route('expenses.index'));
        $res->assertSessionHas('error');
        $this->assertDatabaseHas('expenses', ['id' => $expense->id]);
    }
}
