<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;
    public function test_superadmin_can_visit_settings_page()
    {
        $this->actingAs(User::factory()->superadmin()->create());
        $res = $this->get(route('settings.index'));
        $res->assertStatus(200);
        $res->assertViewIs('settings.index');
    }

    public function test_superadmin_can_update_settings()
    {
        $this->actingAs(User::factory()->superadmin()->create());
        $res = $this->post(route('settings.update'), [
            'key' => 'test',
        ]);
        $res->assertStatus(302);
        $res->assertRedirect(route('settings.index'));
        $res->assertSessionHas('success', 'Settings updated successfully.');
        $this->assertDatabaseHas('settings', [
            'key' => 'key',
            'value' => 'test',
        ]);
    }

    public function test_not_superadmin_cannot_visit_settings_page()
    {
        $this->actingAs(User::factory()->create());
        $res = $this->get(route('settings.index'));
        $res->assertStatus(403);
    }

    public function test_not_superadmin_cannot_update_settings()
    {
        $this->actingAs(User::factory()->create());
        $res = $this->post(route('settings.update'), [
            'key' => 'test',
        ]);
        $res->assertStatus(403);
        $this->assertDatabaseMissing('settings', [
            'key' => 'key',
            'value' => 'test',
        ]);
    }
}
