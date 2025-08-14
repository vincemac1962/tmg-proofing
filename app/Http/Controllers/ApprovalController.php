<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index(Request $request)
    {
        $query = Approval::query();

        if ($request->start_date) {
            $query->where('approved_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->where('approved_at', '<=', $request->end_date);
        }

        return view('approvals.index', [
            'approvals' => $query->paginate(25)
        ]);
    }

    public function show(Approval $approval)
    {
        return view('approvals.show', compact('approval'));
    }

    public function create()
    {
        return view('approvals.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'proof_id' => 'required|exists:proofs,id',
            'customer_id' => 'required|exists:customers,id',
            'approved_at' => 'required|date',
            'approved_by' => 'required|string|max:50'
        ]);

        Approval::create($validated);
        return redirect()->route('approvals.index');
    }

    public function edit(Approval $approval)
    {
        return view('approvals.edit', compact('approval'));
    }

    public function update(Request $request, Approval $approval)
    {
        $validated = $request->validate([
            'proof_id' => 'required|exists:proofs,id',
            'customer_id' => 'required|exists:customers,id',
            'approved_at' => 'required|date',
            'approved_by' => 'required|string|max:50'
        ]);

        $approval->update($validated);
        return redirect()->route('approvals.index');
    }

    public function destroy(Approval $approval)
    {
        $approval->delete();
        return redirect()->route('approvals.index');
    }
}