<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\Designer;
use App\Models\ProofingCompany;
use App\Models\ProofingJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProofingJobControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $customer;
    private $proofingJob;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user and authenticate
        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        // Create test customer
        $this->customer = Customer::factory()->create();

        // Create test proofing job
        $this->proofingJob = ProofingJob::factory()->create([
            'customer_id' => $this->customer->id,
            'contract_reference' => 'TEST-001'
        ]);
    }

    public function test_index_displays_proofing_jobs()
    {
        $response = $this->get(route('proofing_jobs.index', ['customerId' => $this->customer->id]));

        $response->assertStatus(200);
        $response->assertViewIs('proofing_jobs.index');
        $response->assertViewHas('proofingJobs');
        $response->assertSee($this->proofingJob->contract_reference);
    }

    public function test_show_displays_proofing_job()
    {
        $response = $this->get(route('proofing_jobs.show', [
            'customerId' => $this->customer->id,
            'proofingJob' => $this->proofingJob->id
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('proofing_jobs.show');
        $response->assertViewHas('proofingJob');
    }

    public function test_edit_displays_form()
    {
        $response = $this->get(route('proofing_jobs.edit', [
            'customerId' => $this->customer->id,
            'proofingJob' => $this->proofingJob->id
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('proofing_jobs.edit');
        $response->assertViewHas('proofingJob');
    }

    public function test_update_modifies_proofing_job()
    {
        $proofingCompany = ProofingCompany::factory()->create(['active' => true]);

        $response = $this->put(route('proofing_jobs.update', [
            'customerId' => $this->customer->id,
            'proofingJob' => $this->proofingJob->id
        ]), [
            'proofing_company_id' => $proofingCompany->id,
            'contract_reference' => 'TEST-002'
        ]);

        $response->assertRedirect(route('customers.show', $this->customer->id));
        $this->assertDatabaseHas('proofing_jobs', [
            'id' => $this->proofingJob->id,
            'proofing_company_id' => $proofingCompany->id,
            'contract_reference' => 'TEST-002'
        ]);
    }


    public function test_store_creates_proofing_job()
    {
        // Create a valid designer
        $designer = Designer::factory()->create();

        // Assert the designer exists in the database
        $this->assertDatabaseHas('designers', ['id' => $designer->id]);

        // Create a valid proofing company
        $proofingCompany = ProofingCompany::factory()->create(['active' => true]);

        // Make the POST request with valid data
        $response = $this->post(route('proofing_jobs.store', [
            'customerId' => $this->customer->id,
        ]), [
            'customer_id' => $this->customer->id,
            'designer_id' => $designer->id, // Use the valid designer ID
            'proofing_company_id' => $proofingCompany->id,
            'contract_reference' => 'TEST-001',
            'advert_location' => 'Location A',
            'description' => 'Test description',
        ]);

        // Assert the response and database state
        $response->assertStatus(200);
        $this->assertDatabaseHas('proofing_jobs', [
            'customer_id' => $this->customer->id,
            'designer_id' => $designer->id,
            'proofing_company_id' => $proofingCompany->id,
            'contract_reference' => 'TEST-001',
        ]);
    }

    public function test_destroy_deletes_proofing_job()
    {
        $response = $this->delete(route('proofing_jobs.destroy', [
            'customerId' => $this->customer->id,
            'proofingJob' => $this->proofingJob->id
        ]));

        $response->assertRedirect(route('customers.show', $this->customer->id));
        $this->assertDatabaseMissing('proofing_jobs', [
            'id' => $this->proofingJob->id
        ]);
    }
}