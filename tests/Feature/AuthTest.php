<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_visit_login()
    {
        $res = $this->get('/login');
        $res->assertStatus(200);
        $res->assertViewIs('auth.login');
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create();
        $res = $this->post(route('auth.auth'), [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $res->assertRedirect('/dashboard');
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $res = $this->post(route('auth.auth'), [
            'email' => 'xgX2o@example.com',
            'password' => 'password',
        ], ['referer' => route('auth.login')]);
        $res->assertRedirect(route('auth.login'));
        $res->assertSessionHasErrors('email');
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $res = $this->delete(route('auth.logout'));
        $res->assertRedirect(route('auth.login'));
    }
}
