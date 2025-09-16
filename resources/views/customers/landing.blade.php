<!-- resources/views/customers/landing.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Landing Page</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
<div class="w- mx-auto px-4 sm:px-6 lg:px-8 py-8 bg-gray-200 dark:bg-gray-600 rounded-lg shadow">
    <div class="flex justify-center items-center mb-8">
        <img style="max-height: 12.5rem" src="{{ Storage::url('public/images/' . $proofingCompany->company_logo_url) }}" alt="Company Logo">
    </div>
    @if (session('success'))
        <div id="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">
        Welcome, {{ $user->name }}
    </h1>
    <p class="text-gray-700 dark:text-gray-300 mb-10">Here are your proofing jobs. Click on a job to view the proof and submit amendments or
        approve it for display at your chosen location(s)
        If you experience any issues viewing your proof,
       <a class="text-red-800 hover:text-red-400 dark:text-red-200 dark:hover:text-red-800" href="mailto:{{ $proofingCompany->email_address }}">please email our design team</a>
    </p>
    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-8">
    @foreach ($customers as $customer)

            <div class="text-gray-600 dark:text-gray-100 mb-4"><strong>Company Name</strong>: {{ $customer->company_name }}</div>
            <div class="text-gray-600 dark:text-gray-100 mb-4"><strong>City</strong>: {{ $customer->customer_city }}</div>
            <div class="flex justify-center items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Proofing Jobs</h3>
            </div>


            @if ($customer->proofingJobs->isEmpty())
                <p class="text-gray-500">No proofing jobs available for this customer.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-300 dark:bg-gray-700">
                        <tr class="px-4 py-3 text-left text-xs font-medium text-gray-500">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ref</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                        </tr>
                        </thead>
                        <tbody class="bg-slate-200 divide-y divide-gray-200">
                        @foreach ($customer->proofingJobs as $job)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-400 dark:bg-gray-600 cursor-pointer" onclick="window.location='{{ route('customers.view_proof', ['id' => $job->id]) }}'">
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $job->contract_reference }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $job->advert_location }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-200 hover:text-red-900 dark:hover:text-red-200">View</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endforeach

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
</body>
</html>
