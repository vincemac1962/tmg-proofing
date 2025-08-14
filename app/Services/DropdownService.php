<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DropdownService
{
    public function getDropdownValues(): array
    {
        $countries = DB::table('customers')
            ->select('customer_country')
            ->distinct()
            ->orderBy('customer_country', 'asc')
            ->pluck('customer_country');

        $proofingCompanies = DB::table('proofing_companies')
            ->select('name')
            ->distinct()
            ->orderBy('name', 'asc')
            ->pluck('name');

        return [$countries, $proofingCompanies];
    }
}
