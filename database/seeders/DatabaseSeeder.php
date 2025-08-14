<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProofingCompanySeeder::class,
            UserSeeder::class,
            DesignerSeeder::class,
            CustomerSeeder::class,
            ProofingJobSeeder::class,
            ProofingHistorySeeder_1::class,
            ProofingHistorySeeder_2::class,
            ProofingHistorySeeder_3::class,
            ReportSeeder::class,
        ]);
    }
}