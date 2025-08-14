<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $startDate = Carbon::now()->subWeeks(6)->startOfWeek(); // Start from six weeks ago, Monday
        $endDate = Carbon::now()->subWeeks(1)->endOfWeek(); // End one week ago, Sunday
        $customersPerDay = 2; // Number of customers per weekday
        $totalCustomers = 50; // Total customers to create
        $createdCustomers = 0;
        $passwordPlaceholder = 'password'; // Placeholder for password

        while ($createdCustomers < $totalCustomers && $startDate->lte($endDate)) {
            // Skip weekends
            if (!$startDate->isWeekend()) {
                for ($i = 0; $i < $customersPerDay; $i++) {
                    // Create a user record
                    $user = User::factory()->state([
                        'role' => 'customer',
                        'password' => Hash::make($passwordPlaceholder),
                        'created_at' => $startDate->copy(),
                        'updated_at' => $startDate->copy(),
                    ])->create();

                    // Create a customer record linked to the user
                    Customer::factory()->create([
                        'user_id' => $user->id,
                        'created_at' => $startDate->copy(),
                        'updated_at' => $startDate->copy(),
                        'plain_password' => $passwordPlaceholder, // Store the plain password for reference
                    ]);

                    $createdCustomers++;

                    // Stop if we've reached the total number of customers
                    if ($createdCustomers >= $totalCustomers) {
                        break;
                    }
                }
            }

            // Move to the next day
            $startDate->addDay();
        }
    }
}