<?php

namespace Tests\Unit;

use App\Models\Designer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DesignerControllerTest extends TestCase
{
    use RefreshDatabase;

    private function authenticateUser()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        return $user;
    }

    public function test_index_displays_active_designers()
    {
        $this->authenticateUser();

        // Create active and inactive designers
        $activeDesigner = Designer::factory()->create(['active' => true]);
        $inactiveDesigner = Designer::factory()->create(['active' => false]);

        // Test without 'show_all' parameter (only active designers should be shown)
        $response = $this->get(route('designers.index'));
        $response->assertStatus(200);
        $response->assertViewHas('designers', function ($designers) use ($activeDesigner) {
            return $designers->contains($activeDesigner) && $designers->count() === 1;
        });

        // Test with 'show_all' parameter (both active and inactive designers should be shown)
        $response = $this->get(route('designers.index', ['show_all' => true]));
        $response->assertStatus(200);
        $response->assertViewHas('designers', function ($designers) use ($activeDesigner, $inactiveDesigner) {
            return $designers->contains($activeDesigner) && $designers->contains($inactiveDesigner);
        });
    }

    public function test_create_displays_create_form()
    {
        $this->authenticateUser();

        $response = $this->get(route('designers.create'));

        $response->assertStatus(200);
        $response->assertViewIs('designers.create');
    }

    public function test_store_creates_designer_and_user()
    {
        $this->authenticateUser();

        $data = [
            'name' => 'Test Designer',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'active' => '1',
            'notes' => 'Test notes',
        ];

        $response = $this->post(route('designers.store'), $data);

        $response->assertRedirect(route('designers.index'));
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
        $this->assertDatabaseHas('designers', ['name' => 'Test Designer']);
    }

    public function test_show_displays_designer_details()
    {
        $this->authenticateUser();

        $designer = Designer::factory()->create();

        $response = $this->get(route('designers.show', $designer));

        $response->assertStatus(200);
        $response->assertViewIs('designers.show');
        $response->assertViewHas('designer', $designer);
    }

    public function test_edit_displays_edit_form()
    {
        $this->authenticateUser();

        $designer = Designer::factory()->create();

        $response = $this->get(route('designers.edit', $designer));

        $response->assertStatus(200);
        $response->assertViewIs('designers.edit');
        $response->assertViewHas('designer', $designer);
    }

    public function test_update_updates_designer_and_user()
    {
        $this->authenticateUser();

        $designer = Designer::factory()->create();
        $data = [
            'name' => 'Updated Designer',
            'email' => 'updated@example.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
            'active' => '0',
            'notes' => 'Updated notes',
        ];

        $response = $this->put(route('designers.update', $designer), $data);

        $response->assertRedirect(route('designers.index'));
        $this->assertDatabaseHas('users', ['email' => 'updated@example.com']);
        $this->assertDatabaseHas('designers', ['name' => 'Updated Designer']);
    }

    public function test_destroy_deletes_designer_and_user()
    {
        $this->authenticateUser();

        $designer = Designer::factory()->create();

        $response = $this->delete(route('designers.destroy', $designer));

        $response->assertRedirect(route('designers.index'));
        $this->assertDatabaseMissing('designers', ['id' => $designer->id]);
    }
}