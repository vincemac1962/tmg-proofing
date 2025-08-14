<?php

namespace Database\Factories;

use App\Models\Email;
use App\Models\ProofingJob;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailFactory extends Factory
{
    protected $model = Email::class;

    public function definition()
    {
        // Fetch an existing ProofingJob record
        $proofingJob = ProofingJob::inRandomOrder()->first();

        if (!$proofingJob) {
            throw new \Exception('No existing ProofingJob records found.');
        }

        $customer = $proofingJob->customer;

        if (!$customer) {
            throw new \Exception('No customer associated with the selected ProofingJob.');
        }

        $recipientEmail = User::find($customer->user_id)->email;

        return [
            'job_id' => $proofingJob->id, // Use existing ProofingJob
            'customer_id' => $customer->id, // Use associated Customer
            'recipient_email' => $recipientEmail,
            'subject' => $this->faker->sentence(),
            'body' => $this->faker->paragraph(),
        ];
    }
}