<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Amendment;
use App\Models\Proof;
use App\Models\ProofingJob;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProofingHistorySeeder_2 extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Get jobs between 2 and 4 weeks old
        $proofingJobs = ProofingJob::where('created_at', '>', now()->subWeeks(4))
            ->where('created_at', '<=', now()->subWeeks(2))
            ->where('status', 'pending')
            ->get();

        $adminUser = User::where('role', 'admin')->inRandomOrder()->first()
            ?? User::where('role', 'designer')->inRandomOrder()->first();

        foreach ($proofingJobs as $job) {
            $currentTime = $job->created_at;

            // Step A: Create initial proof and activity
            $proof = Proof::create([
                'job_id' => $job->id,
                'proof_sent' => $currentTime,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ]);

            $currentTime = $currentTime->addMinutes(10);

            $job->update([
                'status' => 'proof uploaded',
                'updated_at' => $currentTime,
            ]);

            Activity::create([
                'job_id' => $job->id,
                'user_id' => $adminUser->id,
                'activity_type' => 'proof uploaded',
                'ip_address' => '127.0.0.1',
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ]);

            // Step B: Update status to emailed
            $currentTime = $currentTime->addMinutes(10);

            $job->update([
                'status' => 'emailed',
                'updated_at' => $currentTime,
            ]);

            Activity::create([
                'job_id' => $job->id,
                'user_id' => $adminUser->id,
                'activity_type' => 'proof emailed',
                'ip_address' => '127.0.0.1',
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ]);

            // Step C: Create amendment
            $currentTime = $currentTime->addDay();

            $job->update([
                'status' => 'awaiting amendment',
                'updated_at' => $currentTime,
            ]);

            Amendment::create([
                'proof_id' => $proof->id,
                'customer_id' => $job->customer_id,
                'contract_reference' => $job->contract_reference,
                'amendment_notes' => $faker->paragraph(),
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ]);

            Activity::create([
                'job_id' => $job->id,
                'user_id' => $job->customer->user_id,
                'activity_type' => 'amendment requested',
                'ip_address' => $faker->ipv4,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ]);
        }
    }
}