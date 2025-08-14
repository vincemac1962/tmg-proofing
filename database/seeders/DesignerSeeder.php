<?php

namespace Database\Seeders;

use App\Models\Designer;
use App\Models\User;
use Illuminate\Database\Seeder;

class DesignerSeeder extends Seeder
{
    public function run(): void
    {
        // Create a Designer record for each User with the 'designer' role
        User::where('role', 'designer')->each(function ($user) {
            Designer::updateOrCreate(
                ['user_id' => $user->id], // Ensure no duplicate records
                [
                    'name' => $user->name,
                    'email' => $user->email,
                    'notes' => null,
                    'active' => true,
                ]
            );
        });
    }
}