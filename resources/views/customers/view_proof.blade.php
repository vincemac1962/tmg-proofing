<!-- resources/views/customers/view_proof.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Proof</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-center items-center mb-8">
        <img style="max-height: 12.5rem" src="{{ Storage::url('public/images/' . $proofingCompany->company_logo_url) }}" alt="Company Logo">
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Proofing Job Details</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <div class="text-gray-600"><span class="font-medium"><strong>Company Name:</strong></span> {{ $proofingJob->customer->company_name }}</div>
            <div class="text-gray-600"><span class="font-medium"><strong>Site Location:</strong></span> {{ $proofingJob->advert_location }}</div>
        </div>

        @if ($latestProof)
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Current Proof</h2>
                <div class="aspect-w-16 aspect-h-9 mb-4">
                    <video class="w-full rounded-lg shadow-sm" controls>
                        <source src="{{ asset('storage/'. $latestProof->file_path) }}" type="video/mp4">
                    </video>
                </div>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ asset('storage/' . $latestProof->file_path) }}" target="_blank"
                       class="inline-flex items-center text-blue-600 hover:text-blue-800">
                        Open Full Screen
                    </a>
                    <a href="{{ route('proofs.download', $proofingJob->id) }}" download
                       class="inline-flex items-center text-blue-600 hover:text-blue-800">
                        Download Video
                    </a>
                </div>
            </div>
        @endif


        @if ($proofingJob->status !== 'approved')
            <div class="space-y-8">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Submit Amendment</h2>
                    <form action="{{ route('customers.submit_amendment') }}" method="POST" class="space-y-4">
                        @csrf
                        <textarea class="w-full h-32 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  name="amendment_notes"
                                  placeholder="Enter amendment notes"
                                  required></textarea>
                        <input type="hidden" name="proof_id" value="{{ $latestProof->id ?? '' }}">
                        <input type="hidden" name="proofing_job_id" value="{{ $proofingJob->id }}">
                        <input type="hidden" name="customer_id" value="{{ $proofingJob->customer_id }}">
                        <input type="hidden" name="contract_reference" value="{{ $proofingJob->contract_reference }}">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                            Submit Amendment
                        </button>
                    </form>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Approve Advertisement</h2>
                    <form action="{{ route('customers.submit_approval') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="text" name="approved_by" placeholder="Approved By" required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <input type="hidden" name="proof_id" value="{{ $latestProof->id ?? '' }}">
                        <input type="hidden" name="proofing_job_id" value="{{ $proofingJob->id }}">
                        <input type="hidden" name="customer_id" value="{{ $proofingJob->customer_id }}">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md">
                            Approve Advertisement
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="text-gray-600">
                This proof has already been approved. Please
                <a href="mailto:email@example.com?subject=New proof required for {{ $proofingJob->contract_reference }} - {{ $proofingJob->customer->company_name }}"
                   class="text-blue-600 hover:text-blue-800">click here</a> to email the proofing team.
            </div>
        @endif

            <div class="flex justify-center items-center mt-10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md">
                        Logout
                    </button>
                </form>
            </div>
    </div>
</div>
</body>
</html>