<?php

namespace Database\Factories;

use App\Models\Proof;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApprovalFactory extends Factory
{
    public function definition(): array
    {
        // Fetch an existing Proof record
        $proof = Proof::inRandomOrder()->first();

        if (!$proof) {
            throw new \Exception('No existing Proof records found.');
        }

        // Fetch an existing Customer record
        $customer = Customer::inRandomOrder()->first();

        if (!$customer) {
            throw new \Exception('No existing Customer records found.');
        }

        return [
            'proof_id' => $proof->id,
            'customer_id' => $customer->id,
            'approved_by' => $this->faker->name,
            'approved_at' => now(),
        ];
    }
}