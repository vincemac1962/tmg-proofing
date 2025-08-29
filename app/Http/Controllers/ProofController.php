<?php

namespace App\Http\Controllers;

use App\Mail\CustomerLoginMail;
use App\Models\Activity;
use App\Models\Proof;
use App\Models\ProofingJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProofController extends Controller
{
    public function index()
    {
        $proofs = Proof::with(['proofingJob.customer'])
            ->get()
            ->map(function ($proof) {
                return [
                    'id' => $proof->id,
                    'customer_id' => $proof->proofingJob->customer->id,
                    'contract_reference' => $proof->proofingJob->customer->contract_reference,
                    'customer_name' => $proof->proofingJob->customer->company_name,
                    'job_id' => $proof->proofingJob->id,
                    'customer_id_for_job' => $proof->proofingJob->customer_id,
                    'status' => $proof->proofingJob->status,
                    'file_path' => $proof->file_path,
                    'proof_sent' => $proof->proof_sent
                ];
            });

        return view('proofs.index', compact('proofs'));
    }

    public function show(Proof $proof)
    {
        return view('proofs.show', compact('proof'));
    }

    public function create($jobId, $customerId, Request $request)
    {
        $proofType = $request->input('proof_type', 'uploaded'); // Default proof type
        return view('proofs.create', compact('jobId', 'customerId', 'proofType'));
    }

    public function store(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'proofing_job_id' => 'required|exists:proofing_jobs,id',
            'file' => 'required|file|mimes:mp4|max:10240', // Only accept mp4 files
            'notes' => 'nullable|string|max:255',
        ]);

        // Get the uploaded file
        $file = $request->file('file');
        $newFileName = $this->getName($file);

        // Store the file with the new name
        $path = $file->storeAs('proofs', $newFileName, 'public');

        // Create the proof record
        $proof = Proof::create([
            'job_id' => $validated['proofing_job_id'],
            'file_path' => $path,
            'notes' => $validated['notes'],
        ]);

        // Update the proofing job status and get the customer_id
        $proofingJob = ProofingJob::findOrFail($validated['proofing_job_id']);
        $proofingJob->update(['status' => 'proof uploaded']);
        $customerId = $proofingJob->customer_id;

        // Add an entry to the activities table
        Activity::create([
            'job_id' => $proof->proofingJob->id,
            'user_id' => auth()->id(), // Logged-in user ID
            'activity_type' => 'proof uploaded',
            'ip_address' => request()->ip(), // IP address of the request
        ]);

        // update proofing job status to proof_type passed in by request
        if ($request->has('proof_type')) {
            $proofingJob->update(['status' => $request->input('proof_type')]);
        }

        // Load the proof confirm view
        return view('proofs.confirm', [
            'proof' => $proof,
            'company_name' => $proofingJob->customer->company_name,
            'proofingJob' => $proofingJob,
            'proof_type' => $request->input('proof_type', 'uploaded'), // Pass the proof type to the view
            //'proofingJobId' => $proofingJob->id,
        ]);
    }

    public function edit(Proof $proof)
    {
        return view('proofs.edit', compact('proof'));
    }

    public function update(Request $request, Proof $proof)
    {
        $validated = $request->validate([
            'job_id' => 'required|exists:proofing_jobs,id',
            'file' => 'sometimes|file|mimes:mp4|max:10240', // Ensure file validation matches requirements
        ]);

        if ($request->hasFile('file')) {
            // Delete the old file
            Storage::disk('public')->delete($proof->file_path);

            // Get the uploaded file
            $file = $request->file('file');
            $newFileName = $this->getName($file);

            // Store the file with the new name
            $path = $file->storeAs('proofs', $newFileName, 'public');
            $proof->file_path = $path;
        }

        $proof->save();

        return redirect()->route('proofs.show', $proof)
            ->with('success', 'Proof updated successfully.');
    }

    public function confirm(Proof $proof)
    {
        $proofingJobId = $proof->proofingJob->id; // Retrieve the proofing job ID
        $companyName = $proof->proofingJob->customer->company_name;
        $proofingJob = $proof->proofingJob; // Retrieve the proofing job

        return view('proofs.confirm', [
            'proof' => $proof,
            'company_name' => $companyName,
            'proofingJobId' => $proofingJobId, // Pass the proofing job ID to the view
            'proofingJob' => $proofingJob, // Pass the proofing job to the view
        ]);
    }


    public function sendProofEmail($proofId, $activityType = null)
    {
        try {
            $proof = Proof::with('proofingJob.customer.user', 'proofingJob.designer')->findOrFail($proofId);
            if (!$proof->proofingJob->customer || !$proof->proofingJob->customer->user) {
                return redirect()->route('proofs.confirm', $proofId)
                    ->withErrors(['error' => 'Customer or user information is missing for this proof.']);
            }
            //ToDo: handle case where proofing job is not found or has no status
            if (is_null($activityType)) {
                $activityType = 'proof emailed';
            }

            // Retrieve the proofing company associated with the proofing job
            $proofingCompany = $proof->proofingJob->proofingCompany;

            // set up inline embedding of logo
            $logo = $proofingCompany->company_logo_url;

            /*$imageData = base64_encode(file_get_contents($imagePath));
            $src = 'data: ' . mime_content_type($imagePath) . ';base64,' . $imageData; */


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
                'logo' => $logo,
            ];

            //Mail::to($data['recipient_email'])->send(new CustomerLoginMail($data));
            Mail::to($data['recipient_email'])->queue(new CustomerLoginMail($data));

            Activity::create([
                'job_id' => $proof->proofingJob->id,
                'user_id' => auth()->id(),
                'activity_type' => $activityType === 'amended' ? 'amendment emailed' : $activityType,
                'ip_address' => request()->ip(),
            ]);

            // Update the proofing job status to 'proof emailed'
            $proof->proofingJob->update(['status' => $activityType]);

            $proof->update(['proof_sent' => now()]);

            $successMessage = match ($activityType) {
                'proof emailed' => 'Proof email sent successfully.',
                'proof resent' => 'Proof resent successfully.',
                'amended' => 'Amendment email sent successfully.',
                default => ucfirst(str_replace('_', ' ', $activityType)) . ' email sent successfully.',
            };

            // Redirect to the customers.show screen after sending the email
            return redirect()->route('customers.show', ['customer' => $proof->proofingJob->customer->id, 'proofingJob' => $proof->proofingJob->id])
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error('Error sending proof email: ' . $e->getMessage());


            return redirect()->route('proofs.confirm', $proofId)
                ->withErrors(['error' => 'Failed to send the proof email. ' . $e->getMessage()]);
        }
    }

    public function resendProofEmail($proofId)
    {
        return $this->sendProofEmail($proofId, 'proof resent');
    }

    public function destroy(Proof $proof)
    {
        Storage::disk('public')->delete($proof->file_path);
        $proof->delete();

        return redirect()->route('proofs.index')
            ->with('success', 'Proof deleted successfully.');
    }

    // ToDo: implement method to delete all proofing videos except most recent one
    public function deleteAllExceptMostRecent($jobId)
    {
        $proofs = Proof::where('job_id', $jobId)->orderBy('created_at', 'desc')->get();

        if ($proofs->count() <= 1) {
            return redirect()->route('proofs.index')
                ->with('info', 'No additional proofs to delete.');
        }

        // Keep the most recent proof
        $mostRecentProof = $proofs->first();
        $proofsToDelete = $proofs->slice(1); // All except the first one

        foreach ($proofsToDelete as $proof) {
            Storage::disk('public')->delete($proof->file_path);
            $proof->delete();
        }

        return redirect()->route('proofs.show', $mostRecentProof)
            ->with('success', 'All proofs except the most recent one have been deleted.');
    }

    /**
     * @param array|\Illuminate\Http\UploadedFile|null $file
     * @return string
     */
    public function getName(array|\Illuminate\Http\UploadedFile|null $file): string
    {
    // Generate a sanitized filename
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // Get the original name without extension
        $extension = $file->getClientOriginalExtension(); // Get the file extension
        $sanitizedName = preg_replace('/[^a-zA-Z0-9]/', '_', $originalName); // Replace non-alphanumeric characters with underscores
        $timestamp = now()->format('Y_m_d_H_i'); // Get the current timestamp in the desired format
        return "{$timestamp}_{$sanitizedName}.{$extension}"; // Combine timestamp, sanitized name, and extension
    }

}
