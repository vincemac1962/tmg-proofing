<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\Email;
use App\Models\ProofingJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an authenticated user
        $this->user = User::factory()->create([
            'role' => 'admin', // Adjust role as needed
        ]);

        $this->actingAs($this->user);
    }

    public function testIndexMethod()
    {
        // Ensure ProofingJob records exist
        $this->createProofingJobWithDependencies();

        $emails = Email::factory()->count(5)->create();

        $response = $this->get(route('emails.index'));

        $response->assertStatus(200);
        $response->assertViewIs('emails.index');
        $response->assertViewHas('emails', $emails);
    }

    public function testShowMethod()
    {
        // Ensure ProofingJob records exist
        $this->createProofingJobWithDependencies();

        $email = Email::factory()->create();

        $response = $this->get(route('emails.show', $email));

        $response->assertStatus(200);
        $response->assertViewIs('emails.show');
        $response->assertViewHas('email', $email);
    }

    public function testCreateMethod()
    {
        $proofingJob = $this->createProofingJobWithDependencies();

        $response = $this->get(route('emails.create', ['job_id' => $proofingJob->id]));

        $response->assertStatus(200);
        $response->assertViewIs('emails.create');
        $response->assertViewHasAll(['job_id', 'recipient_email']);
    }

    public function testStoreMethod()
    {
        $proofingJob = $this->createProofingJobWithDependencies();

        $data = [
            'job_id' => $proofingJob->id,
            'recipient_email' => $proofingJob->customer->user->email,
            'subject' => 'Test Subject',
            'body' => 'Test Body',
        ];

        $response = $this->post(route('emails.store'), $data);

        $response->assertRedirect(route('emails.index'));
        $this->assertDatabaseHas('emails', [
            'job_id' => $proofingJob->id,
            'customer_id' => $proofingJob->customer->id,
            'recipient_email' => $proofingJob->customer->user->email,
            'subject' => 'Test Subject',
            'body' => 'Test Body',
        ]);
    }

    public function testEditMethod()
    {
        // Ensure ProofingJob records exist
        $this->createProofingJobWithDependencies();

        $email = Email::factory()->create();

        $response = $this->get(route('emails.edit', $email));

        $response->assertStatus(200);
        $response->assertViewIs('emails.edit');
        $response->assertViewHas('email', $email);
    }

    public function testUpdateMethod()
    {
        // Ensure ProofingJob records exist
        $this->createProofingJobWithDependencies();

        $email = Email::factory()->create();

        $updatedData = [
            'job_id' => $email->job_id,
            'recipient_email' => $email->recipient_email,
            'subject' => 'Updated Subject',
            'body' => 'Updated Body',
        ];

        $response = $this->put(route('emails.update', $email), $updatedData);

        $response->assertRedirect(route('emails.index'));
        $this->assertDatabaseHas('emails', $updatedData);
    }

    public function testDestroyMethod()
    {
        // Ensure ProofingJob records exist
        $this->createProofingJobWithDependencies();

        $email = Email::factory()->create();

        $response = $this->delete(route('emails.destroy', $email));

        $response->assertRedirect(route('emails.index'));
        $this->assertDatabaseMissing('emails', ['id' => $email->id]);
    }

    private function createProofingJobWithDependencies()
    {
        $user = User::factory()->create(['role' => 'customer']);
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        return ProofingJob::factory()->create(['customer_id' => $customer->id]);
    }
}