<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_customers()
    {
        // Create a user with the role 'admin'
        $admin = User::factory()->create(['role' => 'admin']);

        // Authenticate the admin user
        $this->actingAs($admin);

        // Create customers associated with a different user
        $customerUser = User::factory()->create(['role' => 'customer']);
        Customer::factory()->count(3)->create(['user_id' => $customerUser->id]);

        $response = $this->get(route('customers.index'));

        $response->assertStatus(200);
        $response->assertViewIs('customers.index');
        $response->assertViewHas('customers');
    }

    public function test_show_displays_customer_details()
    {
        // Create a user with the role 'admin'
        $admin = User::factory()->create(['role' => 'admin']);

        // Authenticate the admin user
        $this->actingAs($admin);

        // Create customers associated with a different user
        $customerUser = User::factory()->create(['role' => 'customer']);
        $customer = Customer::factory()->create(['user_id' => $customerUser->id]);

        $response = $this->get(route('customers.show', $customer->id));

        $response->assertStatus(200);
        $response->assertViewIs('customers.show');
        $response->assertViewHas('customer', $customer);
    }

    public function test_create_displays_create_form()
    {
        // Create a user with the role 'admin'
        $admin = User::factory()->create(['role' => 'admin']);

        // Authenticate the admin user
        $this->actingAs($admin);

        $response = $this->get(route('customers.create'));

        $response->assertStatus(200);
        $response->assertViewIs('customers.create');
    }

    public function test_store_creates_new_customer()
    {
        // Create a user with the role 'admin'
        $admin = User::factory()->create(['role' => 'admin']);

        // Authenticate the admin user
        $this->actingAs($admin);

        $userData = [
            'customer_name' => 'Test User',
            'customer_email' => 'test@example.com',
            'customer_password' => 'password123',
        ];

        $customerData = [
            'company_name' => 'Test Company',
            'contract_reference' => '12345Y1',
            'customer_city' => 'Test City',
            'customer_country' => 'Test Country',
            'contact_number' => '1234567890',
            'plain_password' => 'password123',
            'notes' => 'Test notes',
        ];

        $response = $this->post(route('customers.store'), array_merge($userData, $customerData));

        // Assert the response status is 200 (since the controller returns a view)
        $response->assertStatus(200);

        // Assert the user was created in the database
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);

        // Assert the customer was created in the database
        $this->assertDatabaseHas('customers', [
            'company_name' => 'Test Company',
            'plain_password' => 'password123', // Assert plain password
        ]);
    }

    public function test_edit_displays_edit_form()
    {
        // Create a user with the role 'admin'
        $admin = User::factory()->create(['role' => 'admin']);

        // Authenticate the admin user
        $this->actingAs($admin);

        // Create customers associated with a different user
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);

        $response = $this->get(route('customers.edit', $customer->id));

        $response->assertStatus(200);
        $response->assertViewIs('customers.edit');
        $response->assertViewHas('customer', $customer);
    }

    public function test_update_modifies_customer()
    {
        // Create a user with the role 'admin'
        $admin = User::factory()->create(['role' => 'admin']);

        // Authenticate the admin user
        $this->actingAs($admin);

        // Create customers associated with a different user
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);

        $updatedData = [
            'company_name' => 'Updated Company',
            'contract_reference' => '54321Y2',
            'customer_city' => 'Updated City',
            'customer_country' => 'Updated Country',
            'contact_number' => '0987654321',
            'notes' => 'Updated notes',
            'customer_name' => 'Updated User',
            'customer_email' => 'updated@example.com',
        ];

        $response = $this->put(route('customers.update', $customer->id), $updatedData);

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseHas('users', ['email' => 'updated@example.com']);
        $this->assertDatabaseHas('customers', ['company_name' => 'Updated Company']);
    }

    public function test_destroy_deletes_customer()
    {
        // Create a user with the role 'admin'
        $admin = User::factory()->create(['role' => 'admin']);

        // Authenticate the admin user
        $this->actingAs($admin);

        // Create customers associated with a different user
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);

        $response = $this->delete(route('customers.destroy', $customer->id));

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}