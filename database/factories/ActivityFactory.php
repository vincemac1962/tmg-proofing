<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\ProofingJob;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition()
    {
        // Fetch an existing User record where role is 'admin' or 'designer'
        $user = User::whereIn('role', ['admin', 'designer'])->inRandomOrder()->first();

        if (!$user) {
            throw new \Exception('No existing User records found with role "admin" or "designer".');
        }

        return [
            'job_id' => null, // To be set explicitly in the seeder
            'user_id' => $user->id,
            'activity_type' => null, // To be set explicitly in the seeder
            'ip_address' => $this->faker->ipv4,
            'notes' => $this->faker->paragraph,
            'created_at' => $this->faker->dateTimeBetween('-1 month'),
            'updated_at' => $this->faker->dateTimeBetween('-1 month'),
        ];
    }

    // Add a state to set a default job_id for tests
    public function withJob()
    {
        return $this->state(function (array $attributes) {
            return [
                'job_id' => ProofingJob::factory(),
            ];
        });
    }

    public function withActivityType()
    {
        return $this->state(function (array $attributes) {
            return [
                'activity_type' => 'proof uploaded', // or any default type
            ];
        });
    }
}