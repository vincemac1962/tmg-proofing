<?php

namespace Tests\Unit;

use App\Models\Activity;
use App\Models\Designer;
use App\Models\ProofingCompany;
use App\Models\ProofingJob;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $customer;
    protected $proofingJob;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user for authentication
        $this->user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($this->user);
    }

    public function test_index_displays_activities()
    {
        // Create a user with the "customer" role
        $customerUser = User::factory()->create(['role' => 'customer']);

        // Create a customer associated with the "customer" role user
        $customer = Customer::factory()->create(['user_id' => $customerUser->id]);

        // Create a proofing company
        $proofingCompany = ProofingCompany::factory()->create([
            'name' => 'Test Proofing Company',
            'telephone_1' => '1234567890',
            'email_address' => 'test@test.com',
            'web_url' => 'https://testproofing.com',
            'company_logo_url' => 'https://testproofing.com/logo.png',
        ]);

        // Create a designer
        $designer = Designer::factory()->create();

        // Create a proofing job associated with the customer and designer
        $proofingJob = ProofingJob::factory()->create([
            'customer_id' => $customer->id,
            'proofing_company_id' => $proofingCompany->id,
            'designer_id' => $designer->id,
        ]);

        // Create an activity for the proofing job
        $activity = Activity::factory()->create([
            'job_id' => $proofingJob->id,
            'activity_type' => 'proof uploaded',
            ]);

        // Call the route
        $response = $this->get(route('activities.index'));

        // Assert the activity is displayed
        $response->assertStatus(200);
        $response->assertSee($activity->activity_type);
    }

    public function test_index_filters_by_date_range()
    {
        // Create a user with the "customer" role
        $customerUser = User::factory()->create(['role' => 'customer']);

        // Create a customer associated with the "customer" role user
        $customer = Customer::factory()->create(['user_id' => $customerUser->id]);

        // Create a proofing company
        $proofingCompany = ProofingCompany::factory()->create([
            'name' => 'Test Proofing Company',
            'telephone_1' => '1234567890',
            'email_address' => 'test@test.com',
            'web_url' => 'https://testproofing.com',
            'company_logo_url' => 'https://testproofing.com/logo.png',
        ]);

        // Create a designer
        $designer = Designer::factory()->create();

        // Create a proofing job associated with the customer and designer
        $this->proofingJob = ProofingJob::factory()->create([
            'customer_id' => $customer->id,
            'proofing_company_id' => $proofingCompany->id,
            'designer_id' => $designer->id,
        ]);

        // Create activities with specific dates
        $activity1 = Activity::factory()->withJob()->withActivityType()->create([
            'user_id' => $this->user->id,
            'job_id' => $this->proofingJob->id,
            'created_at' => now()->subDays(5)->startOfDay(),
            'updated_at' => now()->subDays(5)->startOfDay()
        ]);

        $activity2 = Activity::factory()->withJob()->withActivityType()->create([
            'user_id' => $this->user->id,
            'job_id' => $this->proofingJob->id,
            'created_at' => now()->startOfDay(),
            'updated_at' => now()->startOfDay()
        ]);

        // Use precise date range for filtering
        $startDate = now()->subDay()->startOfDay()->format('Y-m-d');
        $endDate = now()->endOfDay()->format('Y-m-d');

        $response = $this->get(route('activities.filter', [
            'start_date' => $startDate,
            'end_date' => $endDate
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('activities');

        $activities = $response->viewData('activities');
        $this->assertTrue($activities->contains('id', $activity2->id));
        $this->assertFalse($activities->contains('id', $activity1->id));
    }

    public function test_job_activities_shows_specific_job_activities()
    {
        // Create a user with the "customer" role
        $customerUser = User::factory()->create(['role' => 'customer']);

        // Create a customer associated with the "customer" role user
        $customer = Customer::factory()->create(['user_id' => $customerUser->id]);

        // Create a proofing company
        $proofingCompany = ProofingCompany::factory()->create([
            'name' => 'Test Proofing Company',
            'telephone_1' => '1234567890',
            'email_address' => 'test@test.com',
            'web_url' => 'https://testproofing.com',
            'company_logo_url' => 'https://testproofing.com/logo.png',
        ]);

        // Create a designer
        $designer = Designer::factory()->create();

        // Create a proofing job associated with the customer and designer
        $job = ProofingJob::factory()->create([
            'customer_id' => $customer->id,
            'proofing_company_id' => $proofingCompany->id,
            'designer_id' => $designer->id,
        ]);

        // Create activities for the job and another job
        $activityForJob = Activity::factory()->create([
            'job_id' => $job->id,
            'activity_type' => 'proof uploaded',
        ]);

        $activityForAnotherJob = Activity::factory()->create([
            'job_id' => ProofingJob::factory()->create([
                'customer_id' => $customer->id,
                'designer_id' => $designer->id, // Ensure valid designer_id
            ])->id,
            'activity_type' => 'proof approved',
        ]);

        // Call the route
        $response = $this->get(route('activities.job', $job->id));

        // Assert the response contains only the activities for the specific job
        $response->assertStatus(200);
        $response->assertViewHas('activities', function ($activities) use ($activityForJob, $activityForAnotherJob) {
            return $activities->contains($activityForJob) && !$activities->contains($activityForAnotherJob);
        });
    }

    public function test_show_displays_activity()
    {
        // Create a user with the "customer" role
        $customerUser = User::factory()->create(['role' => 'customer']);

        // Create a customer associated with the "customer" role user
        $customer = Customer::factory()->create(['user_id' => $customerUser->id]);

        // Create a proofing company
        $proofingCompany = ProofingCompany::factory()->create([
            'name' => 'Test Proofing Company',
            'telephone_1' => '1234567890',
            'email_address' => 'test@test.com',
            'web_url' => 'https://testproofing.com',
            'company_logo_url' => 'https://testproofing.com/logo.png',
        ]);

        // Create a designer
        $designer = Designer::factory()->create();

        // Create a proofing job associated with the customer and designer
        $job = ProofingJob::factory()->create([
            'customer_id' => $customer->id,
            'proofing_company_id' => $proofingCompany->id,
            'designer_id' => $designer->id,
        ]);

        // Create an activity associated with the proofing job
        $activity = Activity::factory()->create([
            'job_id' => $job->id,
            'activity_type' => 'proof uploaded',
        ]);

        // Call the route
        $response = $this->get(route('activities.show', $activity));

        // Assert the response
        $response->assertStatus(200);
        $response->assertViewIs('activities.show');
        $response->assertViewHas('activity');
    }

    public function test_create_displays_form()
    {
        $response = $this->get(route('activities.create'));

        $response->assertStatus(200);
        $response->assertViewIs('activities.create');
        $response->assertViewHas('activityTypes');
    }

    public function test_store_creates_new_activity()
    {
        // Create a user with the "customer" role
        $customerUser = User::factory()->create(['role' => 'customer']);

        // Create a customer associated with the "customer" role user
        $customer = Customer::factory()->create(['user_id' => $customerUser->id]);

        // Create a proofing company
        $proofingCompany = ProofingCompany::factory()->create([
            'name' => 'Test Proofing Company',
            'telephone_1' => '1234567890',
            'email_address' => 'test@test.com',
            'web_url' => 'https://testproofing.com',
            'company_logo_url' => 'https://testproofing.com/logo.png',
        ]);

        // Create a designer
        $designer = Designer::factory()->create();

        // Create a proofing job associated with the customer and designer
        $job = ProofingJob::factory()->create([
            'customer_id' => $customer->id,
            'proofing_company_id' => $proofingCompany->id,
            'designer_id' => $designer->id,
        ]);

        // Create an activity type
        $activityData = [
            'job_id' => $job->id,
            'user_id' => $this->user->id,
            'activity_type' => 'proof uploaded',
            'notes' => 'Test notes'
        ];

        $response = $this->post(route('activities.store'), $activityData);

        $response->assertRedirect(route('activities.index'));
        $this->assertDatabaseHas('activities', $activityData);
    }

    public function test_edit_displays_form()
    {
        // Create a proofing company
        $proofingCompany = ProofingCompany::factory()->create([
            'name' => 'Test Proofing Company',
            'telephone_1' => '1234567890',
            'email_address' => 'test@test.com',
            'web_url' => 'https://testproofing.com',
            'company_logo_url' => 'https://testproofing.com/logo.png',
        ]);

        // Create a user with the "customer" role
        $customerUser = User::factory()->create(['role' => 'customer']);

        // Create a customer associated with the "customer" role user
        $customer = Customer::factory()->create(['user_id' => $customerUser->id]);

        // Create a designer
        $designer = Designer::factory()->create();

        // Create a proofing job associated with the proofing company, customer, and designer
        $proofingJob = ProofingJob::factory()->create([
            'customer_id' => $customer->id,
            'proofing_company_id' => $proofingCompany->id,
            'designer_id' => $designer->id,
        ]);

        // Create an activity associated with the proofing job
        $activity = Activity::factory()->create([
            'job_id' => $proofingJob->id,
            'activity_type' => 'proof uploaded',
        ]);

        // Call the edit route
        $response = $this->get(route('activities.edit', $activity));

        // Assert the response
        $response->assertStatus(200);
        $response->assertViewIs('activities.edit');
        $response->assertViewHas('activity');
    }

    public function test_update_modifies_activity()
    {
        // Create a proofing company
        $proofingCompany = ProofingCompany::factory()->create([
            'name' => 'Test Proofing Company',
            'telephone_1' => '1234567890',
            'email_address' => 'test@test.com',
            'web_url' => 'https://testproofing.com',
            'company_logo_url' => 'https://testproofing.com/logo.png',
        ]);

        // Create a user with the "customer" role
        $customerUser = User::factory()->create(['role' => 'customer']);

        // Create a customer associated with the "customer" role user
        $customer = Customer::factory()->create(['user_id' => $customerUser->id]);

        // Create a designer
        $designer = Designer::factory()->create();

        // Create a proofing job associated with the proofing company, customer, and designer
        $proofingJob = ProofingJob::factory()->create([
            'customer_id' => $customer->id,
            'proofing_company_id' => $proofingCompany->id,
            'designer_id' => $designer->id,
        ]);

        $activity = Activity::factory()->withJob()->withActivityType()->create([
            'job_id' => $proofingJob->id,
            'activity_type' => 'proof uploaded',
        ]);

        // Prepare updated data
        $updatedData = [
            'activity_type' => 'proof approved',
            'notes' => 'Updated notes'
        ];

        $response = $this->put(route('activities.update', ['activity' => $activity->id]), $updatedData);

        $response->assertRedirect(route('activities.job', $proofingJob->id));
        $this->assertDatabaseHas('activities', array_merge(
            ['id' => $activity->id],
            $updatedData
        ));
    }

    public function test_destroy_deletes_activity()
    {
        // Create a proofing company
        $proofingCompany = ProofingCompany::factory()->create([
            'name' => 'Test Proofing Company',
            'telephone_1' => '1234567890',
            'email_address' => 'test@test.com',
            'web_url' => 'https://testproofing.com',
            'company_logo_url' => 'https://testproofing.com/logo.png',
        ]);

        // Create a user with the "customer" role
        $customerUser = User::factory()->create(['role' => 'customer']);

        // Create a customer associated with the "customer" role user
        $customer = Customer::factory()->create(['user_id' => $customerUser->id]);

        // Create a designer
        $designer = Designer::factory()->create();

        // Create a proofing job associated with the proofing company, customer, and designer
        $proofingJob = ProofingJob::factory()->create([
            'customer_id' => $customer->id,
            'proofing_company_id' => $proofingCompany->id,
            'designer_id' => $designer->id,
        ]);

        $activity = Activity::factory()->withJob()->withActivityType()->create([
            'job_id' => $proofingJob->id,
            'activity_type' => 'proof uploaded',
        ]);

        $response = $this->delete(route('activities.destroy', $activity));

        $response->assertRedirect(route('activities.index'));
        $this->assertDatabaseMissing('activities', ['id' => $activity->id]);
    }
}