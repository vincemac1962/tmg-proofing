<!-- resources/views/customers/edit.blade.php -->

@extends('layouts.app')

@section('content')
    <x-section-heading class="border-teal-400">
        Customers - Edit
    </x-section-heading>
    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')
        <!-- Main grid -->
        <div class="grid grid-cols-4 gap-4 pt-5 w-full">
        <!-- first row -->
        <div class="mb-2 col-span-1">
            <label for="contract_reference" class="block text-sm font-medium text-gray-700 mb-1">
                Contract Reference <span class="text-red-500">*</span>
            </label>
            <input
                    type="text"
                    id="contract_reference"
                    name="contract_reference"
                    class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    value="{{ $customer->contract_reference }}" required
            />
        </div>

        <div class="md:col-span-2 mb-2">
            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">
                Company Name <span class="text-red-500">*</span>
            </label>
            <input
                    type="text"
                    id="company_name"
                    name="company_name"
                    class="w-full col-span-4 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    value="{{ $customer->company_name }}"
                    required
            />
        </div>
        <!-- second row -->
        <div class="md:col-span-2 mb-2">
            <label for="customer_city" class="block text-sm font-medium text-gray-700 mb-1">
                City  <span class="text-red-500">*</span>
            </label>
            <input
                    type="text"
                    id="customer_city"
                    name="customer_city"
                    class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    value="{{ $customer->customer_city }}" required
            />
        </div>

        <div class="md:col-span-2 mb-2">
            <label for="customer_country" class="block text-sm font-medium text-gray-700 mb-1">
                Country
            </label>
            <select
                    id="customer_country"
                    name="customer_country"
                    class="w-full col-span-4 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
                @foreach ($countries as $country)
                    <option value="{{ $country }}" {{ $customer->customer_country === $country ? 'selected' : '' }}>
                        {{ $country }}
                    </option>
                @endforeach
            </select>
        </div>
        <!-- third row -->
        <div class="md:col-span-2 mb-2">
            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">
                Contact  <span class="text-red-500">*</span>
            </label>
            <input
                    type="text"
                    id="customer_name"
                    name="customer_name"
                    class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    value="{{ $customer->customer_name }}" required
            />
        </div>

        <div class="md:col-span-2 mb-4">
            <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">
                Email <span class="text-red-500">*</span>
            </label>
            <input
                    type="email"
                    id="customer_email"
                    name="customer_email"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    value="{{ $customer->user->email }}" required
            />
        </div>

        <!-- fourth row -->
        <div class="w-1/2 md:col-span-4 mb-2">
            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">
                Phone Number
            </label>
            <input
                    type="text"
                    id="contact_number"
                    name="contact_number"
                    class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    value="{{ $customer->contact_number }} "
            />
        </div>

        <!-- fifth row -->
        <div class="w-full md:col-span-4 mb-2">
            <label for="customer_notes" class="block text-sm font-medium text-gray-700 mb-1">
                Notes
            </label>
            <textarea
                    rows="4"
                    name="notes"
                    id="customer_notes"
                    class="text-indent-0 col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
                {{ $customer->notes }}
            </textarea>
        </div>
        <!-- sixth row -->
            <div class="md:col-span-2 flex justify-center items-center">
                <a href="{{ route('customers.index') }}" class="text-red-800 hover:text-red-600 pl-5">Cancel</a>
            </div>
            <div class="md:col-span-2 flex justify-center items-center">
                <button
                        type="submit"
                        class="text-blue-800 hover:text-blue-600"
                >
                    Save
                </button>
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