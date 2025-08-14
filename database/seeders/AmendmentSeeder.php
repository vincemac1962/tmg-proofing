<?php

namespace Database\Seeders;

use App\Models\Amendment;
use Illuminate\Database\Seeder;

class AmendmentSeeder extends Seeder
{
    public function run()
    {
        Amendment::factory()->count(5)->create();
    }
}