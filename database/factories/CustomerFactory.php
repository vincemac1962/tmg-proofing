<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition()
    {
        // Fetch a random user with the 'customer' role who does not already have a Customer record
        $user = User::where('role', 'customer')
            ->doesntHave('customers') // Ensure the user does not already have a Customer record
            ->inRandomOrder()
            ->first();

        if (!$user) {
            throw new \Exception('No available users with the "customer" role to associate with a Customer record.');
        }

        return [
            'user_id' => $user->id,
            'contract_reference' => $this->faker->unique()->numerify('#####') . $this->faker->randomElement(['Y1', 'Y2']),
            'company_name' => $this->faker->company,
            'customer_city' => $this->faker->city,
            'customer_country' => $this->faker->randomElement(['United Kingdom', 'Ireland', 'Channel Islands', 'Canada', 'USA']),
            'contact_number' => $this->faker->phoneNumber,
            'plain_password' => null, // No need to store plain passwords
            'notes' => $this->faker->paragraph,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}