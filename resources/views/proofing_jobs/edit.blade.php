@extends('layouts.app')

@section('content')
    <x-section-heading class="border-indigo-400">
        Proofing Job - Edit
    </x-section-heading>

    <form action="{{ route('proofing_jobs.update', ['customerId' => $customer->id, 'proofingJob' => $proofingJob->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <!-- first row -->
        <div class="grid grid-cols-6 gap-4 pt-5 w-full bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="col-span-2">
                <label for="contract_reference" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">Contract Reference:</label>
                <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 text-gray-900 dark:text-gray-100" type="text" name="contract_reference" id="contract_reference" value="{{ $proofingJob->contract_reference }}">
                @error('contract_reference')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>
          <div class="col-span-2">
        <label for="proofing_company_id" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">Proofing Company:</label>
        <select  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 text-gray-900 dark:text-gray-100" id="proofing_company_id" name="proofing_company_id">
            <option value="">Select a Proofing Company</option>
            @foreach($proofingCompanies as $company)
                <option value="{{ $company->id }}" {{ $proofingJob->proofing_company_id == $company->id ? 'selected' : '' }}>
                    {{ $company->name }}
                </option>
            @endforeach
        </select>
        @error('proofing_company_id')
        <div class="error">{{ $message }}</div>
        @enderror
            </div>
        <div class="col-span-2"></div>

        <div class="col-span-2">
            <label for="company_name" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">Company Name:</label>
            <div id="company_name" class="col-span-3 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $customer->company_name }}
            </div>
        </div>
            <div class="col-span-2"></div>
            <div class="col-span-3">
                <label for="advert_location" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">Location: <span class="text-red-500">*</span></label>
                <textarea id="advert_location"
                          name="advert_location"
                          required
                          class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 text-gray-900 dark:text-gray-100"
                >{{ $proofingJob->advert_location }}</textarea>
                @error('advert_location')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>
        <div class="col-span-3"></div>
            <div class="col-span-3">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">Description:</label>
                <textarea
                        id="description"
                        name="description"
                        class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 text-gray-900 dark:text-gray-100"
                >{{ $proofingJob->description}}</textarea>
                @error('description')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-span-3"></div>
            <div class="col-span-3 text-center">
                <a class="text-red-800 hover:text-red-600 pl-5" href="{{ route('customers.show', ['customer' => $customer->id]) }}">Cancel</a>
            </div>
            <div class="col-span-3 text-center">
                <button class="text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 pl-5" type="submit">Update</button>
            </div>
        </div>
    </form>

@endsection