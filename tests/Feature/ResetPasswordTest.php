<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Mockery\Matcher\Not;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_visit_reset_password_page()
    {
        $response = $this->get(route('password.request'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.forgot-password');
    }

    public function test_user_can_reset_password()
    {
        Notification::fake();
        $user = User::factory()->create();
        $response = $this->post(route('password.email'), [
            'email' => $user->email,
        ]);
        $response->assertStatus(302);
        Notification::assertSentTo($user, ResetPassword::class);
        $response->assertSessionHas('status');
    }

    public function test_user_cannot_reset_password_with_invalid_email()
    {
        $response = $this->post(route('password.email'), [
            'email' => 'xgX2o@example.com',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    public function test_user_can_visit_reset_password_page_with_token()
    {
        Notification::fake();
        $user = User::factory()->create();
        $this->post(route('password.email'), ['email' => $user->email,]);
        Notification::assertSentTo($user, ResetPassword::class, function ($notification, $channels) use ($user) {
            $res = $this->get(route('password.reset', ['token' => $notification->token]));
            $res->assertStatus(200);
            $res->assertViewIs('auth.reset-password');
            return true;
        });
    }

    public function test_user_can_update_password()
    {
        Notification::fake();
        $user = User::factory()->create();
        $this->post(route('password.email'), ['email' => $user->email,]);
        Notification::assertSentTo($user, ResetPassword::class, function ($notification, $channels) use ($user) {
            $res = $this->get(route('password.reset', ['token' => $notification->token]));
            $res->assertStatus(200);
            $response = $this->post(route('password.update'), [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'updatedPassword',
                'password_confirmation' => 'updatedPassword',
            ]);
            $response->assertStatus(302);
            $response->assertRedirect(route('auth.login'));
            $response->assertSessionHas('status');
            return true;
        });
        $this->assertTrue(Hash::check('updatedPassword', $user->refresh()->password));
    }

    public function test_user_cannot_update_password_with_invalid_token()
    {
        $response = $this->post(route('password.update'), [
            'token' => 'invalidToken',
            'email' => 'xgX2o@example.com',
            'password' => 'updatedPassword',
            'password_confirmation' => 'updatedPassword',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }
}
