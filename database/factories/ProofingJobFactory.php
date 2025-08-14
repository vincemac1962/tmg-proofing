<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Designer;
use App\Models\ProofingJob;
use App\Models\ProofingCompany;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProofingJobFactory extends Factory
{
    protected $model = ProofingJob::class;

    public function definition()
    {
        return [
            'customer_id' => Customer::factory(),
            'proofing_company_id' => ProofingCompany::factory(), // Default to creating a new ProofingCompany
            'designer_id' => Designer::factory(),
            'contract_reference' => $this->faker->unique()->bothify('??##??##'),
            'title' => $this->faker->sentence,
            'advert_location' => $this->faker->address,
            'description' => $this->faker->paragraph,
            'status' => 'new job',
        ];
    }
}