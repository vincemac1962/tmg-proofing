<?php

namespace Database\Factories;

use App\Models\Amendment;
use App\Models\Proof;
use Illuminate\Database\Eloquent\Factories\Factory;

class AmendmentFactory extends Factory
{
    protected $model = Amendment::class;

    public function definition()
    {
        $proof = Proof::inRandomOrder()->first();

        return [
            'proof_id' => $proof->id,
            'customer_id' => $proof->proofingJob->customer_id,
            'amendment_notes' => $this->faker->paragraph(),
            'contract_reference' => $proof->proofingJob->contract_reference,
        ];
    }
}