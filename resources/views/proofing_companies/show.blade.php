@extends('layouts.app')

@section('content')
<x-section-heading class="border-rose-700">
    Proofing Companies - View
</x-section-heading>

<div class="grid grid-cols-6 gap-4 pt-5 w-full">
    <!-- first row -->
    <div class="mb-2 col-span-1">
        <label for="id" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
            ID
        </label>
        <div id="id" class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            {{ $proofingCompany->id }}
        </div>
    </div>

    <div class="md:col-span-2 mb-2">
        <label for="company_name" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
            Company Name
        </label>
        <div id="company_name" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            {{ $proofingCompany->name }}
        </div>
    </div>
    <div class="col-span-3"></div>
    <!-- second row -->
    @if(!empty($proofingCompany->address))
    <div class="md:col-span-3 mb-2">
        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
            Address
        </label>
        <div id="address" class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            {{ $proofingCompany->address }}
        </div>
    </div>
    <div class="col-span-3"></div>
    @endif
    <!-- third row -->
    <div class="md:col-span-2 mb-2">
        <label for="telephone" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
            Tel:
        </label>
        <div id="telephone" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            {{ $proofingCompany->telephone_1 }}
        </div>
    </div>
    <div class="md:col-span-2 mb-2">
        <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
            URL
        </label>
        <div id="website" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            {{ $proofingCompany->web_url }}
        </div>
    </div>
    <div class="md:col-span-2 mb-2">
        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
            Contact Email Address:
        </label>
        <div id="email" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            {{ $proofingCompany->email_address }}
        </div>
    </div>
    <!-- fourth row -->
    <div class="md:col-span-2 mb-2">
        <label for="signatory" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
            Signatory
        </label>
        <div id="signatory" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            {{ $proofingCompany->email_signatory }}
        </div>
    </div>
    <div class="md:col-span-2 mb-2">
        <label for="signatory_role" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
            Signatory Role
        </label>
        <div id="signatory_role" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            {{ $proofingCompany->signatory_role }}
        </div>
    </div>
    <div class="col-span-2"></div>
    <!-- fifth row -->
    <div class="md:col-span-2 mb-2">
        <label for="logo_url" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
            Company Logo URL
        </label>
        <div id="logo_url" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            {{ $proofingCompany->company_logo_url }}
        </div>
    </div>
    <div class="md:col-span-2 mb-2">
        <label for="colour_split" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
            Colour Split
        </label>
        <div id="colour_split" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            {{ $proofingCompany->colour_split }}
        </div>
    </div>
    <div class="col-span-2"></div>
    <!-- sixth row -->
    <div class="md:col-span-1 mb-2">
        <label for="active" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
            Active
        </label>
        <div id="active" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            {{ $proofingCompany->active ? 'true' : 'false' }}
        </div>
    </div>
    <div class="col-span-5"></div>
    <!-- seventh row - action buttons -->
    <div class="col-span-2 text-center">
        <a href="{{ route('proofing_companies.index') }}" class="text-red-800 hover:text-red-600 pl-5">Back</a>
    </div>
    <div class="col-span-2 text-center">
        <a href="{{ route('proofing_companies.edit', $proofingCompany) }}" class="text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 pl-5">Edit</a>
    </div>
    <div class="col-span-2 text-center">
        @if(Auth::check() && (int)Auth::user()->access_level >= 2)
        <form method="POST" action="{{ route('proofing_companies.destroy', $proofingCompany) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-800 hover:text-red-600 pl-5" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
        </form>
        @else
            <span class="text-gray-400 cursor-not-allowed">Delete</span>
        @endif
    </div>


@endsection
