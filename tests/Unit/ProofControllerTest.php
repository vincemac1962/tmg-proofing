<?php

namespace Tests\Unit;

use App\Mail\CustomerLoginMail;
use App\Models\Customer;
use App\Models\Designer;
use App\Models\Proof;
use App\Models\ProofingCompany;
use App\Models\ProofingJob;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ProofControllerTest extends TestCase
{
    use DatabaseTransactions;

    private $user;
    private $customer;
    private $proofingJob;
    private $proof;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->user = User::factory()->create(['email' => 'test@example.com', 'name' => 'Test User']);
        $this->actingAs($this->user);

        $this->customer = Customer::factory()->create([
            'contract_reference' => 'TEST-001',
            'company_name' => 'Test Company',
            'user_id' => $this->user->id, // Ensure the customer is linked to the user
        ]);

        $this->proofingJob = ProofingJob::factory()->create([
            'customer_id' => $this->customer->id,
            'status' => 'pending',
        ]);

        $file = UploadedFile::fake()->create('test.mp4', 100);
        $path = $file->store('proofs', 'public');

        $this->proof = Proof::factory()->create([
            'job_id' => $this->proofingJob->id,
            'file_path' => $path,
        ]);
    }

    public function test_index_displays_proofs()
    {
        $response = $this->get(route('proofs.index'));

        $response->assertStatus(200);
        $response->assertViewIs('proofs.index');
        $response->assertViewHas('proofs');
        $response->assertSee($this->customer->contract_reference);
        $response->assertSee($this->customer->company_name);
    }

    public function test_show_displays_proof()
    {
        $response = $this->get(route('proofs.show', $this->proof));

        $response->assertStatus(200);
        $response->assertViewIs('proofs.show');
        $response->assertViewHas('proof');
        $response->assertSee($this->proof->file_path);
    }

    public function test_create_displays_form()
    {
        $response = $this->get(route('proofs.create', ['jobId' => $this->proofingJob->id]));

        $response->assertStatus(200);
        $response->assertViewIs('proofs.create');
    }

    public function test_store_creates_new_proof()
    {
        $file = UploadedFile::fake()->create('test.mp4', 100);

        $response = $this->post(route('proofs.store'), [
            'job_id' => $this->proofingJob->id,
            'file' => $file,
        ]);

        $proof = Proof::latest()->first();

        $response->assertRedirect();
        $this->followRedirects($response)->assertOk();
        Storage::disk('public')->assertExists($proof->file_path);
        $this->assertDatabaseHas('proofs', [
            'job_id' => $this->proofingJob->id,
        ]);
    }

    public function test_store_validates_required_fields()
    {
        $response = $this->post(route('proofs.store'), []);

        $response->assertSessionHasErrors(['proofing_job_id', 'file']);
    }

    public function test_edit_displays_form()
    {
        $response = $this->get(route('proofs.edit', $this->proof));

        $response->assertStatus(200);
        $response->assertViewIs('proofs.edit');
        $response->assertViewHas('proof');
        $response->assertSee($this->proof->file_path);
    }

    public function test_update_modifies_proof()
    {
        $file = UploadedFile::fake()->create('new.mp4', 100);

        $response = $this->put(route('proofs.update', $this->proof), [
            'job_id' => $this->proofingJob->id,
            'file' => $file,
            'notes' => 'Updated notes'
        ]);

        $this->proof->refresh();

        $response->assertRedirect(route('proofs.show', $this->proof));
        Storage::disk('public')->assertExists($this->proof->file_path);
        $this->assertEquals('Updated notes', $this->proof->notes);
    }

    public function test_update_without_file()
    {
        $oldPath = $this->proof->file_path;

        $response = $this->put(route('proofs.update', $this->proof), [
            'job_id' => $this->proofingJob->id,
            'notes' => 'Updated notes only'
        ]);

        $this->proof->refresh();

        $response->assertRedirect(route('proofs.show', $this->proof));
        $this->assertEquals($oldPath, $this->proof->file_path);
        $this->assertEquals('Updated notes only', $this->proof->notes);
    }

    public function test_destroy_deletes_proof()
    {
        Storage::disk('public')->put($this->proof->file_path, 'test content');

        $response = $this->delete(route('proofs.destroy', $this->proof));

        $response->assertRedirect(route('proofs.index'));
        $this->assertDatabaseMissing('proofs', ['id' => $this->proof->id]);
        Storage::disk('public')->assertMissing($this->proof->file_path);
    }

    public function test_send_proof_email_sends_email_successfully()
    {
        // Fake the mailer
        Mail::fake();

        // Ensure the user is authenticated
        $this->actingAs($this->user);

        // Create the user records
        $customerUser = User::factory()->create([
            'name' => 'Test Customer',
            'email' => 'test_customer@email.co.uk',
            'role' => 'customer',
            'password' => bcrypt('password'),
        ]);

        $designerUser = User::factory()->create([
            'name' => 'Test Designer',
            'email' => 'test_designer@email.co.uk',
            'role' => 'designer',
            'password' => bcrypt('password'),
        ]);

        // Create the customer record
        $customer = Customer::factory()->create([
            'user_id' => $customerUser->id,
            'company_name' => 'Test Company',
            'contract_reference' => 'test-xxx',
            'plain_password' => 'password',
        ]);

        dump('Customer created:', $customer->toArray());

        // Create the designer record
        $designer = Designer::factory()->create([
            'user_id' => $designerUser->id,
            'name' => 'Test Designer',
            'email' => 'test_designer@email.com',
        ]);

        // Create the proofing company record
        $proofingCompany = ProofingCompany::factory()->create([
            'name' => 'Test Proofing Company',
            'address' => '123 Test Street',
            'telephone_1' => '01234 567890',
            'email_address' => 'design@testcompany.com',
            'email_signatory' => 'Hannah Dunn',
            'signatory_role' => 'Design Coordinator',
            'web_url' => 'http://www.testproofingcompany.com',
            'company_logo_url' => 'storage/proofing_companies/logo.png',
        ]);

        // Create the proofing job record
        $proofingJob = ProofingJob::factory()->create([
            'customer_id' => $customer->id,
            'designer_id' => $designer->id,
            'proofing_company_id' => $proofingCompany->id,
            'advert_location' => 'test location',
            'contract_reference' => 'test-xxx',
        ]);

        // Create the proof record
        $proof = Proof::factory()->create([
            'job_id' => $proofingJob->id,
            'file_path' => 'storage/proofs/' . fake()->word() . '.mp4',
        ]);

        // Prepare the email data
        $emailData = [
            'contract_reference' => $proofingJob->contract_reference,
            'recipient_name' => $customerUser->name,
            'recipient_email' => $customerUser->email,
            'recipient_password' => $customer->plain_password,
            'proofingCompany' => $proofingCompany,
            'advert_location' => $proofingJob->advert_location,
            'company_name' => $customer->company_name,
            'loginId' => $customerUser->email,
            'login_url' => 'http://www.proofing_domain.com',
            'subject' => 'Subject line for email',
            'notes' => 'Please review the proof.',
        ];

        Mail::to($emailData['recipient_email'])->send(new CustomerLoginMail($emailData));
    }

    public function test_send_proof_email_handles_failure()
    {
        // Mock the Mail facade to throw an exception
        Mail::shouldReceive('to')->andThrow(new \Exception('Test email failure'));

        // Call the sendProofEmail method
        $response = $this->post(route('proofs.sendProofEmail', $this->proof->id));

        // Assert the redirect and error message
        $response->assertRedirect(route('proofs.confirm', $this->proof->id));
        $response->assertSessionHasErrors(['error' => 'Failed to send the proof email. Test email failure']);
    }




}