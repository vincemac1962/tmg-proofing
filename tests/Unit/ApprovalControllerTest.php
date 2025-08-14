<?php

namespace Tests\Unit;

use App\Models\Approval;
use App\Models\Customer;
use App\Models\Proof;
use App\Models\ProofingJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApprovalControllerTest extends TestCase
{
    use RefreshDatabase;

    private Approval $approval;
    private array $validData;

    protected function setUp(): void
    {
        parent::setUp();

        // create and authenticate a user
        $this->user = User::factory()->create();
        $this->actingAs($this->user);  // Authenticate the user

        $customer = Customer::factory()->create();
        $proofingJob = ProofingJob::factory()->create(['customer_id' => $customer->id]);
        $proof = Proof::factory()->create(['job_id' => $proofingJob->id]);

        $this->validData = [
            'proof_id' => $proof->id,
            'customer_id' => $customer->id,
            'approved_at' => now(),
            'approved_by' => 'John Doe',
        ];

        $this->approval = Approval::factory()->create();
    }

    public function test_index_displays_approvals()
    {
        $response = $this->get(route('approvals.index'));

        $response->assertStatus(200);
        $response->assertViewHas('approvals');
    }

    public function test_index_filters_by_date_range()
    {
        $startDate = now()->subDays(5)->format('Y-m-d');
        $endDate = now()->format('Y-m-d');

        $response = $this->get(route('approvals.index', [
            'start_date' => $startDate,
            'end_date' => $endDate
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('approvals');
    }

    public function test_show_displays_approval()
    {
        $response = $this->get(route('approvals.show', $this->approval));

        $response->assertStatus(200);
        $response->assertViewHas('approval');
    }

    public function test_create_displays_form()
    {
        $response = $this->get(route('approvals.create'));

        $response->assertStatus(200);
    }

    public function test_store_creates_approval()
    {
        $response = $this->post(route('approvals.store'), $this->validData);

        $response->assertRedirect(route('approvals.index'));

        // Adjust the format of `approved_at` to match the database format
        $expectedData = $this->validData;
        $expectedData['approved_at'] = $this->validData['approved_at']->format('Y-m-d H:i:s');

        $this->assertDatabaseHas('approvals', $expectedData);
    }

    public function test_store_validates_required_fields()
    {
        $response = $this->post(route('approvals.store'), []);

        $response->assertSessionHasErrors(['proof_id', 'customer_id', 'approved_at', 'approved_by']);
    }

    public function test_edit_displays_form()
    {
        $response = $this->get(route('approvals.edit', $this->approval));

        $response->assertStatus(200);
        $response->assertViewHas('approval');
    }

    public function test_update_modifies_approval()
    {
        $response = $this->put(route('approvals.update', $this->approval), $this->validData);

        $response->assertRedirect(route('approvals.index'));
        $this->assertDatabaseHas('approvals', $this->validData);
    }

    public function test_destroy_removes_approval()
    {
        $response = $this->delete(route('approvals.destroy', $this->approval));

        $response->assertRedirect(route('approvals.index'));
        $this->assertDatabaseMissing('approvals', ['id' => $this->approval->id]);
    }
}