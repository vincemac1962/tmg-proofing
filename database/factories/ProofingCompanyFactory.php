<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProofingCompanyFactory extends Factory
{
    protected $model = \App\Models\ProofingCompany::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'telephone_1' => $this->faker->phoneNumber,
            'email_address' => $this->faker->safeEmail,
            'web_url' => $this->faker->url,
            'company_logo_url' => $this->faker->imageUrl(200, 200, 'business', true, 'logo'),
        ];
    }
}