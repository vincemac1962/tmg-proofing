<!-- resources/views/customers/landing.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Landing Page</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-center items-center mb-8">
        <img style="max-height: 12.5rem" src="{{ Storage::url('public/images/' . $proofingCompany->company_logo_url) }}" alt="Company Logo">
    </div>

    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">
        Hello, {{ $user->name }}
    </h1>

    @foreach ($customers as $customer)
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="text-gray-600 mb-4"><strong>Company Name</strong>: {{ $customer->company_name }}</div>
            <div class="text-gray-600 mb-4"><strong>City</strong>: {{ $customer->customer_city }}</div>

            <h3 class="text-xl font-semibold text-gray-900 mb-4">Proofing Jobs</h3>

            @if ($customer->proofingJobs->isEmpty())
                <p class="text-gray-500">No proofing jobs available for this customer.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ref</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($customer->proofingJobs as $job)
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('customers.view_proof', ['id' => $job->id]) }}'">
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $job->contract_reference }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $job->advert_location }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-red-600">Click to View</td>
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
</div>
</body>
</html>