<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Amendment;
use App\Models\Approval;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\Proof;
use App\Models\ProofingCompany;
use App\Models\ProofingJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use getID3;
use Illuminate\Support\Facades\Mail;
use App\Mail\AmendmentRequestEmail;
use App\Mail\ApprovalEmail;

class CustomerController extends Controller
{
    public function index(Request $request)
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

        // Filter by country
        if ($request->filled('customer_country')) {
            $query->where('customer_country', $request->customer_country);
        }

        // Sorting
        $sortField = $request->get('sort', 'company_name');
        $sortOrder = $request->get('order', 'asc');
        $query->orderBy($sortField, $sortOrder);

        // Pagination
        $perPage = $request->get('perPage', 10);
        $customers = $query->paginate($perPage)->withQueryString();

        // Get unique countries and sort them
        $countries = Customer::select('customer_country')
            ->distinct()
            ->pluck('customer_country')
            ->filter()
            ->sort()
            ->values()
            ->toArray();

        return view('customers.index', compact('customers', 'countries'));
    }

    // Collect list of countries from the customers table
    public function getCountries()
    {
        return Customer::select('customer_country')
            ->distinct()
            ->pluck('customer_country')
            ->filter()
            ->sort()
            ->values()
            ->toArray();
    }

    public function create()
    {
        $countries = $this->getCountries();
        return view('customers.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'contract_reference' => 'required|string|max:20|unique:customers',
            'customer_city' => 'nullable|string|max:20',
            'customer_country' => 'nullable|string|max:20',
            'contact_number' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'customer_email' => 'required|string|email|max:255|unique:users,email',
            'customer_password' => 'required|string|min:8',
            'additional_pocs' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value !== null && trim($value) !== '') {
                        $emails = array_map('trim', explode(',', $value));
                        foreach ($emails as $email) {
                            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $fail('The Additional POCs field must contain valid email addresses separated by commas.');
                                break;
                            }
                        }
                    }
                }
            ],
        ]);

        $user = User::create([
            'name' => $request->customer_name,
            'email' => $request->customer_email,
            'password' => Hash::make($request->customer_password), // Hash the password
            'role' => 'customer',
        ]);


        $customer = Customer::create([
            'user_id' => $user->id,
            'customer_name' => $request->customer_name,
            'company_name' => $request->company_name,
            'contract_reference' => $request->contract_reference,
            'customer_city' => $request->customer_city,
            'customer_country' => $request->customer_country,
            'contact_number' => $request->contact_number,
            'plain_password' => $request->customer_password, // Store plain password
            'notes' => $request->notes,
            'additional_pocs' => $request->additional_pocs,
        ]);


        return redirect()->route('customers.confirm', $customer->id)
            ->with('success', 'Customer created successfully.');

    }

    public function show($id)
    {
        $customer = Customer::with('proofingJobs.designer', 'proofingJobs.proofingCompany')->findOrFail($id);
        return view('customers.show', compact('customer'));
    }

    public function edit($id)
    {
        $customer = Customer::with('user:id,name,email')->findOrFail($id);

        // Map user fields to customer_name and customer_email
        $customer->customer_name = $customer->user->name;
        $customer->customer_email = $customer->user->email;

        // Get countries for dropdown
        $countries = $this->getCountries();

        return view('customers.edit', compact('customer', 'countries'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'contract_reference' => 'required|string|max:20|unique:customers,contract_reference,' . $id,
            'customer_city' => 'nullable|string|max:20',
            'customer_country' => 'nullable|string|max:20',
            'contact_number' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'customer_password' => 'nullable|string|min:8',
            'additional_pocs' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value !== null && trim($value) !== '') {
                        $emails = array_map('trim', explode(',', $value));
                        foreach ($emails as $email) {
                            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $fail('The additional_pocs field must contain valid email addresses separated by commas.');
                                break;
                            }
                        }
                    }
                }
            ],
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($request->except(['customer_password']));

        $user = $customer->user;
        $user->update([
            'name' => $request->customer_name,
            'email' => $request->customer_email,
        ]);

        // Check if the password has been changed
        if ($request->filled('customer_password')) {
            $user->update([
                'password' => Hash::make($request->customer_password),
            ]);

            $customer->update([
                'plain_password' => $request->customer_password, // Update plain password
            ]);
        }

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        //$customer = Customer::findOrFail($id);
        //dd($customer);
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    public function confirm(Customer $customer)
    {
        return view('customers.confirm', [
            'customer' => $customer,
        ]);
    }

    public function landing()
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $customers = $user->customers()->with('proofingJobs.proofingCompany')->get();

        // Retrieve the proofing company for the highest proofing_company_id
        $proofingCompany = null;
        foreach ($customers as $customer) {
            foreach ($customer->proofingJobs as $proofingJob) {
                $currentCompany = $proofingJob->proofingCompany;
                if ($proofingCompany === null || ($currentCompany && $currentCompany->id > $proofingCompany->id)) {
                    $proofingCompany = $currentCompany;
                }
            }
        }

        $proofingCompanyArray = $proofingCompany->toArray();

        return view('customers.landing', compact('user', 'customers', 'proofingCompany', 'proofingCompanyArray'));
    }

    public function viewProof($id)
    {
        // get the current user
        $user = auth()->user();

        // Retrieve the most recent proof for the given proofing_jobs.id
        $latestProof = Proof::where('job_id', $id)->latest()->first();

        if (!$latestProof) {
            abort(404, 'No proof found for the given job.');
        }

        $proofingJob = ProofingJob::with('customer', 'proofingCompany')->findOrFail($id);

        $videoDimensions = null;

        if ($latestProof->file_path) {
            $filePath = storage_path('app/public/' . $latestProof->file_path);

            if (file_exists($filePath)) {
                $getID3 = new getID3();
                $fileInfo = $getID3->analyze($filePath);

                if (isset($fileInfo['video']['resolution_x']) && isset($fileInfo['video']['resolution_y'])) {
                    $videoDimensions = [
                        'width' => $fileInfo['video']['resolution_x'],
                        'height' => $fileInfo['video']['resolution_y'],
                    ];
                }
            }
        }

        // Add a record to the activities table
        Activity::create([
            'job_id' => $proofingJob->id,
            'user_id' => auth()->id(),
            'activity_type' => 'proof viewed',
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'notes' => null,
        ]);

        $proofingCompany = $proofingJob->proofingCompany;

        $proofingCompanyArray = $proofingCompany->toArray();

        return view('customers.view_proof', compact('proofingJob', 'proofingCompany', 'proofingCompanyArray', 'latestProof', 'videoDimensions', 'user'));
    }

    public function submitAmendment(Request $request)
    {
        $request->validate([
            'amendment_notes' => 'required|string',
            'proof_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'proofing_job_id' => 'required|integer',
            'contract_reference' => 'required|string',
        ]);

        // Create the Amendment record
        Amendment::create($request->only('amendment_notes', 'proof_id', 'customer_id', 'contract_reference'));

        // Update the ProofingJob status
        $proofingJob = ProofingJob::findOrFail($request->proofing_job_id);
        $proofingJob->update(['status' => 'awaiting amendment']);

        // Add an Activity record
        Activity::create([
            'job_id' => $proofingJob->id,
            'user_id' => auth()->id(),
            'activity_type' => 'amendment requested',
            'ip_address' => $_SERVER['REMOTE_ADDR'],
        ]);

        // Get the proofing company
        $proofingCompany = $proofingJob->proofingCompany;

        // Prepare email details
        $designerEmail = $proofingJob->designer->email;
        $contactEmail = ProofingCompany::where('id', $proofingCompany->id)->value('email_address');
        $recipients = [$designerEmail, $contactEmail];

        $subject = "Amendment Requested for {$proofingJob->contract_reference} - {$proofingJob->customer->company_name}";
        $data = [
            'amendment_notes' => $request->amendment_notes,
            'proofingJob' => $proofingJob,
        ];
        //  queue the email
        // Mail::send('emails.amendment_email', $data, function ($message) use ($recipients, $subject) {
        //    $message->to($recipients)
        //        ->subject($subject)->bcc('vince.macrae@gmail.com');
        //});

        Mail::to($recipients)
            ->bcc('vince.macrae@gmail.com')
            ->queue(new AmendmentRequestEmail($proofingJob, $request->amendment_notes, $subject));



        return redirect()->route('customers.landing')->with('success', 'Your amendments have been submitted successfully. A revised proof will be prepared and emailed to you.');
    }

    public function submitApproval(Request $request)
    {
        $request->validate([
            'approved_by' => 'required|string',
            'proof_id' => 'required|integer',
            'customer_id' => 'required|integer',
        ]);

        // Create the Approval record
        Approval::create($request->only('approved_by', 'proof_id', 'customer_id'));

        // Update proofing job status
        $proofingJob = ProofingJob::with('customer', 'designer')->findOrFail($request->proofing_job_id);
        $proofingJob->update(['status' => 'approved']);

        // Add an Activity record
        Activity::create([
            'job_id' => $proofingJob->id,
            'user_id' => auth()->id(),
            'activity_type' => 'proof approved',
            'ip_address' => $_SERVER['REMOTE_ADDR'],
        ]);

        // Get the proofing company
        $proofingCompany = $proofingJob->proofingCompany;

        // Prepare email details
        $designerEmail = $proofingJob->designer->email;
        $contactEmail = ProofingCompany::where('id', $proofingCompany->id)->value('email_address');
        $recipients = [$designerEmail, $contactEmail];

        $subject = "Approval Received for {$proofingJob->contract_reference} - {$proofingJob->customer->company_name}";
        $data = [
            'proofingJob' => $proofingJob,
            'approved_by' => $request->approved_by,
            // date and time approved
            'approved_at' => now()->format('H:i:s d-m-Y'),
        ];

        // Queue the email
        Mail::to($recipients)
            ->queue(new ApprovalEmail($proofingJob, $request->approved_by, $data['approved_at'], $subject));


        return redirect()->route('customers.landing')->with('success', 'Thank you for your approval. This has been sent to our scheduling department who will send your advertisement live at your chosen site(s) at the next available update.');
    }

    public function downloadProof($id)
    {
        $proofingJob = ProofingJob::with('proofs')->findOrFail($id);
        $latestProof = $proofingJob->proofs->first();

        if ($latestProof && $latestProof->file_path) {
            $filePath = storage_path('app/public/' . $latestProof->file_path);

            if (file_exists($filePath)) {
                // Log the activity
                Activity::create([
                    'job_id' => $proofingJob->id,
                    'user_id' => auth()->id(),
                    'activity_type' => 'proof downloaded',
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                    'notes' => null, // Optional, can be customized
                ]);

                // Serve the file for download
                return response()->download($filePath);
            }
        }

        abort(404, 'File not found.');
    }


}
