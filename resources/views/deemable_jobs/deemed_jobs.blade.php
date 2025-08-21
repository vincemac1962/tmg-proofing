@extends('layouts.app')

@section('content')
    <x-section-heading class="border-slate-400">
        Deemed Job History
    </x-section-heading>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('deemable_jobs.show_deemed') }}" class="mb-4">
        <div class="flex flex-row items-center mt-4 space-x-6">
            <!-- Date Range Pickers -->
            <div class="flex space-x-2">
                <x-text-input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ request('start_date') }}"
                        class="w-40"
                />
                <span class="text-gray-500">to</span>
                <x-text-input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ request('end_date') }}"
                        class="w-40"
                />
            </div>

            <!-- Country Dropdown -->
            <div>
                <x-dropdown align="left" width="48" :active="request()->has('country')">
                    <x-slot name="trigger">
                        <span class="py-1 text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 text-lg leading-5">
                            {{ request('country', __('Filter by Country')) }}
                        </span>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('deemable_jobs.show_deemed', array_merge(request()->except('country'), ['country' => '']))">
                            {{ __('All Countries') }}
                        </x-dropdown-link>
                        @foreach ($countries as $country)
                            <x-dropdown-link :href="route('deemable_jobs.show_deemed', array_merge(request()->except('country'), ['country' => $country]))">
                                {{ $country }}
                            </x-dropdown-link>
                        @endforeach
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Proofing Company Dropdown -->
            <div>
                <x-dropdown align="left" width="48" :active="request()->has('proofing_company')">
                    <x-slot name="trigger">
                        <span class="py-1 text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 text-lg leading-5">
                            {{ request('proofing_company', __('Filter by Proofing Company')) }}
                        </span>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('deemable_jobs.show_deemed', array_merge(request()->except('proofing_company'), ['proofing_company' => '']))">
                            {{ __('All Companies') }}
                        </x-dropdown-link>
                        @foreach ($proofingCompanies as $company)
                            <x-dropdown-link :href="route('deemable_jobs.show_deemed', array_merge(request()->except('proofing_company'), ['proofing_company' => $company]))">
                                {{ $company }}
                            </x-dropdown-link>
                        @endforeach
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Records Per Page Dropdown -->
            <div>
                <x-dropdown align="left" width="48" :active="request()->has('perPage')">
                    <x-slot name="trigger">
                        <span class="py-1 text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 text-lg leading-5">
                            {{ request('perPage', 25) }} {{ __('Records per Page') }}
                        </span>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('deemable_jobs.show_deemed', array_merge(request()->except('perPage'), ['perPage' => 5]))">
                            5
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('deemable_jobs.show_deemed', array_merge(request()->except('perPage'), ['perPage' => 10]))">
                            10
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('deemable_jobs.show_deemed', array_merge(request()->except('perPage'), ['perPage' => 25]))">
                            25
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('deemable_jobs.show_deemed', array_merge(request()->except('perPage'), ['perPage' => 50]))">
                            50
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>

        <!-- Filter and Reset Buttons -->
        <div class="flex flex-row items-center mt-4 space-x-2">
            <button type="submit" class="text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400">
                Apply Filters
            </button>
            <a href="{{ route('deemable_jobs.show_deemed') }}" class="text-red-800 hover:text-red-600 pl-5">
                Reset Filters
            </a>
        </div>
    </form>
    <!-- Displaying the chosen dates -->
    <p class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">Showing records between  {{ $startDate }} AND {{ $endDate }}</p>

    @if(!$deemed_jobs->isEmpty())
        @php $currentWeek = null; @endphp

        @foreach($deemed_jobs->groupBy('week_start') as $weekStart => $weekDeemedJobs)
            <!-- Week Header -->
            <h3 class="mt-8 mb-4 text-lg font-semibold">
                w/b {{ \Carbon\Carbon::parse($weekStart)->format('d-M-Y') }}
            </h3>

            <!-- Reminders Table -->
            <table class="w-full border-collapse mx-auto">
                <thead>
                <tr class="bg-slate-400 dark:bg-gray-800 text-white dark:text-gray-300">
                    <th class="py-2 px-4 text-left">Date/Time</th>
                    <th class="py-2 px-4 text-left">Job ID</th>
                    <th class="py-2 px-4 text-left">Customer</th>
                    <th class="py-2 px-4 text-left">Country</th>
                    <th class="py-2 px-4 text-left">Time Company</th>
                    <th class="py-2 px-4 text-left">Activity Type</th>
                    <th class="py-2 px-4 text-left">Sent By</th>
                </tr>
                </thead>
                <tbody>
                @foreach($weekDeemedJobs as $deemedJob)
                    <tr class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <td class="py-2 px-4 border-b">
                            {{ \Carbon\Carbon::parse($deemedJob->updated_at)->format('d-M-Y H:i') }}
                        </td>
                        <td class="py-2 px-4 border-b">
                            {{ $deemedJob->job_id }}
                        </td>
                        <td class="py-2 px-4 border-b">
                            {{ $deemedJob->company_name ?? 'N/A' }}
                        </td>
                        <td class="py-2 px-4 border-b">
                            {{ $deemedJob->customer_country ?? 'N/A' }}
                        </td>
                        <td class="py-2 px-4 border-b">
                            {{ $deemedJob->proofing_company_name ?? 'N/A' }}
                        </td>
                        <td class="py-2 px-4 border-b">
                            {{ $deemedJob->activity_type }}
                        </td>
                        <td class="py-2 px-4 border-b">
                            {{ $deemedJob->user->name ?? 'N/A' }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endforeach

        <!-- Pagination -->
        <div class="flex flex-col items-center mt-4">
            <div class="text-gray-600 dark:text-gray-400 mb-2">
                Showing {{ $deemed_jobs->firstItem() }} to {{ $deemed_jobs->lastItem() }} of {{ $deemed_jobs->total() }} results
            </div>
            <div class="mt-2">
                {{ $deemed_jobs->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    @else
        <div class="text-center mt-4">
            <p class="text-gray-500">No deemed jobs found for the selected date range.</p>
        </div>
        @endif
@endsection
