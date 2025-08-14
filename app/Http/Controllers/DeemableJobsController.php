<?php

namespace App\Http\Controllers;

use App\Mail\CustomerReminderMail;
use App\Models\Activity;
use App\Models\Proof;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class DeemableJobsController extends Controller
{
    public function index(Request $request)
    {

        $parameters = $this->extractRequestParameters($request);
        $chosenDate = $parameters['chosenDate'];
        $filterCountry = $parameters['filterCountry'];
        $filterProofingCompany = $parameters['filterProofingCompany'];
        $sortBy = $parameters['sortBy'];
        $sortOrder = $parameters['sortOrder'];

        // Fetch dropdown values for countries and proofing companies
        list($countries, $proofingCompanies) = $this->getDropdownValues();

        // Build the query to fetch reminders based on filters and sorting
        $query = $this->buildReminderQuery($chosenDate, $filterCountry, $filterProofingCompany, $sortBy, $sortOrder);

        // Paginate the query results manually
        $perPage = 25; // Number of items per page
        $currentPage = $request->input('page', 1); // Current page number
        $total = count($query); // Total number of items
        $deemableJobs = new LengthAwarePaginator(
            array_slice($query, ($currentPage - 1) * $perPage, $perPage), // Slice the query results for the current page
            $total, // Total number of items
            $perPage, // Items per page
            $currentPage, // Current page number
            ['path' => $request->url(), 'query' => $request->query()] // Pagination links
        );

        // Generate a CSV file from the query results
        $csvFilePath = $this->generateCsv($query);

        // Return the view with reminders, filters, sorting, and the CSV download link
        return view('deemable_jobs.index', [
            'deemable_jobs' => $deemableJobs, // Paginated reminders
            'title' => 'Deemable Proofing Jobs', // Page title
            'countries' => $countries, // Dropdown values for countries
            'proofingCompanies' => $proofingCompanies, // Dropdown values for proofing companies
            'formattedDate' => Carbon::parse($chosenDate)->format('d-m-Y H:i'), // Formatted chosen date
            'sort_by' => $sortBy, // Current sorting column
            'sort_order' => $sortOrder, // Current sorting order
            'csvDownloadLink' => url($csvFilePath), // Link to download the generated CSV file
        ]);
    }

    private function extractRequestParameters(Request $request): array
    {
        // Set the default date to 14 days ago and retrieve the chosen date from the request
        $defaultDate = now()->modify('-14 days')->format('Y-m-d');
        $chosenDate = $request->input('chosen_date', $defaultDate);

        // Retrieve filters for country and proofing company from the request
        $filterCountry = $request->input('country');
        $filterProofingCompany = $request->input('proofing_company');

        // Retrieve sorting parameters from the request (default to ascending order)
        $sortBy = $request->input('sort_by', null);
        $sortOrder = $request->input('sort_order', 'asc');

        return compact('chosenDate', 'filterCountry', 'filterProofingCompany', 'sortBy', 'sortOrder');
    }

    private function generateCsv(array $data): string
    {
        // ToDo: On later iteration, refactor this method to use a service class for CSV generation
        // create a string from now() date to be used in the CSV file name
        $now = Carbon::now()->format('Y-m-d_H-i-s');
        $filePath = 'storage/deemable/deemable_jobs_' . $now . '.csv';
        $file = fopen(public_path($filePath), 'w');

        // Add headers
        fputcsv($file, ['Job ID', 'Customer Name', 'Country', 'Time Company', 'Activity Type', 'Updated At']);

        // Add data rows
        foreach ($data as $row) {
            fputcsv($file, [
                $row->job_id,
                $row->company_name,
                $row->customer_country,
                $row->proofing_company_name,
                $row->activity_type,
                $row->activity_updated_at,
            ]);
        }

        fclose($file);

        return $filePath;
    }

    private function buildReminderQuery($chosenDate, $filterCountry, $filterProofingCompany, $sortBy = null, $sortOrder = 'asc')
    {
        $queryString = "
        SELECT a.*, c.*, u.*, pc.*, a.updated_at as activity_updated_at, pc.name as proofing_company_name
        FROM activities a
        JOIN proofing_jobs pj ON a.job_id = pj.id
        JOIN customers c ON pj.customer_id = c.id
        JOIN users u ON c.user_id = u.id
        JOIN proofing_companies pc ON pj.proofing_company_id = pc.id
        WHERE a.updated_at = (
            SELECT MAX(sub.updated_at)
            FROM activities sub
            WHERE sub.job_id = a.job_id
        )
        AND a.activity_type like '%reminder sent%' 
        AND a.updated_at <= ?
    ";

        $params = [$chosenDate];

        if ($filterCountry) {
            $queryString .= " AND c.customer_country = ?";
            $params[] = $filterCountry;
        }

        if ($filterProofingCompany) {
            $queryString .= " AND pc.name = ?";
            $params[] = $filterProofingCompany;
        }

        if ($sortBy) {
            $queryString .= " ORDER BY {$sortBy} {$sortOrder}";
        }

        return DB::select($queryString, $params);
    }

    public function getDropdownValues()
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

    public function processDeemableJobs(Request $request)
    {
        // extract IDs from request
        $selectedIds = $request->input('selected_ids', []);
        if (empty($selectedIds)) {
            return redirect()->back()->with('error', 'No jobs selected.');
        }
        // Validate selected IDs
        $selectedIds = $this->validateSelectedIds($selectedIds);
        if (empty($selectedIds)) {
            return redirect()->back()->with('error', 'No valid jobs selected.');
        }

        // Process each selected reminder
        foreach ($selectedIds as $id) {
            // extract the last/latest proof ID from the job ID
            $proofId = DB::table('proofs')
                ->join('proofing_jobs', 'proofs.job_id', '=', 'proofing_jobs.id')
                ->where('proofing_jobs.id', $id)
                ->orderBy('proofs.created_at', 'desc')
                ->value('proofs.id');

            //get the proofing job record
            $proofingJob = DB::table('proofing_jobs')->where('id', $id)->first();
            // update the proofing job status to 'approved'
            DB::table('proofing_jobs')
                ->where('id', $id)
                ->update(['status' => 'approved', 'updated_at' => now()]);

            // get the current user
            $user = DB::table('users')->where('id', auth()->id())->value('name') ?? 'System';

            // create approval record
            DB::table('approvals')->insert([
                'proof_id' => $proofId,
                'customer_id' => $proofingJob->customer_id,
                'contract_reference' => $proofingJob->contract_reference,
                'approved_at' => now(),
                'approved_by' => 'Admin User - ' . $user,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Log the activity for the job
            DB::table('activities')->insert([
                'job_id' => $id,
                'user_id' => auth()->id(),
                'activity_type' => 'proof approved',
                'ip_address' => request()->ip(),
                'notes' => 'Deemed correct by admin user ' . $user,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $this->showDeemedJobs($request);
    }

    public function validateSelectedIds(array $selectedIds): array
    {
        // Validate that selected IDs are an array of integers
        if (!is_array($selectedIds) || !array_reduce($selectedIds, fn($carry, $id) => $carry && is_numeric($id), true)) {
            return [];
        }

        // Convert all values to integers
        $selectedIds = array_map('intval', $selectedIds);

        // Ensure the IDs are unique
        $selectedIds = array_unique($selectedIds);

        // Ensure the IDs are not empty
        $selectedIds = array_filter($selectedIds, fn($id) => !empty($id));

        // Ensure the IDs are integers
        $selectedIds = array_map('intval', $selectedIds);

        // Check if the IDs exist in the database
        $existingIds = DB::table('proofing_jobs')->whereIn('id', $selectedIds)->pluck('id')->toArray();

        return array_intersect($selectedIds, $existingIds);
    }

    // show deemed jobs with date passed in and pagination
    public function showDeemedJobs(Request $request)
    {
        // Get default date range (last 30 days)
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));

        // Get filters from request
        $filterCountry = $request->input('country');
        $filterProofingCompany = $request->input('proofing_company');
        $perPage = $request->get('perPage', 25);

        // Build query for deemed jobs with week grouping
        $query = DB::table('activities')
            ->select(
                'activities.*',
                'customers.company_name',
                'customers.customer_country',
                'proofing_companies.name as proofing_company_name',
                'activities.updated_at as activity_updated_at',
                DB::raw('DATE(DATE_SUB(activities.updated_at, INTERVAL WEEKDAY(activities.updated_at) DAY)) as week_start')
            )
            ->join('proofing_jobs', 'activities.job_id', '=', 'proofing_jobs.id')
            ->join('customers', 'proofing_jobs.customer_id', '=', 'customers.id')
            ->join('proofing_companies', 'proofing_jobs.proofing_company_id', '=', 'proofing_companies.id')
            ->where('activities.notes', 'like', '%deemed%')
            ->where('activities.updated_at', '>=', $startDate)
            ->where('activities.updated_at', '<=', $endDate . ' 23:59:59'); // Include the entire end date

        // Apply filters if present
        if ($filterCountry) {
            $query->where('customers.customer_country', $filterCountry);
        }

        if ($filterProofingCompany) {
            $query->where('proofing_companies.name', $filterProofingCompany);
        }

        // Group and order by week
        $query->orderBy('week_start', 'desc')
            ->orderBy('activities.updated_at', 'desc');

        // Paginate results
        $deemedJobs = $query->paginate($perPage);

        // Get dropdown values
        list($countries, $proofingCompanies) = $this->getDropdownValues();

        return view('deemable_jobs.deemed_jobs', [
            'deemed_jobs' => $deemedJobs,
            'countries' => $countries,
            'proofingCompanies' => $proofingCompanies,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'title' => 'Deemed Jobs'
        ]);
    }

    private function buildDeemedJobsQuery($chosenDate, $filterCountry, $filterProofingCompany, $sortBy = null, $sortOrder = 'asc')
    {
        $queryString = "
        SELECT a.*, c.*, u.*, pc.name, a.updated_at as activity_updated_at, a.id as activity_id, a.notes as activity_notes, pc.name as proofing_company_name, pj.id as job_id, c.customer_country, c.company_name, a.activity_type
        FROM activities a
        JOIN proofing_jobs pj ON a.job_id = pj.id
            JOIN customers c ON pj.customer_id = c.id
            JOIN users u ON c.user_id = u.id
            JOIN proofing_companies pc ON pj.proofing_company_id = pc.id
            WHERE a.updated_at >= ?              
			AND a.notes LIKE '%admin user%'
    ";

        $params = [$chosenDate];

        if ($filterCountry) {
            $queryString .= " AND c.customer_country = ?";
            $params[] = $filterCountry;
        }

        if ($filterProofingCompany) {
            $queryString .= " AND pc.name = ?";
            $params[] = $filterProofingCompany;
        }

        if ($sortBy) {
            $queryString .= " ORDER BY {$sortBy} {$sortOrder}";
        }

        return DB::select($queryString, $params);
    }




}
