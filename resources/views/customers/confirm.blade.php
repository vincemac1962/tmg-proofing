@extends('layouts.app')

@section('content')

    <x-section-heading class="border-teal-400">
        Customers - Customer Created
    </x-section-heading>

    @if (session('success'))
        <div id="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    <div class="grid grid-cols-2 gap-4 pt-5 w-full bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="w-1/2 col-span-2 p-6">
                <h2 class="text-xl font-semibold mb-4">Customer Details</h2>
                <p><strong>Company Name:</strong> {{ $customer->company_name }}</p>
                <p><strong>City:</strong> {{ $customer->customer_city }}</p>
                <p><strong>Country:</strong> {{ $customer->customer_country }}</p>
                <p><strong>Contract Reference:</strong> {{ $customer->contract_reference }}</p>
            </div>

            <div class="col-span-2 p-6 flex justify-center items-center">
                <h2 class="text-xl mb-2">Do you want to create a new proofing job for {{ $customer->company_name }}?</h2>
            </div>
            <div class="mb-3 col-span-1 flex justify-center items-center">
                <a href="{{ route('customers.index') }}" class="text-red-800 hover:text-red-600 pl-5">No</a>
            </div>
            <div class="mb-3 col-span-1 flex justify-center items-center">
                <form method="GET" action="{{ route('proofing_jobs.create', $customer->id) }}">
                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                    <input type="hidden" name="company_name" value="{{ $customer->company_name }}">
                    <input type="hidden" name="customer_city" value="{{ $customer->customer_city }}">
                    <input type="hidden" name="customer_country" value="{{ $customer->customer_country }}">
                    <input type="hidden" name="contract_reference" value="{{ $customer->contract_reference }}">
                    <button
                            type="submit"
                            class="text-blue-800 hover:text-blue-600"
                    >
                        Yes
                    </button>
                </form>
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

