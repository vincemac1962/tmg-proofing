<?php

namespace Database\Seeders;

use App\Models\ProofingCompany;
use Illuminate\Database\Seeder;

class ProofingCompanySeeder extends Seeder
{
    public function run()
    {
        $companies = [
            [
                'name' => 'Trade Connect',
                'address' => '1959 Upper Water Street, Suite 1301, Tower 1, Halifax, B3J 3N2',
                'telephone_1' => '1 902-334-7744',
                'telephone_2' => null,
                'telephone_3' => null,
                'email_address' => 'design@tradeconnect.ca',
                'web_url' => 'www.tradeconnect.ca',
                'email_signatory' => 'Hannah Dunn',
                'signatory_role' => 'Graphics Administrator',
                'company_logo_url' => 'trade_connect.svg',
                'colour_split' => 'rgb(252, 98, 0)',
            ],
            [
                'name' => 'Instore Connect',
                'address' => null,
                'telephone_1' => 'UK: 0161 828 4243',
                'telephone_2' => 'IRE: +353 (0) 1 588 6908',
                'telephone_3' => null,
                'email_address' => 'design@instore-connect.com',
                'web_url' => 'www.instore-connect.com',
                'email_signatory' => 'Hannah Dunn',
                'signatory_role' => 'Graphics Administrator',
                'company_logo_url' => 'instore_connect.svg',
                'colour_split' => 'rgb(21, 168, 223)',
            ],
            [
                'name' => 'Livestock Media',
                'address' => null,
                'telephone_1' => 'USA: 1 469 587 8361',
                'telephone_2' => 'CAN: 1 855 207 6048',
                'telephone_3' => null,
                'email_address' => 'design@livestockmedia.tv',
                'web_url' => 'www.livestockmedia.tv',
                'email_signatory' => 'Hannah Dunn',
                'signatory_role' => 'Graphics Administrator',
                'company_logo_url' => 'livestock_media.svg',
                'colour_split' => 'rgb(112, 80, 47)',
            ],
            [
                'name' => 'Community Connect',
                'address' => null,
                'telephone_1' => 'CAN: 1 855-207-0648',
                'telephone_2' => 'USA: 1 469-587-8361',
                'telephone_3' => null,
                'email_address' => 'design@community-connects.com',
                'web_url' => 'www.community-connects.com',
                'email_signatory' => 'Hannah Dunn',
                'signatory_role' => 'Graphics Administrator',
                'company_logo_url' => 'community_connect.svg',
                'colour_split' => 'rgb(153, 204, 83)',
            ],
            [
                'name' => 'Property Connect',
                'address' => null,
                'telephone_1' => 'CAN: 1 905 803 9985',
                'telephone_2' => 'USA: 1 302 546 4409',
                'telephone_3' => null,
                'email_address' => 'design@propertyconnectglobal.com',
                'web_url' => 'www.propertyconnectglobal.com',
                'email_signatory' => 'Hannah Dunn',
                'signatory_role' => 'Graphics Administrator',
                'company_logo_url' => 'property_connect.svg',
                'colour_split' => 'rgb(235, 28, 115)',
            ],
        ];

        foreach ($companies as $company) {
            ProofingCompany::create($company);
        }
    }
}