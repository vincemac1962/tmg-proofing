<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Approval;
use App\Models\Designer;
use App\Models\Customer;
use App\Models\ProofingJob;
use App\Models\Report;
use App\Models\Proof;
use App\Models\Amendment;
use App\Services\DropdownService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\CsvGeneratorService;

class ReportsController extends Controller
{
    protected $csvGenerator;

    public function __construct(CsvGeneratorService $csvGenerator)

    {
        $this->csvGenerator = $csvGenerator;
    }

    public function index()
    {
        $reports = Report::all()->groupBy('report_category')->map(function ($group) {
            return $group->sortBy('report_name'); // Sort records within each group by report_name
        });
        return view('reports.index', compact('reports'));
    }

    public function viewReport($id)
    {
        $report = Report::findOrFail($id);
        // check if the report is customer activity
        if ($report->report_view === 'customer_activity') {
            // return the customer list view
            return $this->customerList(request(), $report);
        } else {
            //
            return $this->{$report->report_view}(request(), $report);
        }
    }


    public function customerList(Request $request, $report = null)
    {
        $query = Customer::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('company_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('contract_reference', 'like', '%' . $searchTerm . '%');
            });
        }

        // Pagination
        $perPage = $request->get('perPage', 10);
        $customers = $query->paginate($perPage)->withQueryString();
        $title = 'Select Customer to View Activity For';
        $reportName = 'Activity for Customer';

        // Get the report object if not provided
        if (!$report) {
            $report = Report::where('report_view', 'customer_list')->first();
        }

        return view('reports.customer_list', compact('customers', 'title', 'report', 'reportName'));
    }

    public function customerReport(Request $request, $customerId, $reportName)
    {
        // Get the customer by ID
        $customer = Customer::findOrFail($customerId);

        // Get the proofing jobs by customer ID
        $proofingJobs = ProofingJob::where('customer_id', $customerId)->get();

        // Get all activities for the customer's proofing jobs
        $activities = $proofingJobs->flatMap(function ($job) {
            return $job->activities->map(function ($activity) {
                $activity->user_name = $activity->user ? $activity->user->name : 'Unknown User';
                return $activity;
            });
        });

        $title = 'Activity for ' . $customer->company_name;

        return view('reports.customer_activity', compact('activities', 'customer', 'proofingJobs', 'title', 'reportName'));
    }

    public function proofs_report(Request $request)
    {
        // set title
        $title = 'Proofs Report';
        // set default start and end dates if not provided
        $startDate = $request->filled('start_date') ? $request->input('start_date') : now()->subDays(28);
        $endDate = $request->filled('end_date') ? $request->input('end_date') : now();

        // Retrieve filters for country and proofing company from the request
        $filterCountry = $request->input('country');
        $filterProofingCompany = $request->input('proofing_company');
        $filterDesigner = $request->input('designer');

        // Retrieve sorting parameters from the request (default to ascending order)
        $sortBy = $request->input('sort_by', null);
        $sortOrder = $request->input('sort_order', 'asc');

        // Fetch dropdown values for countries and proofing companies
        list($countries, $proofingCompanies) = $this->getDropdownValues();
        // fetch designers for the dropdown
        $designers = Designer::all()->pluck('name', 'id')->toArray();

        //
        // Return data based on the filters and sorting
        // Query proofs with filters
        $query = Proof::query()
            ->whereBetween('proof_sent', [$startDate, $endDate])
            ->whereHas('proofingJob.customer', function ($query) use ($filterCountry) {
                if ($filterCountry) {
                    $query->where('customer_country', $filterCountry);
                }
            })
            ->whereHas('proofingJob.proofingCompany', function ($query) use ($filterProofingCompany) {
                if ($filterProofingCompany) {
                    $query->where('name', $filterProofingCompany);
                }
            })
            ->whereHas('proofingJob', function ($query) use ($filterDesigner) {
                if ($filterDesigner) {
                    $query->where('designer_id', $filterDesigner);
                }
            })
            ->select([
                'proofs.id',
                'proofs.proof_sent',
                'customers.contract_reference',
                'customers.company_name as customer_name',
                'customers.customer_country',
                'proofing_companies.name as proofing_company_name',
                'proofs.file_path',
                'designers.name as designer_name',
            ])
            ->join('proofing_jobs', 'proofs.job_id', '=', 'proofing_jobs.id')
            ->join('customers', 'proofing_jobs.customer_id', '=', 'customers.id')
            ->join('proofing_companies', 'proofing_jobs.proofing_company_id', '=', 'proofing_companies.id')
            ->join('designers', 'proofing_jobs.designer_id', '=', 'designers.id');;

        // Apply sorting if provided
        $sortBy = $request->filled('sort_by') ? $request->input('sort_by') : 'id';
        $sortOrder = $request->filled('sort_order') ? $request->input('sort_order') : 'asc';


        // Get all results for CSV
        $allResults = (clone $query)->get();

        // Store in session - used for CSV download
        session(['report_data.proofs_report' => $allResults->toArray()]);

        // Get paginated results for view
        $perPage = $request->get('perPage', 25);
        $proofs = $query->paginate(is_numeric($perPage) && $perPage > 0 ? $perPage : 25);

        // Get the report configuration
        $report = Report::where('report_view', 'proofs_report')->first();


        return view('reports.proofs_report', compact(
            'title',
            'proofs',
            'startDate',
            'endDate',
            'report',
            'filterCountry',
            'filterProofingCompany',
            'filterDesigner',
            'sortBy',
            'sortOrder',
            'countries',
            'proofingCompanies',
            'designers',
            'startDate',
            'endDate',
        ));
    }

    public function amendments_report(Request $request)
    {
        // set title
        $title = 'Amendments Report';
        // Set default start and end dates independently if not provided
        $startDate = $request->filled('start_date') ? $request->input('start_date') : now()->subDays(28);
        $endDate = $request->filled('end_date') ? $request->input('end_date') : now();

        // Retrieve filters for country, proofing company, and designer
        $filterCountry = $request->input('country');
        $filterProofingCompany = $request->input('proofing_company');
        $filterDesigner = $request->input('designer');

        // Retrieve sorting parameters from the request (default to id and ascending order)
        $sortBy = $request->filled('sort_by') ? $request->input('sort_by') : 'id';
        $sortOrder = $request->filled('sort_order') ? $request->input('sort_order') : 'asc';

        // Fetch dropdown values for countries, proofing companies, and designers
        list($countries, $proofingCompanies) = $this->getDropdownValues();
        $designers = Designer::all()->pluck('name', 'id')->toArray();

        // Query amendments with filters
        $query = Amendment::query()
            ->whereBetween('amendments.updated_at', [$startDate, $endDate])
            ->whereHas('proof.proofingJob.customer', function ($query) use ($filterCountry) {
                if ($filterCountry) {
                    $query->where('customer_country', $filterCountry);
                }
            })
            ->whereHas('proof.proofingJob.proofingCompany', function ($query) use ($filterProofingCompany) {
                if ($filterProofingCompany) {
                    $query->where('name', $filterProofingCompany);
                }
            })
            ->whereHas('proof.proofingJob', function ($query) use ($filterDesigner) {
                if ($filterDesigner) {
                    $query->where('designer_id', $filterDesigner);
                }
            })
            ->select([
                'amendments.id',
                'amendments.updated_at as amendment_date',
                'proofs.id as proof_id',
                'proofing_jobs.id as proofing_job_id',
                'customers.contract_reference',
                'customers.company_name as customer_name',
                'customers.customer_country',
                'proofing_companies.name as proofing_company_name',
                'amendments.amendment_notes',
                'designers.name as designer_name',
            ])
            ->join('proofs', 'amendments.proof_id', '=', 'proofs.id')
            ->join('proofing_jobs', 'proofs.job_id', '=', 'proofing_jobs.id')
            ->join('proofing_companies', 'proofing_jobs.proofing_company_id', '=', 'proofing_companies.id')
            ->join('customers', 'proofing_jobs.customer_id', '=', 'customers.id')
            ->join('designers', 'proofing_jobs.designer_id', '=', 'designers.id');

        // Get all results for CSV
        $allResults = (clone $query)->get();

        // Apply sorting
        $query->orderBy($sortBy, $sortOrder);

        // Store in session with report key - used for CSV download
        session(['report_data.amendments_report' => $allResults->toArray()]);


        // Get paginated results for view
        $perPage = $request->get('perPage', 25);
        $amendments = $query->paginate(is_numeric($perPage) && $perPage > 0 ? $perPage : 25);

        // Get the report configuration
        $report = Report::where('report_view', 'amendments_report')->first();

        return view('reports.amendments_report', compact(
            'title',
            'amendments',
            'startDate',
            'endDate',
            'filterCountry',
            'filterProofingCompany',
            'filterDesigner',
            'sortBy',
            'sortOrder',
            'countries',
            'proofingCompanies',
            'designers',
            'startDate',
            'endDate',
            'report',
        ));
    }

    public function approvals_report(Request $request)
    {
        // Set report title
        $title = 'Approvals Report';
        // Set default start and end dates if not provided
        if (!$request->has('start_date') || !$request->has('end_date')) {
            $startDate = now()->subWeek()->format('Y-m-d'); // One week ago
            $endDate = now()->format('Y-m-d'); // Today's date
        } else {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
        }

        // Retrieve filters for country, proofing company, and designer
        $filterCountry = $request->input('country');
        $filterProofingCompany = $request->input('proofing_company');
        $filterDesigner = $request->input('designer');

        // Retrieve sorting parameters from the request (default to ascending order)

        $sortBy = $request->input('sort_by', 'approval_date');
        $sortOrder = $request->input('sort_order', 'asc');

        // Fetch dropdown values for countries, proofing companies, and designers
        list($countries, $proofingCompanies) = $this->getDropdownValues();
        $designers = Designer::all()->pluck('name', 'id')->toArray();

        // Query approvals with filters
        $query = Approval::query()
            ->whereBetween('approvals.updated_at', [$startDate, $endDate])
            ->whereHas('proof.proofingJob.customer', function ($query) use ($filterCountry) {
                if ($filterCountry) {
                    $query->where('customer_country', $filterCountry);
                }
            })
            ->whereHas('proof.proofingJob.proofingCompany', function ($query) use ($filterProofingCompany) {
                if ($filterProofingCompany) {
                    $query->where('name', $filterProofingCompany);
                }
            })
            ->whereHas('proof.proofingJob', function ($query) use ($filterDesigner) {
                if ($filterDesigner) {
                    $query->where('designer_id', $filterDesigner);
                }
            })
            ->select([
                'approvals.id',
                'approvals.approved_at as approval_date',
                'approvals.contract_reference',
                'proofs.id as proof_id',
                'customers.company_name as customer_name',
                'customers.customer_country',
                'proofing_jobs.id as proofing_job_id',
                'proofing_companies.name as proofing_company_name',
                'approvals.approved_by',
                'designers.name as designer_name',
            ])
            ->join('proofs', 'approvals.proof_id', '=', 'proofs.id')
            ->join('proofing_jobs', 'proofs.job_id', '=', 'proofing_jobs.id')
            ->join('proofing_companies', 'proofing_jobs.proofing_company_id', '=', 'proofing_companies.id')
            ->join('customers', 'proofing_jobs.customer_id', '=', 'customers.id')
            ->join('designers', 'proofing_jobs.designer_id', '=', 'designers.id');

        // Apply sorting
        if ($sortBy && $sortOrder) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('approval_date', 'asc'); // Default sorting
        }

        // Get all results for CSV
        $allResults = (clone $query)->get();


        // Store in session with correct key
        session(['report_data.approvals_report' => $allResults->toArray()]);

        // Get paginated results for view
        $perPage = $request->get('perPage', 25);
        $approvals = $query->paginate(is_numeric($perPage) && $perPage > 0 ? $perPage : 25);

        // Get the report configuration
        $report = Report::where('report_view', 'approvals_report')->first();

        return view('reports.approvals_report', compact(
            'title',
            'approvals',
            'startDate',
            'endDate',
            'report',
            'filterCountry',
            'filterProofingCompany',
            'filterDesigner',
            'sortBy',
            'sortOrder',
            'countries',
            'proofingCompanies',
            'designers',
            'startDate',
            'endDate',
        ));
    }

    public function getDropdownValues()
    {
        $dropdownService = app(DropdownService::class);
        return $dropdownService->getDropdownValues();
    }

    // Method for CSV on demand download
    public function downloadCsv($report_view)
    {
        $data = session("report_data.{$report_view}");
        if (!$data) {
            return back()->with('error', 'No report data found. Please run the report again.');
        }

        $report = Report::where('report_view', $report_view)->first();
        if (!$report) {
            return back()->with('error', 'Report configuration not found.');
        }
        $title = $report->report_view;
        $csvFilePath = $this->csvGenerator->generate($data, $title, $report);
        return response()->download(public_path($csvFilePath))->deleteFileAfterSend(true);
    }

}
