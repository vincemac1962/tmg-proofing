<?php

namespace Database\Seeders;

use App\Models\Email;
use Illuminate\Database\Seeder;

class EmailSeeder extends Seeder
{
    public function run()
    {
        Email::factory()->count(5)->create();
    }
}