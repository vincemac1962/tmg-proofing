<?php

// app/Services/CsvGeneratorService.php

namespace App\Services;

use App\Models\Report;
use Carbon\Carbon;

class CsvGeneratorService
{
    public function generate($data, Report $report): string
    {
        $now = Carbon::now()->format('Y-m-d_H-i-s');
        $filePath = 'storage/reports/' . $report->report_view . '_' . $now . '.csv';
        $file = fopen(public_path($filePath), 'w');

        // Get headers from report fields
        $headers = array_map('trim', explode(',', $report->report_fields));
        fputcsv($file, $headers);

        // Add data rows using the same field structure
        foreach ($data as $row) {
            $rowData = [];
            foreach ($headers as $field) {
                $rowData[] = $row[$field] ?? '';
            }
            fputcsv($file, $rowData);
        }

        fclose($file);
        return $filePath;
    }
}
