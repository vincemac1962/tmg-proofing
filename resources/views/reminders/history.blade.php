@extends('layouts.app')

@section('content')
    <x-section-heading class="border-rose-400">
        Reminder History
    </x-section-heading>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('reminders.history') }}" class="mb-4">
        <div class="flex flex-row items-center mt-4 space-x-6">
            <!-- Date Range Pickers -->
            <div class="flex space-x-2">
                <div class="relative">
                    <x-text-input
                            type="date"
                            id="start_date"
                            name="start_date"
                            value="{{ request('start_date') }}"
                            class="w-40"
                    />
                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 4h10M5 11h14M5 15h14M5 19h14M5 7h14" />
                        </svg>
                    </span>
                </div>
                <span class="text-gray-500">to</span>
                <div class="relative">
                <x-text-input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ request('end_date') }}"
                        class="w-40"
                />
                <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 4h10M5 11h14M5 15h14M5 19h14M5 7h14" />
                        </svg>
                    </span>
                </div>
            </div>

            <!-- Country Dropdown -->
            <div>
                <x-dropdown align="left" width="48" :active="request()->has('country')">
                    <x-slot name="trigger">
                        <span class="py-1  text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 text-lg leading-5">
                            {{ request('country', __('Filter by Country')) }}
                        </span>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('reminders.index', array_merge(request()->except('country'), ['country' => '']))">
                            {{ __('All Countries') }}
                        </x-dropdown-link>
                        @foreach ($countries as $country)
                            <x-dropdown-link :href="route('reminders.index', array_merge(request()->except('country'), ['country' => $country]))">
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
                        <span class="py-1  text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 text-lg leading-5">
                            {{ request('proofing_company', __('Filter by Proofing Company')) }}
                        </span>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('reminders.index', array_merge(request()->except('proofing_company'), ['proofing_company' => '']))">
                            {{ __('All Companies') }}
                        </x-dropdown-link>
                        @foreach ($proofingCompanies as $company)
                            <x-dropdown-link :href="route('reminders.index', array_merge(request()->except('proofing_company'), ['proofing_company' => $company]))">
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
                        <span class="py-1  text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 text-lg leading-5">
                            {{ request('perPage', 25) }} {{ __('Records per Page') }}
                        </span>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('reminders.index', array_merge(request()->except('perPage'), ['perPage' => 5]))">
                            5
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('reminders.index', array_merge(request()->except('perPage'), ['perPage' => 10]))">
                            10
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('reminders.index', array_merge(request()->except('perPage'), ['perPage' => 25]))">
                            25
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('reminders.index', array_merge(request()->except('perPage'), ['perPage' => 50]))">
                            50
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>

        <!-- Filter and Reset Buttons -->
        <div class="flex flex-row items-center mt-4 space-x-2">
            <button type="submit" class="py-1  text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 text-lg leading-5">
                Apply Filters
            </button>
            <a href="{{ route('reminders.history') }}" class="text-red-800 hover:text-red-600 pl-5 text-lg leading-5">
                Reset Filters
            </a>
        </div>
    </form>

    <!-- Displaying the chosen dates -->
    <p class="text-gray-900 dark:text-gray-100">Showing records between  {{ $startDate->format('d-m-Y') }} AND {{ $endDate->format('d-m-Y') }}</p>

    @if(!$reminders->isEmpty())
        @php $currentWeek = null; @endphp

        @foreach($reminders->groupBy('week_start') as $weekStart => $weekReminders)
            <!-- Week Header -->
            <h3 class="mt-8 mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100 inline-block border-t-2 border-b-2 border-rose-400 px-2">
                W/B {{ \Carbon\Carbon::parse($weekStart)->format('d-M-Y') }}
            </h3>

            <!-- Reminders Table -->
            <table class="w-full border-collapse mx-auto">
                <thead>
                <tr class="bg-rose-900 dark:bg-gray-800 text-white dark:text-gray-300">
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
                @foreach($weekReminders as $reminder)
                    <tr class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <td class="py-2 px-4 border-b">
                            {{ $reminder->updated_at->format('d-M-Y H:i') }}
                        </td>
                        <td class="py-2 px-4 border-b">
                            {{ $reminder->job_id }}
                        </td>
                        <td class="py-2 px-4 border-b">
                            {{ $reminder->proofingJob->customer->company_name ?? 'N/A' }}
                        </td>
                        <td class="py-2 px-4 border-b">
                            {{ $reminder->proofingJob->customer->customer_country ?? 'N/A' }}
                        </td>
                        <td class="py-2 px-4 border-b">
                            {{ $reminder->proofingJob->proofingCompany->name ?? 'N/A' }}
                        </td>
                        <td class="py-2 px-4 border-b">
                            {{ $reminder->activity_type }}
                        </td>
                        <td class="py-2 px-4 border-b">
                            {{ $reminder->user->name ?? 'N/A' }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endforeach
        <div class="grid grid-cols-2 w-full pt-5">
            <div class="col-span-1 text-center">
                <button type="submit" class="btn btn-primary text-red-800 hover:text-red-600" onclick="window.history.back()">Back</button>
            </div>
            <div class="col-span-1 text-center">
                <a href="{{ route('reminders.download_history') }}" class="btn btn-primary text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400">Generate and Download CSV</a>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex flex-col items-center mt-4">
            <div class="text-gray-600 dark:text-gray-400 mb-2">
                Showing {{ $reminders->firstItem() }} to {{ $reminders->lastItem() }} of {{ $reminders->total() }} results
            </div>
            <div class="mt-2">
                {{ $reminders->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    @else
        <div class="text-center mt-4">
            <p class="text-gray-500">No reminders found for the selected date range.</p>
        </div>
    @endif
@endsection
