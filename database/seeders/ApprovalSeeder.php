<?php

namespace Database\Seeders;

use App\Models\Approval;
use Illuminate\Database\Seeder;

class ApprovalSeeder extends Seeder
{
    public function run(): void
    {
        Approval::factory()->count(3)->create();
    }
}