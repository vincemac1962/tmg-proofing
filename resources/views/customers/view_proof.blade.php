<!-- resources/views/customers/view_proof.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Proof</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 dark:bg-gray-800">
<div class="flex justify-end items-center p-4">
    <div x-data="{ open: false, dark: localStorage.getItem('theme') === 'dark' }" class="relative">
        <button @click="open = !open" class="flex items-center space-x-2 px-4 py-2 bg-gray-50 dark:bg-gray-800 rounded shadow hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
            <span class="font-semibold text-gray-800 dark:text-gray-100">{{ $user->name }}</span>
            <svg class="w-4 h-4 text-gray-600 dark:text-gray-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-gray-50 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded shadow-lg z-50">
            <button
                @click="
                    dark = !dark;
                    document.documentElement.classList.toggle('dark', dark);
                    localStorage.setItem('theme', dark ? 'dark' : 'light');
                    open = false;
                "
                class="block w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700"
            >
                Toggle Theme
            </button>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">


    <div class="w- mx-auto px-4 sm:px-6 lg:px-8 py-8 bg-gray-200 dark:bg-gray-600 rounded-lg shadow">
        <div class="flex justify-center items-center mb-8">
            <img style="max-height: 12.5rem" src="{{ Storage::url('public/images/' . $proofingCompany->company_logo_url) }}" alt="Company Logo">
        </div>

        <div class="mb-12">
            <div class="text-gray-600 dark:text-gray-100 mb-2"><span class="font-medium"><strong>Company Name:</strong></span> {{ $proofingJob->customer->company_name }}</div>
            <div class="text-gray-600 dark:text-gray-100"><span class="font-medium"><strong>Site Location:</strong></span> {{ $proofingJob->advert_location }}</div>
        </div>

        @if ($latestProof)
            <div class="mb-8">
                <div class="aspect-w-16 aspect-h-9 mb-4 flex justify-center">
                    <div x-data="{ playing: false }" class="relative aspect-w-16 aspect-h-9 mb-4 flex justify-center">
                        <video
                            class="w-full max-h-[90vh] rounded-lg shadow-sm"
                            controls
                            @play="playing = true"
                            @pause="playing = false"
                            x-ref="video"
                        >
                            <source src="{{ asset('storage/'. $latestProof->file_path) }}" type="video/mp4">
                        </video>
                        <button
                            x-show="!playing"
                            @click="$refs.video.play()"
                            class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 rounded-lg transition"
                            style="pointer-events: auto;"
                            aria-label="Play video"
                        >
                            <svg class="w-20 h-20 text-white opacity-80" fill="currentColor" viewBox="0 0 84 84">
                                <circle cx="42" cy="42" r="42" fill="currentColor" opacity="0.4"/>
                                <polygon points="34,28 62,42 34,56" fill="white"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="flex flex-col items-center">
                    <a href="{{ asset('storage/' . $latestProof->file_path) }}" target="_blank"
                       class="inline-flex items-center text-blue-800 hover:text-blue-400 dark:text-gray-200 dark:hover:text-gray-400 text-lg leading-5">
                        Open Full Screen</a>
                        <p class="text-gray-600 dark:text-gray-100 text-xs mb-2">[Opens in new window]</p>


                </div>
            </div>
        @endif

        <hr class="my-8 border-gray-300 dark:border-gray-700">


        @if ($proofingJob->status !== 'approved')
            <div class="space-y-8">
                <div>
                    <h1 class="text-3xl font-semibold text-gray-900 dark:text-gray-300 mb-4">Submit Amendment</h1>
                    <div class="text-gray-700 dark:text-gray-300 mb-5">
                    <p class="text-gray-700 dark:text-gray-300 mb-5">Please enter any amendment notes below and click "Submit Amendment". </p>
                    <p>For more extensive changes or to send us additonal content or files
                        <a class="text-red-800 hover:text-red-400 dark:text-red-200 dark:hover:text-red-800" href="mailto:{{ $proofingCompany->email_address }}">please email our design team</a>
                    </p>
                    </div>
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
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                            Submit Amendment
                        </button>
                    </form>
                </div>

                <div>
                    <h1 class="text-3xl font-semibold text-gray-900 dark:text-gray-300 mb-4 mt-5">Approve Advertisement</h1>
                    <p class="text-gray-700 dark:text-gray-300 mb-5">If you are happy with the advertisement as it is, please enter your name below and click "Approve Advertisement".
                        This will notify our team to proceed with displaying your advert at the chosen location(s).
                        <strong>Please note:</strong> that once approved, no further changes can be made unless you contact our design team directly.</p>
                    <p class="text-gray-700 dark:text-gray-300 mb-5">If you would like to download a copy of your advert for your records, please <a href="{{ route('proofs.download', $proofingJob->id) }}"
                        class="text-red-800 hover:text-red-400 dark:text-red-200 dark:hover:text-red-800"> click here.</a>
                    </p>
                    <form action="{{ route('customers.submit_approval') }}" method="POST" class="space-y-4"
                          onsubmit="return confirm('Are you sure you want to approve this advertisement?');">
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
            <div class="text-gray-600 dark:text-gray-100 mb-4 text-center">
                This proof has already been approved. Please
                <a href="mailto:email@example.com?subject=New proof required for {{ $proofingJob->contract_reference }} - {{ $proofingJob->customer->company_name }}"
                   class="text-blue-800 hover:text-blue-400 dark:text-red-200 dark:hover:text-gray-400">click here</a> to email the proofing team.
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
        <!-- Footer -->
        <x-company-footer :proofing-company="$proofingCompanyArray" />
    </div>

</div>
</body>
</html>
