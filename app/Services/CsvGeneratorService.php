<?php

// app/Services/CsvGeneratorService.php

namespace App\Services;

use App\Models\Report;
use Carbon\Carbon;


class CsvGeneratorService
{
    // app/Services/CsvGeneratorService.php

    // In CsvGeneratorService
    public function generateAndStoreCsv($data, $title, $report = null, $headers = [], $fields = [])
    {
        // if passsed report, use its fields as headers and fields
        if ($report) {
            $headers = $report->report_fields;
            $fields = $headers;
            //otherwise if headers are passed, use them as fields too
        } elseif (empty($headers) && !empty($data)) {
            $headers = array_keys($data[0]);
            $fields = $headers;
        }
        return $this->generate($data, $title, $report, $headers, $fields);
    }


    public function generate($data, string $title = null, Report $report = null, array $headers = [], array $fields = []): string
    {
        $now = Carbon::now()->format('Y-m-d_H-i-s');
        $baseTitle = $title ? preg_replace('/[^A-Za-z0-9_\-]/', '_', $title) : 'report';
        $filePath = 'storage/reports/' . $baseTitle . '_' . $now . '.csv';
        $file = fopen(public_path($filePath), 'w');

        // Determine headers and fields
        if ($report) {
            $headers = array_map('trim', explode(',', $report->report_fields));
            $fields = $headers;
        }

        fputcsv($file, $headers);

        foreach ($data as $row) {
            $rowData = [];
            foreach ($fields as $field) {
                $rowData[] = $row[$field] ?? '';
            }
            fputcsv($file, $rowData);
        }

        fclose($file);
        return $filePath;
    }
}

