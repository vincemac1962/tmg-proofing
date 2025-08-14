<?php

namespace Database\Seeders;

use App\Models\Proof;
use App\Models\ProofingJob;
use Illuminate\Database\Seeder;

class ProofSeeder extends Seeder
{
    public function run()
    {
        // Fetch all ProofingJob records
        $proofingJobs = ProofingJob::all();

        // Create a Proof for each ProofingJob
        foreach ($proofingJobs as $proofingJob) {
            Proof::factory()->create([
                'job_id' => $proofingJob->id, // Associate Proof with the current ProofingJob
            ]);
        }
    }
}