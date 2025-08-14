<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('reports')->insert([
            [
                'report_category' => 'Customers',
                'report_name' => 'View Customer Activity By Proofing Job',
                'report_description' => 'View activity for a selected customer..',
                'report_fields' => 'activity_type, address, ip_address, user, notes, updated _at',
                'report_view' => 'customer_activity',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'report_category' => 'Proofing Actions',
                'report_name' => 'View Proofs for Date Period',
                'report_description' => 'View all proofs generated between two dates. Can be filtered on proofing company, country and designer.',
                'report_fields' => 'id, proof_sent, proofing_company_name, contract_reference, customer_name, customer_country, designer_name, notes',
                'report_view' => 'proofs_report',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'report_category' => 'Proofing Actions',
                'report_name' => 'View Amendments for Date Period',
                'report_description' => 'View all customer amendments received between two dates. Can be filtered on proofing company and country.',
                'report_fields' => 'id, amendment_date, proof_id, proofing_job_id, contract_reference, customer_name, country, amendments_notes, designer_name, proofing_company_name',
                'report_view' => 'amendments_report',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'report_category' => 'Proofing Actions',
                'report_name' => 'View Approvals for Date Period',
                'report_description' => 'View all approvals (including deemed) between two dates. Can be filtered on proofing company and country.',
                'report_fields' => 'id, approved_at, proof_id, proofing_job_id, contract_reference, customer_name, customer_country, approved_by, designer_name',
                'report_view' => 'approvals_report',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}