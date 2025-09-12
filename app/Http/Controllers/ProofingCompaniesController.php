<?php

namespace App\Http\Controllers;

use App\Models\ProofingCompany;
use Illuminate\Http\Request;

class ProofingCompaniesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $showAll = $request->query('show_all', false);
        $query = ProofingCompany::query()->orderBy('name');

        if (!$showAll) {
            $query->where('active', true);
        }

        $proofingCompanies = $query->paginate(10);

        return view('proofing_companies.index', compact('proofingCompanies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('proofing_companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->validateData($request);

        ProofingCompany::create($validatedData);

        return redirect()->route('proofing_companies.index')->with('success', 'Proofing Company created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProofingCompany $proofingCompany)
    {
        return view('proofing_companies.show', compact('proofingCompany'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProofingCompany $proofingCompany)
    {
        return view('proofing_companies.edit', compact('proofingCompany'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProofingCompany $proofingCompany)
    {
        $validatedData = $this->validateData($request);

        $proofingCompany->update($validatedData);

        return redirect()->route('proofing_companies.index')->with('success', 'Proofing Company updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProofingCompany $proofingCompany)
    {
        $proofingCompany->delete();

        return redirect()->route('proofing_companies.index')->with('success', 'Proofing Company deleted successfully.');
    }

    /**
     * Validate incoming request data.
     */
    private function validateData(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:50',
            'address' => 'nullable|string|max:100',
            'telephone_1' => 'required|string|max:75',
            'email_address' => 'required|email|max:50',
            'web_url' => 'required|string|max:100',
            'email_signatory' => 'required|string|max:50',
            'signatory_role' => 'required|string|max:50',
            'company_logo_url' => 'required|max:100',
            'colour_split' => 'required|string|max:25',
            'active' => 'boolean',
        ]);
    }
}
