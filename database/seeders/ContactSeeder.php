<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Contact::create([
            'poc_for' => 'proofing',
            'name' => 'Vince MacRae',
            'email' => 'vince.macrae@gmail.com',
        ]);
    }
}