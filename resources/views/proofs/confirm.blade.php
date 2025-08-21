@extends('layouts.app')

@section('content')
    <x-section-heading class="border-amber-400">
        Proofs - Send Email?
    </x-section-heading>
    <div class="grid grid-cols-6 gap-4 pt-5 w-full bg-white dark:bg-gray-800 shadow rounded-lg">
        <!-- first row -->
        <div class="mb-2 col-span-1">
            <label for="contract_reference" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                Contract Reference
            </label>
            <div id="contract_reference" class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $proofingJob->contract_reference }}
            </div>
        </div>

        <div class="md:col-span-2 mb-2">
            <label for="company_name" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                Company Name
            </label>
            <div id="company_name" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $proofingJob->customer->company_name }}
            </div>
        </div>
        <div class="col-span-3"></div>
        <!-- second row -->
        <div class="md:col-span-1 mb-2">
            <label for="customer_city" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                City
            </label>
            <div id="customer_city" class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $proofingJob->customer->customer_city }}
            </div>
        </div>

        <div class="md:col-span-2 mb-2">
            <label for="customer_country" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                Country
            </label>
            <div id="customer_country" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $proofingJob->customer->customer_country }}
            </div>
        </div>
        <div class="col-span-3"></div>
        <!-- third row -->
        <div class="w-full col-span-6 p-2 items-center text-center">
            <h2 class="text-xl mb-2 text-gray-900 dark:text-gray-100">Do you want to send this proof to {{ $proofingJob->customer->company_name }} now?</h2>
        </div>
        <div class="col-span-6 flex">
            <div class="flex-1 flex justify-center items-center">
                <a href="{{ route('customers.show', ['customer' => $proofingJob->customer->id]) }}" class="text-red-800 hover:text-red-600">No</a>
            </div>
            <div class="flex-1 flex justify-center items-center">
                <form method="POST" action="{{ route('proofs.sendProofEmail', $proof->id) }}">
                    @csrf
                    <input type="hidden" name="proof_type" value="{{ $proof_type ?? 'uploaded' }}">
                    <button type="submit" class="text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400">Send Email</button>
                </form>
            </div>
        </div>
    </div>
    @if ($errors->has('error'))
        <div class="mt-10 alert alert-danger text-red-600">
            {{ $errors->first('error') }}
        </div>
    @endif

@endsection