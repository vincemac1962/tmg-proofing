@extends('layouts.app')

@section('content')
    <x-section-heading class="border-rose-400">
        Reminders - Select from Due
    </x-section-heading>
    <!-- Displaying success message if available -->
    @if (session('success'))
        <x-alert-success>
            {{ session('success') }}
        </x-alert-success>
    @endif
    <!-- Filter Form -->
    <form method="GET" action="{{ route('reminders.index') }}" class="mb-4">
        <div class="flex flex-row items-center mt-4 space-x-6">
            <!-- Date Picker -->
            <div>
                <x-text-input
                        type="date"
                        id="chosen_date"
                        name="chosen_date"
                        value="{{ request('chosen_date') }}"
                        class="w-64"
                />
            </div>

            <!-- Country Dropdown -->
            <div>
                <x-dropdown align="left" width="48" :active="request()->has('country')">
                    <x-slot name="trigger">
                    <span class="py-1 bg-white dark:bg-gray-700 text-blue-800 hover:text-blue-400 text-lg leading-5">
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
                    <span class="py-1 bg-white dark:bg-gray-700 text-blue-800 hover:text-blue-400 text-lg leading-5">
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
                    <span class="py-1 bg-white dark:bg-gray-700 text-lg text-blue-800 hover:text-blue-400 leading-5">
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
            <button type="submit" class="text-blue-800 hover:text-blue-600">
                Apply Filters
            </button>
            <a href="{{ route('reminders.index') }}" class="text-red-800 hover:text-red-600 pl-5">
                Reset Filters
            </a>
        </div>
    </form>
    <!-- Displaying the chosen date -->
    <p><span style="font-weight: bold"> Selected Date: </span>{{ $formattedDate }}</p>

    <form method="POST" action="{{ route('reminders.process') }}">
        @csrf
        @if(!$reminders->isEmpty())
        <table class="w-full border-collapse mt-16 mx-auto">
            <thead>
            <tr class="bg-rose-900 dark:bg-gray-800 text-white dark:text-gray-300">
                <th class="py-2 px-4 text-left">ID</th>
                <th class="py-2 px-4 text-center">Select</th>
                <th class="sortable py-2 px-4 text-left whitespace-nowrap" data-sort="company_name">
                    <a href="{{ route('reminders.index', array_merge(request()->all(), ['sort_by' => 'company_name', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}">
                        Customer Name
                    </a>
                <th class="sortable py-2 px-4 text-left whitespace-nowrap" data-sort="customer_country"> <a href="{{ route('reminders.index', array_merge(request()->all(), ['sort_by' => 'customer_country', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}">
                        Country
                    </a>
                </th>
                <th class="sortable py-2 px-4 text-left whitespace-nowrap" data-sort="proofing_company_name">
                    <a href="{{ route('reminders.index', array_merge(request()->all(), ['sort_by' => 'proofing_company_name', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}">
                        Time Company
                    </a>
                </th>
                <th class="sortable py-2 px-4 text-left whitespace-nowrap" data-sort="activity_type">
                    <a href="{{ route('reminders.index', array_merge(request()->all(), ['sort_by' => 'activity_type', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}">
                        Activity Type
                    </a>
                </th>
                <th>
                    <a href="{{ route('reminders.index', array_merge(request()->all(), ['sort_by' => 'activity_updated_at', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}">
                        Last Activity
                    </a>
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach ($reminders as $reminder)
                <tr class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">

                    <td class="py-2 px-4 border-b">{{ $reminder->job_id }}</td>
                    <td class="py-2 px-4 border-b text-center"><input type="checkbox" name="selected_ids[]" value="{{ $reminder->job_id }}"></td>
                    <td class="py-2 px-4 border-b"><a href="{{ route('activities.job', ['id' => $reminder->job_id]) }}" class="btn btn-info">
                            {{ $reminder->company_name ?? 'N/A' }}
                        </a>
                    </td>
                    <td class="py-2 px-4 border-b">{{ $reminder->customer_country ?? 'N/A' }}</td>
                    <td class="py-2 px-4 border-b">{{ $reminder->proofing_company_name }}</td>
                    <td class="py-2 px-4 border-b">
                        {{ $reminder->activity_type }}
                    </td>
                    <td class="text-center border-b">
                        {{ date('d-M-y h:s', strtotime($reminder->activity_updated_at)) }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="grid grid-cols-2 w-full pt-5">
            <div class="col-span-1 text-center">
                <button type="submit" class="btn btn-primary text-red-800 hover:text-red-600">Send Reminders</button>
            </div>
            <div class="col-span-1 text-center">
                <a href="{{ $csvDownloadLink }}" class="btn btn-primary text-blue-800 hover:text-blue-600">Download CSV</a>
            </div>
        </div>
    </form>

    <!-- Pagination Links -->
    <div class="flex flex-col items-center mt-4">
        <!-- Showing x of y -->
        <div class="text-gray-600 dark:text-gray-400 mb-2">
            Showing {{ $reminders->firstItem() }} to {{ $reminders->lastItem() }} of {{ $reminders->total() }} results
        </div>
        <!-- Pagination Links -->
        <div class="mt-2">
            {{ $reminders->links('vendor.pagination.tailwind') }}
        </div>
    </div>
    @else
    <!-- No reminders message -->
        <div class="text-center mt-4">
            <p class="text-gray-500">No reminders found for the selected date.</p>
        </div>
    @endif
@endsection
