<?php

namespace App\Http\Controllers;

use App\Mail\CustomerReminderMail;
use App\Models\Activity;
use App\Models\Proof;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use \App\Services\DropdownService;
use \App\Services\CsvGeneratorService;

class ReminderController extends Controller
{
    protected $csvGenerator;

    public function __construct(CsvGeneratorService $csvGenerator)
    {
        $this->csvGenerator = $csvGenerator;
    }
    public function index(Request $request)
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

        // Fetch dropdown values for countries and proofing companies
        list($countries, $proofingCompanies) = $this->getDropdownValues();

        // Build the query to fetch reminders based on filters and sorting
        $query = $this->buildReminderQuery($chosenDate, $filterCountry, $filterProofingCompany, $sortBy, $sortOrder);




        // Paginate the query results manually
        $perPage = $request->get('perPage', 25);
        $currentPage = $request->input('page', 1); // Current page number
        $total = count($query); // Total number of items
        $reminders = new LengthAwarePaginator(
            array_slice($query, ($currentPage - 1) * $perPage, $perPage), // Slice the query results for the current page
            $total, // Total number of items
            $perPage, // Items per page
            $currentPage, // Current page number
            ['path' => $request->url(), 'query' => $request->query()] // Pagination links
        );

        // create session data from the query results for use in other methods
        $csvData = array_map(function ($item) {
            return [
                'Job ID' => $item->job_id,
                'Company' => $item->company_name,
                'Country' => $item->customer_country,
                'Proofing Company' => $item->proofing_company_name,
                'Activity Type' => $item->activity_type,
                'Last Activity Date' => $item->activity_updated_at,
            ];
        }, $reminders->items());

        $request->session()->put('reminder_query', $csvData);
        $request->session()->put('reminder_title', 'reminders_due');


        // Return the view with reminders, filters, sorting, and the CSV download link
        return view('reminders.index', [
            'reminders' => $reminders, // Paginated reminders
            'title' => 'Reminders', // Page title
            'countries' => $countries, // Dropdown values for countries
            'proofingCompanies' => $proofingCompanies, // Dropdown values for proofing companies
            'formattedDate' => Carbon::parse($chosenDate)->format('d-m-Y'), // Formatted chosen date
            'sort_by' => $sortBy, // Current sorting column
            'sort_order' => $sortOrder, // Current sorting order
        ]);
    }

        public function downloadCsv(Request $request)
    {
        $data = $request->session()->get('reminder_query', []);
        if (empty($data)) {
            return redirect()->back()->with('error', 'No data available to generate CSV.');
        }
        $title = session('reminder_title');
        $csvPath = $this->csvGenerator->generateAndStoreCsv($data, $title);
        return response()->download(public_path($csvPath))->deleteFileAfterSend(true);
    }

    public function showReminderHistory(Request $request)
    {
        // Get default date range (last 30 days)
        $endDate = Carbon::parse($request->input('end_date', now()->format('Y-m-d')));
        $startDate = Carbon::parse($request->input('start_date', now()->subDays(30)->format('Y-m-d')));

        // Get filters from request
        $filterCountry = $request->input('country');
        $filterProofingCompany = $request->input('proofing_company');
        $perPage = $request->get('perPage', 25);

        // Build query for reminder activities with eager loading
        $query = Activity::query()
            ->select(
                'activities.*',
                DB::raw('DATE(DATE_SUB(activities.updated_at, INTERVAL WEEKDAY(activities.updated_at) DAY)) as week_start')
            )
            ->where('activity_type', 'like', '%reminder%')
            ->where('updated_at', '>=', $startDate)
            ->where('updated_at', '<=', $endDate . ' 23:59:59') // Include the entire end date
            ->with([
                'proofingJob.customer', // Eager load proofing job and customer
                'user', // Eager load user
                'proofingJob.proofingCompany' // Eager load proofing company
            ]);

        // Apply filters if present
        if ($filterCountry) {
            $query->whereHas('proofingJob.customer', function ($q) use ($filterCountry) {
                $q->where('customer_country', $filterCountry);
            });
        }

        if ($filterProofingCompany) {
            $query->whereHas('proofingJob', function ($q) use ($filterProofingCompany) {
                $q->whereHas('proofingCompany', function ($q) use ($filterProofingCompany) {
                    $q->where('name', $filterProofingCompany);
                });
            });
        }

        // Group and order by week
        $reminders = $query->orderBy('week_start', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage);

        // Convert Eloquent models to arrays
        $reminderItems = array_map(function ($item) {
            return $item->toArray();
        }, $reminders->items());

        // Convert Eloquent models to arrays
        $reminderItems = array_map(function ($item) {
            return $item->toArray();
        }, $reminders->items());

        // Create a CSV-friendly array from converted models
        $csvData = array_map(function ($item) {
            return [
                'Job ID' => $item['job_id'],
                'Updated' => Carbon::parse($item['updated_at'])->format('d-M-Y H:i'),
                'Company' => $item['proofing_job']['customer']['company_name'],
                'Country' => $item['proofing_job']['customer']['customer_country'],
                'Proofing Company' => $item['proofing_job']['proofing_company']['name'],
                'Activity Type' => $item['activity_type'],
                'Sent By' => $item['user']['name'] ?? 'N/A',
            ];
        }, $reminderItems);

        $request->session()->put('reminder_query', $csvData);
        $request->session()->put('reminder_title', 'reminder_history');



        // Get dropdown values
        list($countries, $proofingCompanies) = $this->getDropdownValues();

        return view('reminders.history', [
            'reminders' => $reminders,
            'countries' => $countries,
            'proofingCompanies' => $proofingCompanies,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'title' => 'Reminder History'
        ]);
    }



    // process selected records to send reminders
    public function processReminders(Request $request)
    {
        // extract IDs from request
        $selectedIds = $request->input('selected_ids', []);
        if (empty($selectedIds)) {
            return redirect()->back()->with('error', 'No reminders selected.');
        }
        // Validate selected IDs
        $selectedIds = $this->validateSelectedIds($selectedIds);
        if (empty($selectedIds)) {
            return redirect()->back()->with('error', 'No valid reminders selected.');
        }

        // Process each selected reminder
        foreach ($selectedIds as $id) {
            // extract the last/latest proof ID from the job ID
            $proofId = DB::table('proofs')
                ->join('proofing_jobs', 'proofs.job_id', '=', 'proofing_jobs.id')
                ->where('proofing_jobs.id', $id)
                ->orderBy('proofs.created_at', 'desc')
                ->value('proofs.id');
            // send reminder email
            $this->sendReminder($proofId);
            // Create an activity record for the reminder sent
            DB::table('activities')->insert([
                'job_id' => $id,
                'user_id' => auth()->id(),
                'activity_type' => 'reminder sent',
                'ip_address' => request()->ip(),
                'notes' => 'Reminder sent for job ID: ' . $id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', count($selectedIds) . ' reminders processed successfully.');
    }

    // send reminder emails to selected jobs
    public function sendReminder($proofId)
    {
        {
            try {
                $proof = Proof::with('proofingJob.customer.user', 'proofingJob.designer')->findOrFail($proofId);
                if (!$proof->proofingJob->customer || !$proof->proofingJob->customer->user) {
                    return redirect()->route('proofs.confirm', $proofId)
                        ->withErrors(['error' => 'Customer or user information is missing for this proof.']);
                }

                // Retrieve the proofing company associated with the proofing job
                $proofingCompany = $proof->proofingJob->proofingCompany;

                $data = [
                    'subject' => 'Your Advertisement Proof',
                    'recipient_name' => $proof->proofingJob->customer->user->name,
                    'recipient_email' => $proof->proofingJob->customer->user->email,
                    'recipient_password' => $proof->proofingJob->customer->plain_password,
                    'advert_location' => $proof->proofingJob->advert_location,
                    'contract_reference' => $proof->proofingJob->contract_reference,
                    'company_name' => $proof->proofingJob->customer->company_name,
                    'notes' => $proof->notes,
                    'proofingCompany' => $proofingCompany,
                ];
                try {
                    //Mail::to($data['recipient_email'])->send(new CustomerReminderMail($data));
                    Mail::to($data['recipient_email'])->queue(new CustomerReminderMail($data));
                    $proof->update(['proof_sent' => now()]);
                    echo "Reminder email sent to: " . $data['recipient_email'] . "\n";
                    // Log successful email sending
                    Log::info('Reminder email sent successfully to: ' . $data['recipient_email']);

                } catch (\Exception $e) {
                    Log::error('Error in sendReminder method for proof ID: ' . $proofId . '. Error: ' . $e->getMessage());
                    return redirect()->route('reminders.index', $proofId)
                        ->withErrors(['error' => 'Failed to send the reminder email. ' . $e->getMessage()]);
                }
                // Update the proofing job status to 'proof emailed'
                $proof->update(['proof_sent' => now()]);


            } catch (\Exception $e) {
                Log::error('Error sending reminder email: ' . $e->getMessage());
                //dd($data); // Dump and die to display the contents of $data


                return redirect()->route('reminders.index', $proofId)
                    ->withErrors(['error' => 'Failed to send the reminder email. ' . $e->getMessage()]);
            }

        }
    }

    public function validateSelectedIds(array $selectedIds): array
    {
        // Validate that selected IDs are an array of integers
        if (!is_array($selectedIds) || !array_reduce($selectedIds, fn($carry, $id) => $carry && is_numeric($id), true)) {
            return [];
        }

        $selectedIds = array_map('intval', $selectedIds); // Convert all values to integers

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
        AND a.activity_type NOT IN ('proof approved', 'reminder sent')
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
        $dropdownService = app(DropdownService::class);
        return $dropdownService->getDropdownValues();
    }

}


