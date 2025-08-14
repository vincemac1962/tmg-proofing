<?php

namespace App\Http\Controllers;

use App\Models\Amendment;
use Illuminate\Http\Request;

class AmendmentController extends Controller
{
    public function index($customerId)
    {
        $amendments = Amendment::with(['proof.proofingJob', 'customer'])
            ->where('customer_id', $customerId)
            ->get();

        return view('amendments.index', [
            'amendments' => $amendments,
            'customer_id' => $customerId
        ]);
    }

    public function show(Amendment $amendment)
    {
        return view('amendments.show', compact('amendment'));
    }

    public function create(Request $request)
    {
        return view('amendments.create', [
            'proof_id' => $request->proof_id,
            'customer_id' => $request->customer_id
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'proof_id' => 'required|exists:proofs,id',
            'customer_id' => 'required|exists:customers,id',
            'amendment_notes' => 'required|string',
            'contract_reference' => 'required|string'
        ]);

        Amendment::create($validated);

        return redirect()->route('amendments.index', $validated['customer_id']);
    }

    public function edit(Amendment $amendment)
    {
        return view('amendments.edit', compact('amendment'));
    }

    public function update(Request $request, Amendment $amendment)
    {
        $validated = $request->validate([
            'proof_id' => 'required|exists:proofs,id',
            'customer_id' => 'required|exists:customers,id',
            'amendment_notes' => 'required|string',
            'contract_reference' => 'required|string'
        ]);

        $amendment->update($validated);

        return redirect()->route('amendments.index', $amendment->customer_id);
    }

    public function destroy(Amendment $amendment)
    {
        $customer_id = $amendment->customer_id;
        $amendment->delete();
        return redirect()->route('amendments.index', $customer_id);
    }



}