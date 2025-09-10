@extends('layouts.app')

@section('content')
    <x-section-heading class="border-teal-400">
        Customers - Show
    </x-section-heading>
    @if (session('success'))
        <div id="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    <div class="grid grid-cols-8 gap-4 pt-5 w-full">
        <!-- first row -->
        <div class="mb-2 col-span-1">
            <label for="contract_reference" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                Contract Reference
            </label>
            <div id="contract_reference" class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $customer->contract_reference }}
            </div>
        </div>

        <div class="md:col-span-3 mb-4">
            <label for="company_name" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                Company Name
            </label>
            <div id="company_name" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $customer->company_name }}
            </div>
        </div>
        <div class="col-span-4"></div>
        <!-- second row -->
        <div class="md:col-span-2 mb-2">
            <label for="customer_city" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                City
            </label>
            <div id="customer_city" class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $customer->customer_city }}
            </div>
        </div>

        <div class="md:col-span-2 mb-4">
            <label for="customer_country" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                Country
            </label>
            <div id="customer_country" class="col-span-4 w-full px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $customer->customer_country }}
            </div>
        </div>
        <div class="col-span-4"></div>
        <!-- third row -->
        <div class="md:col-span-4 mb-4">
            <label for="additional_pocs" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                Additional POCs
            </label>
            <div id="additional_pocs" class="col-span-4 w-full px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $customer->additional_pocs }}
            </div>
        </div>
        <div class="col-span-4"></div>
        <!-- fourth row -->
        @if(!empty($customer->notes))
        <div class="w-full md:col-span-8 mb-2">
            <label for="customer_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                Notes
            </label>
            <div id="customer_notes" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $customer->notes }}
            </div>
        </div>
        @endif
        <!-- fifth row - proofing jobs table -->
        <div class="w-full col-span-8 mb-2">
            @if($customer->proofingJobs->isEmpty())
                <p>No proofing jobs found.</p>
            @else
                <table class="w-full border-collapse mt-5 mx-auto text-gray-900 dark:text-gray-100">
                    <thead>
                    <!-- Title Row -->
                    <tr>
                        <th colspan="7" class="text-center text-lg font-bold py-4">
                            Proofing Jobs for {{ $customer->company_name }}
                        </th>
                    </tr>
                    <!-- Header Row -->
                    <tr class="bg-indigo-400 text-white">
                        <th class="py-2 px-4 text-center">ID</th>
                        <th class="py-2 px-4 text-center">C/N Reference</th>
                        <th class="py-2 px-4 text-center">Advert Location</th>
                        <th class="py-2 px-4 text-center">Sent From</th>
                        <th class="py-2 px-4 text-center">Designer</th>
                        <th class="py-2 px-4 text-center">Status</th>
                        <th class="py-2 px-4 text-center">Date Created</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Data Rows -->
                            @foreach($customer->proofingJobs as $job)
                                    <tr class="hover:bg-gray-300  dark:hover:bg-gray-700" onclick="window.location='{{ route('proofing_jobs.show', ['proofingJob' => $job->id]) }}'">
                                        <td class="py-2 px-4 text-center">{{ $job->id }}</td>
                                        <td class="py-2 px-4 text-center">{{ $job->contract_reference }}</td>
                                        <td class="py-2 px-4 text-center">{{ $job->advert_location }}</td>
                                        <td class="py-2 px-4 text-center">{{ $job->proofingCompany->name }}</td>
                                        <td class="py-2 px-4 text-center">{{ $job->designer->name ?? 'No designer assigned' }}</td>
                                        <td class="py-2 px-4 text-center">{{ $job->status }}</td>
                                        <td class="py-2 px-4 text-center">{{ $job->created_at->format('d-m-Y') }}</td>
                                        <td class="py-2 px-4 text-center"><a href="{{ route('proofing_jobs.edit',  ['customerId' => $job->customer_id,'proofingJob' => $job->id]) }}">Edit</a></td>
                                    </tr>
                            @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <!-- sixth row - action buttons -->
        <div class="col-span-2 flex justify-center items-center">
            <a href="{{ route('customers.index') }}" class="text-red-800 hover:text-red-600 pl-5">Back</a>
        </div>
        <div class="col-span-2 flex justify-center items-center>">
            <!-- add proofing job -->
            <a href="{{ route('proofing_jobs.create', ['customerId' => $customer->id]) }}" class="text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 pl-5">
                Add Proofing Job
            </a>
        </div>
        <div class="col-span-2 flex justify-center items-center">
            @if(!$customer->proofingJobs->isEmpty())
                <!-- send proof -->
                <a href="{{ route('proofs.create', ['jobId' => $customer->proofingJobs->first()->id, 'customerId' => $customer->id, 'proof_type' => 'proof uploaded']) }}" class="text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 pl-5">
                    Send Proof
                </a>
            @else
                <span class="text-gray-400 pl-5">
            Send Proof
        </span>
            @endif
        </div>

        <div class="col-span-2 flex justify-center items-center">
            @if(!$customer->proofingJobs->isEmpty())
                <!-- send amendment -->
                <a href="{{ route('proofs.create', ['jobId' => $customer->proofingJobs->first()->id, 'customerId' => $customer->id, 'proof_type' => 'amended']) }}" class="text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 pl-5">
                    Send Amendment
                </a>
            @else
                <span class="text-gray-400 pl-5">
            Send Amendment
        </span>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Success message auto-hide functionality
            const successMessage = document.getElementById('successMessage');
            console.log(successMessage); // Debugging
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.display = 'none';
                    console.log('Success message hidden'); // Debugging
                }, 5000); // 5000ms = 5 seconds
            }
        });
    </script>
@endpush
