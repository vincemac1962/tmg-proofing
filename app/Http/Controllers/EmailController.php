<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\ProofingJob;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has(['start_date', 'end_date'])) {
            $emails = Email::whereBetween('sent_at', [$request->start_date, $request->end_date])->get();
        } elseif ($request->has('job_id')) {
            $emails = Email::where('job_id', $request->job_id)->get();
        } else {
            $emails = Email::all();
        }

        return view('emails.index', compact('emails'));
    }

    public function show(Email $email)
    {
        return view('emails.show', compact('email'));
    }

    public function create(Request $request)
    {
        $jobId = $request->input('job_id');
        $proofingJob = ProofingJob::find($jobId);

        if (!$proofingJob) {
            abort(404, 'Proofing job not found.');
        }

        $recipientEmail = $proofingJob->customer->user->email;

        return view('emails.create', [
            'job_id' => $jobId,
            'recipient_email' => $recipientEmail,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_id' => 'required|exists:proofing_jobs,id',
            'recipient_email' => 'required|email',
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);

        $proofingJob = ProofingJob::findOrFail($validated['job_id']);
        $validated['customer_id'] = $proofingJob->customer_id; // Add customer_id

        Email::create($validated);

        return redirect()->route('emails.index');
    }

    public function edit(Email $email)
    {
        return view('emails.edit', compact('email'));
    }

    public function update(Request $request, Email $email)
    {
        $validated = $request->validate([
            'job_id' => 'required|exists:proofing_jobs,id',
            'recipient_email' => 'required|email',
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);

        $email->update($validated);

        return redirect()->route('emails.index');
    }

    public function destroy(Email $email)
    {
        $email->delete();

        return redirect()->route('emails.index');
    }
}