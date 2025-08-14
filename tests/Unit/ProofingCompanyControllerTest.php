<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use App\Models\ProofingCompany;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProofingCompanyControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $proofingCompany;

    protected function setUp(): void
    {
        parent::setUp();
        // Create authenticated user
        $this->user = User::factory()->create(['email' => 'test@example.com', 'name' => 'Test User']);
        $this->actingAs($this->user);

        // Create a proofing company for testing
        $this->proofingCompany = ProofingCompany::factory()->create();
    }


    public function test_it_displays_a_list_of_proofing_companies()
    {
        $response = $this->get(route('proofing_companies.index'));

        $response->assertStatus(200);
        $response->assertViewIs('proofing_companies.index');
        $response->assertViewHas('proofingCompanies');
    }

    public function test_it_displays_the_create_form()
    {
        $response = $this->get(route('proofing_companies.create'));

        $response->assertStatus(200);
        $response->assertViewIs('proofing_companies.create');
    }

    public function test_it_stores_a_new_proofing_company()
    {
        $data = ProofingCompany::factory()->make()->toArray();
        $data['email_signatory'] = 'signatory@example.com';
        $data['signatory_role'] = 'Manager';
        $data['colour_split'] = 'RGB';

        $response = $this->post(route('proofing_companies.store'), $data);

        $response->assertRedirect(route('proofing_companies.index'));
        $this->assertDatabaseHas('proofing_companies', $data);
    }


    public function test_it_displays_a_specific_proofing_company()
    {
        $response = $this->get(route('proofing_companies.show', $this->proofingCompany));

        $response->assertStatus(200);
        $response->assertViewIs('proofing_companies.show');
        $response->assertViewHas('proofingCompany', $this->proofingCompany);
    }


    public function test_it_displays_the_edit_form()
    {
        $response = $this->get(route('proofing_companies.edit', $this->proofingCompany));

        $response->assertStatus(200);
        $response->assertViewIs('proofing_companies.edit');
        $response->assertViewHas('proofingCompany', $this->proofingCompany);
    }

    public function test_it_updates_a_proofing_company()
    {
        $data = ProofingCompany::factory()->make()->toArray();
        $data['email_signatory'] = 'signatory@example.com';
        $data['signatory_role'] = 'Manager';
        $data['colour_split'] = 'RGB';

        $response = $this->put(route('proofing_companies.update', $this->proofingCompany), $data);

        $response->assertRedirect(route('proofing_companies.index'));
        $this->assertDatabaseHas('proofing_companies', $data);
    }


    public function test_it_deletes_a_proofing_company()
    {
        $response = $this->delete(route('proofing_companies.destroy', $this->proofingCompany));

        $response->assertRedirect(route('proofing_companies.index'));
        $this->assertDatabaseMissing('proofing_companies', ['id' => $this->proofingCompany->id]);
    }
}