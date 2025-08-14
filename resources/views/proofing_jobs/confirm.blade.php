@extends('layouts.app')

@section('content')
    <x-section-heading class="border-indigo-400">
        Proofing Job - Create
    </x-section-heading>
    @if (session('success'))
        <div id="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-6 gap-4 pt-5 w-full bg-white dark:bg-gray-800 shadow rounded-lg">
        <!-- first row -->
        <div class="mb-2 col-span-1">
            <label for="contract_reference" class="block text-sm font-medium text-gray-700 mb-1">
                Contract Reference
            </label>
            <div id="contract_reference" class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                {{ $proofingJob->contract_reference }}
            </div>
        </div>

        <div class="md:col-span-2 mb-2">
            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">
                Company Name
            </label>
            <div id="company_name" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                {{ $customer->company_name }}
            </div>
        </div>
        <div class="col-span-3"></div>
        <!-- second row -->
        <div class="md:col-span-1 mb-2">
            <label for="customer_city" class="block text-sm font-medium text-gray-700 mb-1">
                City
            </label>
            <div id="customer_city" class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                {{ $customer->customer_city }}
            </div>
        </div>

        <div class="md:col-span-2 mb-2">
            <label for="customer_country" class="block text-sm font-medium text-gray-700 mb-1">
                Country
            </label>
            <div id="customer_country" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                {{ $customer->customer_country }}
            </div>
        </div>
        <div class="col-span-3"></div>
        <!-- third row -->
        <div class="w-full col-span-3 p-2 items-center text-center">
            <h2 class="text-xl mb-2">Do you want to upload a proof for {{ $customer->company_name }}?</h2>
        </div>
        <div class="col-span-3">
        </div>
        <!-- fourth row -->
        <div class="col-span-2 flex">
            <div class="flex-1 flex justify-center items-center">
                <a href="{{ route('customers.show', ['customer' => $customer->id]) }}" class="text-red-800 hover:text-red-600 pl-5">No</a>
            </div>
            <div class="flex-1 flex justify-center items-center">
                <form method="GET" action="{{ route('proofs.create', ['jobId' => $proofingJob->id, 'customerId' => $customer->id]) }}">
                    @csrf
                    <button type="submit" class="text-blue-800 hover:text-blue-600">Yes</button>
                </form>
            </div>
        </div>
        <div class="col-span-4"></div>

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