<!-- resources/views/reports/customer_list.blade.php -->
@extends('layouts.app')

@section('content')

    <x-section-heading class="border-emerald-700 mb-5">
        Reports - Select Customer
    </x-section-heading>

    <!-- Combined Filter and Sort Form -->
    <form method="GET" class="pt-5" action="{{ route('reports.view', $report->id) }}" id="filterForm">
        <div class="flex flex-row items-center mt-4 space-x-6">
            <div>

                <x-text-input
                        type="text"
                        name="search"
                        placeholder="Search on Name or C/N"
                        value="{{ request('search') }}"
                        class="w-64"
                />
            </div>


            <div>
                <x-dropdown align="left" width="48" :active="request()->has('perPage')">
                    <x-slot name="trigger">
        <span class="py-1 bg-white dark:bg-gray-700 text-lg text-blue-800 hover:text-blue-400 leading-5">
            {{ request('perPage', 10) }} {{ __('Records per Page') }}
        </span>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('reports.view', array_merge(['id' => $report->id], request()->except('perPage'), ['perPage' => 5]))">
                            5
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('reports.view', array_merge(['id' => $report->id], request()->except('perPage'), ['perPage' => 10]))">
                            10
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('reports.view', array_merge(['id' => $report->id], request()->except('perPage'), ['perPage' => 25]))">
                            25
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('reports.view', array_merge(['id' => $report->id], request()->except('perPage'), ['perPage' => 50]))">
                            50
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            </div>
            <!-- Hidden fields for sorting -->
            <input type="hidden" id="sort" name="sort" value="{{ request('sort', 'company_name') }}">
            <input type="hidden" id="order" name="order" value="{{ request('order', 'asc') }}">
        </div>
        <div class="flex flex-row items-center mt-4 space-x-2">
            <!-- Apply filters/search -->
            <button type="submit" class="text-blue-800 hover:text-blue-600">
                Apply Filters
            </button>
            <!-- Clear filters -->
            <a href="{{ route('reports.view', $report->id) }}" class="text-red-800 hover:text-red-600 pl-5">
                Clear Filters
            </a>
        </div>
    </form>

    <table class="w-full border-collapse mt-16 mx-auto">
        <thead>
        <tr class="bg-emerald-800 dark:bg-gray-800 text-white dark:text-gray-300">
            <th class="py-2 px-4 text-left">ID</th>
            <th class="sortable py-2 px-4 text-left whitespace-nowrap" data-sort="contract_reference">
                C/N
                <span class="sort-indicator">
            @if (request('sort') === 'contract_reference')
                        {{ request('order') === 'asc' ? '▼' : '▲' }}
                    @endif
        </span>
            </th>
            <th class="sortable py-2 px-4 text-left whitespace-nowrap" data-sort="company_name">
                Company Name
                <span class="sort-indicator">
            @if (request('sort') === 'company_name')
                        {{ request('order') === 'asc' ? '▼' : '▲' }}
                    @endif
        </span>
            </th>
            <th class="sortable py-2 px-4 text-left whitespace-nowrap" data-sort="customer_city">
                City
                <span class="sort-indicator">
            @if (request('sort') === 'customer_city')
                        {{ request('order') === 'asc' ? '▼' : '▲' }}
                    @endif
        </span>
            </th>
            <th class="sortable py-2 px-4 text-left whitespace-nowrap" data-sort="customer_country">
                Country
                <span class="sort-indicator">
            @if (request('sort') === 'customer_country')
                        {{ request('order') === 'asc' ? '▼' : '▲' }}
                    @endif
        </span>
            </th>
        </tr>
        <tbody>
        @foreach ($customers as $customer)
            <tr class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer"
                onclick="window.location='{{ route('reports.customer_activity', ['customerId' => $customer->id, 'reportName' => $reportName]) }}'">
                <td class="py-2 px-4 border-b">{{ $customer->id }}</a></td>
                <td class="py-2 px-4 border-b">{{ $customer->contract_reference }}</td>
                <td class="py-2 px-4 border-b">{{ $customer->company_name }}</a></td>
                <td class="py-2 px-4 border-b">{{ $customer->customer_city }}</td>
                <td class="py-2 px-4 border-b">{{ $customer->customer_country }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
@push('scripts')
    <script>
        // Add this to your JavaScript
        document.querySelectorAll('.sortable').forEach(header => {
            header.addEventListener('click', function() {
                const sort = this.dataset.sort;
                const currentOrder = document.getElementById('order').value;
                const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';

                document.getElementById('sort').value = sort;
                document.getElementById('order').value = newOrder;
                document.getElementById('filterForm').submit();
            });
        });
    </script>

@endpush