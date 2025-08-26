<?php

namespace App\Http\Controllers;

use App\Models\Designer;
use App\Models\ProofingCompany;
use App\Models\ProofingJob;
use App\Models\Customer;
use App\Mail\CustomerLoginMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class ProofingJobController extends Controller
{
    public function index($customerId)
    {
        $customer = Customer::findOrFail($customerId);
        $proofingJobs = ProofingJob::where('customer_id', $customerId)->get();
        return view('proofing_jobs.index', compact('proofingJobs', 'customer'));
    }

    public function create(Request $request, $customerId)
    {
        $customer = Customer::findOrFail($customerId);
        $designers = Designer::all();
        $proofingCompanies = ProofingCompany::where('active', true)->orderBy('name', 'asc')->get();

        return view('proofing_jobs.create', [
            'customerId' => $customer->id,
            'company_name' => $request->input('company_name', $customer->company_name),
            'customer_city' => $request->input('customer_city', $customer->customer_city),
            'customer_country' => $request->input('customer_country', $customer->customer_country),
            'contract_reference' => $request->input('contract_reference', $customer->contract_reference),
            //  ToDo: Don't allow default Proofing Company to be passed
            'designers' => $designers,
            'proofingCompanies' => $proofingCompanies,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'designer_id' => 'required|exists:designers,id',
            'proofing_company_id' => 'required|exists:proofing_companies,id',
            'contract_reference' => 'required|string|max:20',
            'advert_location' => 'required|string|max:255',
        ]);

        $proofingJob = ProofingJob::create([
            'customer_id' => $validatedData['customer_id'],
            'designer_id' => $validatedData['designer_id'],
            'proofing_company_id' => $validatedData['proofing_company_id'],
            'contract_reference' => $validatedData['contract_reference'],
            'advert_location' => $validatedData['advert_location'],
            'description' => $request->description,
            'status' => 'pending',
        ]);

        $customer = Customer::findOrFail($validatedData['customer_id']);
        $companyName = $customer->company_name;

        return redirect()->route('proofing_jobs.confirm', [
            'customerId'    => $customer->id,
            'proofingJob' => $proofingJob->id,
            ])->with('success', 'Proofing job created successfully.');
    }

    public function showSendProof($customerId, $proofingJobId)
    {
        $proofingJob = ProofingJob::findOrFail($proofingJobId);
        $customer = Customer::findOrFail($customerId);
        $proofingCompany = $proofingJob->proofingCompany;

        return view('proofing_jobs.confirm', [
            'proofingJob' => $proofingJob,
            'customer' => $customer,
            'proofingCompany' => $proofingCompany,
        ]);
    }

    public function show($id)
    {
        $proofingJob = ProofingJob::with('proofs', 'customer')->findOrFail($id);
        // get count of proofs
        $proofCount = $proofingJob->proofs()->count();
        // pass proofing_jobs and proof count to view
        return view('proofing_jobs.show', [
            'proofingJob' => $proofingJob,
            'customer' => $proofingJob->customer,
            'proofCount' => $proofCount,
        ]);
    }

    public function edit($customerId, $proofingJob)
    {
        $customer = Customer::findOrFail($customerId);
        $proofingJob = ProofingJob::findOrFail($proofingJob);
        $proofingCompanies = ProofingCompany::where('active', true)->get();

        return view('proofing_jobs.edit', compact('proofingJob', 'customer', 'proofingCompanies'));
    }

    public function update(Request $request, $customerId, $proofingJob)
    {
        $validatedData = $request->validate([
            'proofing_company_id' => 'required|exists:proofing_companies,id',
            'contract_reference' => 'required|string|max:20',
        ]);

        $proofingJob = ProofingJob::findOrFail($proofingJob);
        $proofingJob->update($validatedData + ['description' => $request->description]);

        return redirect()->route('customers.show', $customerId)
            ->with('success', 'Proofing job updated successfully.');
    }

    public function destroy($customerId, $proofingJob)
    {
        $proofingJob = ProofingJob::findOrFail($proofingJob);
        $proofingJob->delete();

        return redirect()->route('customers.show', $customerId)
            ->with('success', 'Proofing job deleted successfully.');
    }

    public function sendLoginEmail($proofingJobId, $loginId, $password)
    {
        $proofingJob = ProofingJob::findOrFail($proofingJobId);
        $customer = $proofingJob->customer;
        $proofingCompany = $proofingJob->proofingCompany;

        Mail::to($customer->email)->send(new CustomerLoginMail($customer, $proofingCompany, $loginId, $password));
    }
}