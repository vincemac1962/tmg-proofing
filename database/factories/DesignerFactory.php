<?php

namespace Database\Factories;

use App\Models\Designer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DesignerFactory extends Factory
{
    protected $model = Designer::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create([
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->safeEmail(),
                'role' => 'designer', // Assign a role for the user
            ])->id, // Create a valid User and use its ID
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'notes' => $this->faker->optional()->text(),
            'active' => $this->faker->boolean(90),
        ];
    }
}