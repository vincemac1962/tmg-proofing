<?php

namespace Tests\Unit;

use App\Models\Designer;
use App\Models\ProofingCompany;
use Tests\TestCase;
use App\Models\User;
use App\Models\Proof;
use App\Models\Customer;
use App\Models\Amendment;
use App\Models\ProofingJob;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AmendmentControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Customer $customer;
    private ProofingJob $proofingJob;
    private Proof $proof;
    private Amendment $amendment;
    private array $validData;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user and authenticate
        $this->user = User::factory()->create([
            'role' => 'admin',
        ]);
        $this->actingAs($this->user);

        // Create a customer user and store it as a class property
        $this->customerUser = User::factory()->create([
            'role' => 'customer',
        ]);

        // Create a customer associated with the customer user
        $this->customer = Customer::factory()->create([
            'user_id' => $this->customerUser->id,
        ]);

        $this->proofingJob = ProofingJob::factory()->create(['customer_id' => $this->customer->id]);
        $this->proof = Proof::factory()->create(['job_id' => $this->proofingJob->id]);

        $this->amendment = Amendment::factory()->create([
            'proof_id' => $this->proof->id,
            'customer_id' => $this->customer->id,
        ]);

        $this->validData = [
            'proof_id' => $this->proof->id,
            'customer_id' => $this->customer->id,
            'amendment_notes' => 'Test amendment content',
            'contract_reference' => 'TEST-REF-123',
        ];
    }

    public function test_index_displays_amendments(): void
    {
        $proofingCompany = ProofingCompany::factory()->create([
            'name' => 'Test Proofing Company',
            'telephone_1' => '1234567890',
            'email_address' => 'test@test.com',
            'web_url' => 'https://testproofing.com',
            'company_logo_url' => 'https://testproofing.com/logo.png',
        ]);

        // Create a designer
        $designer = Designer::factory()->create();

        // Ensure the customer user is created and authenticated
        $customerUser = User::factory()->create(['role' => 'customer']);
        $this->actingAs($customerUser);

        // Create a customer associated with the authenticated user
        $customer = Customer::factory()->create([
            'user_id' => $customerUser->id,
        ]);

        $proofingJob = ProofingJob::factory()->create([
            'customer_id' => $customer->id,
            'proofing_company_id' => $proofingCompany->id,
            'designer_id' => $designer->id,
        ]);

        // Create a proof associated with the proofing job
        $proof = Proof::factory()->create(['job_id' => $proofingJob->id]);

        // Create multiple amendments for testing
        Amendment::factory()->count(3)->create([
            'proof_id' => $proof->id,
            'customer_id' => $customer->id,
        ]);

        // Make the request
        $response = $this->get(route('amendments.index', $customer->id));

        // Assert the response
        $response->assertStatus(200);
        $response->assertViewIs('amendments.index');
        $response->assertViewHas('amendments');
    }

    public function test_show_displays_amendment(): void
    {
        $response = $this->get(route('amendments.show', $this->amendment));

        $response->assertStatus(200);
        $response->assertViewIs('amendments.show');
        $response->assertViewHas('amendment');
    }

    public function test_create_displays_form(): void
    {
        // Start output buffering
        ob_start();

        $response = $this->get(route('amendments.create', [
            'proof_id' => $this->proof->id,
            'customer_id' => $this->customer->id
        ]));

        // Clean the buffer
        ob_get_clean();

        $response->assertStatus(200);
        $response->assertViewIs('amendments.create');
    }

    public function test_store_creates_amendment(): void
    {
        $response = $this->post(route('amendments.store'), $this->validData);

        $response->assertStatus(302);
        $response->assertRedirect(route('amendments.index', $this->customer->id));
        $this->assertDatabaseHas('amendments', $this->validData);
    }

    public function test_edit_displays_form(): void
    {
        $response = $this->get(route('amendments.edit', $this->amendment));

        $response->assertStatus(200);
        $response->assertViewIs('amendments.edit');
        $response->assertViewHas('amendment');
    }

    public function test_update_modifies_amendment(): void
    {
        $response = $this->put(route('amendments.update', $this->amendment), $this->validData);

        $response->assertStatus(302);
        $response->assertRedirect(route('amendments.index', $this->customer->id));
        $this->assertDatabaseHas('amendments', $this->validData);
    }

    public function test_destroy_deletes_amendment(): void
    {
        $response = $this->delete(route('amendments.destroy', $this->amendment));

        $response->assertStatus(302);
        $response->assertRedirect(route('amendments.index', $this->customer->id));
        $this->assertDatabaseMissing('amendments', ['id' => $this->amendment->id]);
    }
}