<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function testIndex()
    {
        $response = $this->get(route('users.index'));
        $response->assertStatus(200);
        $response->assertViewIs('users.index');
    }

    public function testShow()
    {
        $user = User::factory()->create();
        $response = $this->get(route('users.show', $user->id));
        $response->assertViewHas('user', $user);
    }

    public function testCreate()
    {
        $response = $this->get(route('users.create'));
        $response->assertStatus(200);
        $response->assertViewIs('users.create');
    }

    public function testStore()
    {
        $userData = User::factory()->make()->toArray();
        $userData['password'] = 'password';
        $userData['password_confirmation'] = 'password';
        $userData['role'] = 'customer'; // or any default role

        $response = $this->post(route('users.store'), $userData);
        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['email' => $userData['email']]);
    }

    public function testEdit()
    {
        $user = User::factory()->create();
        $response = $this->get(route('users.edit', $user->id));
        $response->assertStatus(200);
        $response->assertViewIs('users.edit');
        $response->assertViewHas('user', $user);
    }

    public function testUpdate()
    {
        $user = User::factory()->create();
        $updatedData = User::factory()->make()->toArray();
        $updatedData['password'] = 'newpassword';
        $updatedData['password_confirmation'] = 'newpassword';
        $updatedData['role'] = 'customer'; // or any default role

        $response = $this->put(route('users.update', $user->id), $updatedData);
        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['email' => $updatedData['email']]);
    }

    public function testDestroy()
    {
        $user = User::factory()->create();
        $response = $this->delete(route('users.destroy', $user->id));
        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}