<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create designer users
        User::factory()->designer()->create([
            'name' => 'Liam Fogerty',
            'email' =>'liam@timemg.com',
            'password' => Hash::make('T1m3M3d1a'),
            ]);

        User::factory()->designer()->create([
            'name' => 'Chris Bond',
            'email' =>'chris.bond@timemg.com',
            'password' => Hash::make('T1m3M3d1a'),
        ]);

        User::factory()->designer()->create([
            'name' => 'Ray Gritt',
            'email' =>'raygritt@hotmail.co.uk',
            'password' => Hash::make('T1m3M3d1a'),
        ]);

        // Create admin users
        User::factory()->admin()->create([
            'name' => 'Hannah Dunn',
            'email' => 'design@timemg.com',
            'password' => Hash::make('T1m3M3d1a'),
            ] );

        // Create one super_admin
        User::factory()->superAdmin()->create([
            'name' => 'Vince MacRae',
            'email' => 'vince@test.com',
            'password' => Hash::make('T1m3M3d1a'),
        ]);
    }
}