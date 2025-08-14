<?php

namespace Database\Factories;

use App\Models\ProofingJob;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProofFactory extends Factory
{
    public function definition()
    {
        // Fetch an existing ProofingJob record
        $proofingJob = ProofingJob::inRandomOrder()->first();

        if (!$proofingJob) {
            throw new \Exception('No existing ProofingJob records found.');
        }

        return [
            'job_id' => $proofingJob->id, // Use existing ProofingJob
            'file_path' => $this->faker->filePath() . '.mp4',
            'notes' => null,
            'proof_sent' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}