<!-- resources/views/proofing_companies/edit.php -->

@extends('layouts.app')

@section('content')
    <x-section-heading class="border-rose-700">
        Proofing Companies - Edit
    </x-section-heading>
    <form method="POST" action="{{ isset($proofingCompany) ? route('proofing_companies.update', $proofingCompany) : route('proofing_companies.store') }}">
        @csrf
        @method('PUT')
        <!-- Main grid -->
        <div class="grid grid-cols-6 gap-4 pt-5 w-full">
            <!-- first row -->
            <div class="mb-2 col-span-2">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                    Company Name
                </label>
                <input
                        type="text"
                        id="name"
                        name="name"
                        class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500  dark:bg-gray-900 text-gray-900 dark:text-gray-100"
                        value="{{ old('name', $proofingCompany->name ?? '') }}"
                        required
                />
            </div>

            <div class="md:col-span-4 mb-2">
                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                    Postal Address
                </label>
                <input
                        type="text"
                        id="address"
                        name="address"
                        class="w-full col-span-4 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 text-gray-900 dark:text-gray-100"
                        value="{{ old('address', $proofingCompany->address ?? '') }}"
                />
            </div>
            <!-- second row -->
            <div class="md:col-span-2 mb-2">
                <label for="telephone_1" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                    City
                </label>
                <input
                        type="text"
                        id="telephone_1"
                        name="telephone_1"
                        class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 text-gray-900 dark:text-gray-100"
                        value="{{ old('telephone_1', $proofingCompany->telephone_1 ?? '') }}" required
                />
            </div>

            <div class="md:col-span-2 mb-2">
                <label for="email_address" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                    Email Address
                </label>
                <input
                        type="text"
                        id="email_address"
                        name="email_address"
                        class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 text-gray-900 dark:text-gray-100"
                        value="{{ old('email_address', $proofingCompany->email_address ?? '') }}" required
                />
            </div>

            <div class="md:col-span-2 mb-2">
                <label for="web_url" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                    Web URL
                </label>
                <input
                        type="text"
                        id="web_url"
                        name="web_url"
                        class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 text-gray-900 dark:text-gray-100"
                        value="{{ old('web_url', $proofingCompany->web_url ?? '') }}" required
                />
            </div>
            <!-- third row -->
            <div class="md:col-span-2 mb-2">
                <label for="email_signatory" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                    Email Signatory
                </label>
                <input
                        type="text"
                        id="email_signatory"
                        name="email_signatory"
                        class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 text-gray-900 dark:text-gray-100"
                        value="{{ old('email_signatory', $proofingCompany->email_signatory ?? '') }}" required
                />
            </div>

            <div class="md:col-span-2 mb-4">
                <label for="signatory_role" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                    Signatory Role
                </label>
                <input
                        type="text"
                        id="signatory_role"
                        name="signatory_role"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 text-gray-900 dark:text-gray-100"
                        value="{{ old('signatory_role', $proofingCompany->signatory_role ?? '') }}" required
                />
            </div>
            <div class="col-span-2"></div>

            <!-- fourth row -->
            <div class="w-full md:col-span-2 mb-2">
                <label for="company_logo_url" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                    Logo URL
                </label>
                <input
                        type="text"
                        id="company_logo_url"
                        name="company_logo_url"
                        class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 text-gray-900 dark:text-gray-100"
                        value="{{ old('company_logo_url', $proofingCompany->company_logo_url ?? '') }} "
                        required
                />
            </div>

            <div class="w-full md:col-span-2 mb-2">
                <label for="colour_split" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                    Colour Split
                </label>
                <input
                type="text"
                id="colour_split"
                name="colour_split"
                class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 text-gray-900 dark:text-gray-100"
                value="{{ old('colour_split', $proofingCompany->colour_split ?? '') }} "
                required
                />
            </div>
            <div class="col-span-1">
                <div class="md:col-span-1 mb-2 items-center text-center">
                    <label for="active" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">Active?:</label>
                    <!-- Hidden input to send false value -->
                    <input type="hidden" name="active" value="0">
                    <!-- Checkbox to send true value -->
                    <input type="checkbox" id="active" name="active" value="1" {{ old('active', $proofingCompany->active ?? false) ? 'checked' : '' }}>
                </div>
            </div>
            <div class="col-span-1"></div>
            <!-- fifth row -->
            <div class="md:col-span-3 flex justify-center items-center text-center">
                <a href="{{ route('proofing_companies.index') }}" class="text-red-800 hover:text-red-600 pl-5">Cancel</a>
            </div>
            <div class="md:col-span-3flex justify-center items-center text-center">
                <button
                        type="submit"
                        class="text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 pl-5"
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