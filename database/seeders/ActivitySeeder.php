<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ProofingJob;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run()
    {
        $activityTypes = [
            'proof uploaded',
            'comment added',
            'amendment requested',
            'amendment uploaded',
            'proof emailed',
        ];

        // Loop through each ProofingJob and create activities
        ProofingJob::all()->each(function ($proofingJob) use ($activityTypes) {
            foreach ($activityTypes as $activityType) {
                Activity::factory()->create([
                    'job_id' => $proofingJob->id,
                    'activity_type' => $activityType,
                ]);
            }
        });
    }
}