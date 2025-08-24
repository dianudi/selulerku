<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountSettingTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    public function test_user_can_visit_account_setting(): void
    {
        $this->actingAs(User::factory()->create());
        $response = $this->get(route('account.index'));
        $response->assertStatus(200);
        $response->assertViewIs('account.index');
    }

    public function test_user_can_update_account_setting(): void
    {
        $this->actingAs(User::factory()->create());
        $email = $this->faker->email();
        $response = $this->patch(route('account.update'), [
            'name' => $this->faker->name(),
            'email' => $email,
        ], ['referer' => route('account.index')]);
        $response->assertStatus(302);
        $response->assertRedirect(route('account.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);
    }

    public function test_user_cannot_update_account_setting_with_invalid_data(): void
    {
        $this->actingAs(User::factory()->create());
        $response = $this->patch(route('account.update'), [
            'name' => $this->faker->name(),
            'email' => 'invalid-email',
        ], ['referer' => route('account.index')]);
        $response->assertStatus(302);
        $response->assertRedirect(route('account.index'));
        $response->assertSessionHasErrors('email');
    }

    public function test_user_can_update_password(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->patch(route('account.password'), [
            'current_password' => 'password',
            'password' => 'updatedPassword',
            'password_confirmation' => 'updatedPassword',
        ], ['referer' => route('account.index')]);
        $response->assertStatus(302);
        $response->assertRedirect(route('account.index'));
        $response->assertSessionHas('success');
        $this->assertTrue(Hash::check('updatedPassword', $user->refresh()->password));
    }

    public function test_user_cannot_update_password_with_invalid_data(): void
    {
        $this->actingAs(User::factory()->create());
        $response = $this->patch(route('account.password'), [
            'current_password' => 'invalidPassword',
            'password' => 'updatedPassword',
            'password_confirmation' => 'updatedPassword',
        ], ['referer' => route('account.index')]);
        $response->assertStatus(302);
        $response->assertRedirect(route('account.index'));
        $response->assertSessionHasErrors('current_password');
    }
}
