@extends('layouts.app')

@section('content')
    <x-section-heading class="border-indigo-400">
        Proofing Job - Create
    </x-section-heading>
    <form method="POST" action="{{ route('proofing_jobs.store', ['customerId' => $customerId]) }}">
        @csrf
        <input type="hidden" name="customer_id" value="{{ $customerId }}">
    <div class="grid grid-cols-6 gap-4 pt-5 w-full bg-white dark:bg-gray-800 shadow rounded-lg">
        <!-- first row -->
        <div class="mb-2 col-span-1">
            <label for="contract_reference" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                Contract Reference
            </label>
            <div id="contract_reference" class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $contract_reference }}
            </div>
        </div>

        <div class="md:col-span-2 mb-2">
            <label for="company_name" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                Company Name
            </label>
            <div id="company_name" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $company_name }}
            </div>
        </div>
        <div class="col-span-3"></div>
        <!-- second row -->
        <div class="md:col-span-1 mb-2">
            <label for="customer_city" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                City
            </label>
            <div id="customer_city" class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $customer_city }}
            </div>
        </div>

        <div class="md:col-span-2 mb-2">
            <label for="customer_country" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                Country
            </label>
            <div id="customer_country" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $customer_country }}
            </div>
        </div>
        <div class="col-span-3"></div>
        <!-- third row -->
        <div class="w-full col-span-3 p-2 items-center text-center">
            <h2 class="text-xl mb-2 text-gray-900 dark:text-gray-100">Create a New Proofing Job</h2>
            <p class="text-gray-900 dark:text-gray-100">Fill out the form below to create a new proofing job for {{ $company_name }}.</p>
        </div>
        <div class="col-span-3">

        </div>
        <!-- fourth row -->
        <div class="w-full col-span-1 py-2">
            <label for="contract_reference" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">Contract Reference:  <span class="text-red-500">*</span></label>
            <input
                    type="text"
                    id="contract_reference"
                    name="contract_reference"
                    class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 text-gray-900 dark:text-gray-100"
                    required
                    value="{{ old('contract_reference', $contract_reference) }}">
            @error('contract_reference')
            <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <div class="w-full col-span-2 p-2">
        <label for="proofing_company_id" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
            Proofing Company:&nbsp <span class="text-red-500">*</span>
        </label>
        <select
                id="proofing_company_id"
                name="proofing_company_id"
                class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 text-gray-900 dark:text-gray-100"
                required
        >
            @foreach($proofingCompanies as $company)
                <option value="{{ $company->id }}" {{ old('proofing_company_id') == $company->id ? 'selected' : '' }}>
                    {{ $company->name }}
                </option>
            @endforeach
        </select>
            @error('proofing_company_id')
            <div class="error">{{ $message }}</div>
            @enderror
    </div>
        <div class="w-full col-span-2 p-2">
            <label for="designer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                Designer: <span class="text-red-500">*</span>
            </label>
        <select
                id="designer_id"
                name="designer_id"
                class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 text-gray-900 dark:text-gray-100"
            >
                <option value="">Select a Designer</option>
                @foreach($designers as $designer)
                    <option value="{{ $designer->id }}" {{ old('designer_id') == $designer->id ? 'selected' : '' }}>
                        {{ $designer->name }}
                    </option>
                @endforeach
        </select>
        @error('designer_id')
        <div class="error">{{ $message }}</div>
        @enderror
        </div>
        <div class="col-span-1"></div>
        <!-- fifth row -->
        <div class="col-span-3">
            <label for="advert_location" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">Location: <span class="text-red-500">*</span></label>
            <textarea id="advert_location"
                      name="advert_location"
                      required
                        class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 text-gray-900 dark:text-gray-100"
            >{{ old('advert_location') }}</textarea>
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
                    >{{ old('description') }}</textarea>
            @error('description')
            <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-span-3"></div>
        <!-- sixth row -->
        <div class="col-span-6 flex">
            <div class="flex-1 flex justify-center items-center">
                <div class="btn text-red-800 hover:text-red-600 pl-5" onclick="history.back()">Cancel</div>
            </div>
            <div class="flex-1 flex justify-center items-center">
                <button type="submit" class="text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400">Save</button>
            </div>
        </div>
        </div>



    </form>
    @if ($errors->any())
        <div class="md:col-span-4">
            <h2 class="text-red-600 font-bold">Please fix the following errors:</h2>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
@endsection