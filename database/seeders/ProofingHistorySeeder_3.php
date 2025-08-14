<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Proof;
use App\Models\ProofingJob;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProofingHistorySeeder_3 extends Seeder
{
    public function run(): void
    {
        // Get jobs less than 2 weeks old
        $proofingJobs = ProofingJob::where('created_at', '>', now()->subWeeks(2))
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
        }
    }
}