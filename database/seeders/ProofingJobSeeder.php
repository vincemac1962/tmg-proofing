<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Designer;
use App\Models\ProofingCompany;
use App\Models\ProofingJob;
use Illuminate\Database\Seeder;

class ProofingJobSeeder extends Seeder
{
    public function run(): void
    {
        $designers = Designer::all();

        // Create one proofing job per customer
        Customer::all()->each(function ($customer) use ($designers) {
            ProofingJob::create([
                'customer_id' => $customer->id,
                'designer_id' => $designers->random()->id ?? null,
                'proofing_company_id' => ProofingCompany::inRandomOrder()->first()->id ?? null,
                'contract_reference' => $customer->contract_reference,
                'title' => fake()->sentence(),
                'advert_location' => fake()->address(),
                'description' => fake()->paragraph(),
                'status' => 'pending',
                'created_at' => $customer->created_at,
                'updated_at' => $customer->updated_at,
            ]);
        });
    }
}