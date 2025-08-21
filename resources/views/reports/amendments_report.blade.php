<!-- resources/views/reports/amendments_report.blade.php -->
@extends('layouts.app')

@section('content')
    <x-section-heading class="border-emerald-900">
        Reporting - Amendments for Period
    </x-section-heading>
    <!-- Filter Form -->
    <form method="GET" action="{{ route('reports.amendments_report') }}" class="mb-4">
        <!-- Hidden Inputs for Sorting -->
        <input type="hidden" id="sort" name="sort_by" value="{{ request('sort_by') }}">
        <input type="hidden" id="order" name="sort_order" value="{{ request('sort_order', 'asc') }}">
        <!-- Date Range and Filters -->
        <div class="flex flex-row items-center mt-4 space-x-6">
            <!-- Date Range Pickers -->
            <div class="flex space-x-2">
                <x-text-input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ request('start_date') ?? $startDate }}"
                        class="w-40"
                />
                <span class="text-gray-500">to</span>
                <x-text-input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ request('end_date') ?? $endDate }}"
                        class="w-40"
                />
            </div>

            <!-- Country Dropdown -->
            <div>
                <x-dropdown align="left" width="48" :active="request()->has('country')">
                    <x-slot name="trigger">
                        <span class="py-1 bg-white dark:bg-gray-800 text-blue-800  hover:text-blue-400 dark:text-gray-200 dark:hover:text-gray-400 text-lg leading-5">
                            {{ request('country', __('Filter by Country')) }}
                        </span>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('reports.amendments_report', array_merge(request()->except('country'), ['country' => '']))">
                            {{ __('All Countries') }}
                        </x-dropdown-link>
                        @foreach ($countries as $country)
                            <x-dropdown-link :href="route('reports.amendments_report', array_merge(request()->except('country'), ['country' => $country]))">
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
                        <span class="py-1 bg-white dark:bg-gray-800 text-blue-800  hover:text-blue-400 dark:text-gray-200 dark:hover:text-gray-400 text-lg leading-5">
                            {{ request('proofing_company', __('Filter by Proofing Company')) }}
                        </span>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('reports.amendments_report', array_merge(request()->except('proofing_company'), ['proofing_company' => '']))">
                            {{ __('All Companies') }}
                        </x-dropdown-link>
                        @foreach ($proofingCompanies as $company)
                            <x-dropdown-link :href="route('reports.amendments_report', array_merge(request()->except('proofing_company'), ['proofing_company' => $company]))">
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
                        <span class="py-1 bg-white dark:bg-gray-800 text-blue-800  hover:text-blue-400 dark:text-gray-200 dark:hover:text-gray-400 text-lg leading-5">
                            {{ request('perPage', 25) }} {{ __('Records per Page') }}
                        </span>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('reports.amendments_report', array_merge(request()->except('perPage'), ['perPage' => 5]))">
                            5
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('reports.amendments_report', array_merge(request()->except('perPage'), ['perPage' => 10]))">
                            10
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('reports.amendments_report', array_merge(request()->except('perPage'), ['perPage' => 25]))">
                            25
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('reports.amendments_report', array_merge(request()->except('perPage'), ['perPage' => 50]))">
                            50
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>

        <!-- Filter and Reset Buttons -->
        <div class="flex flex-row items-center mt-4 space-x-2">
            <button type="submit" class="hover:text-blue-400 dark:text-gray-200 dark:hover:text-gray-400 leading-5">
                Apply Filters
            </button>
            <a href="{{ route('reports.amendments_report') }}" class="text-red-800 hover:text-red-600 pl-5">
                Reset Filters
            </a>
        </div>
    </form>

    <!-- Displaying the chosen dates -->
    <p class="text-gray-900 dark:text-gray-100">Showing records between  {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }} and {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}</p>

        <!-- Report Content -->
@if($amendments->isNotEmpty())
    <div class="grid grid-cols-2">
        <div class="col-span-2">
                <table class="w-full border-collapse  mt-5 mx-auto">
                    <thead>
                    <tr class="bg-emerald-900 dark:bg-emerald-900 text-white dark:text-gray-300">
                        <th class="py-2 px-4 text-left">ID</th>
                        <th class="py-2 px-4 text-center" data-sort="amendment_date">
                            <a href="{{ route('reports.amendments_report', array_merge(request()->all(), ['sort_by' => 'amendment_date', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}">
                            Date
                                @if(request('sort_by') === 'amendment_date')
                                    @if(request('sort_order') === 'asc')
                                        &#9650; <!-- Up arrow for ascending -->
                                    @else
                                        &#9660; <!-- Down arrow for descending -->
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th class="sortable py-2 px-4 text-left whitespace-nowrap" data-sort="contract_reference">
                            <a href="{{ route('reports.amendments_report', array_merge(request()->all(), ['sort_by' => 'contract_reference', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}">
                                C/N
                                @if(request('sort_by') === 'contract_reference')
                                    @if(request('sort_order') === 'asc')
                                        &#9650; <!-- Up arrow for ascending -->
                                    @else
                                        &#9660; <!-- Down arrow for descending -->
                                    @endif
                                @endif
                            </a>
                        <th class="sortable py-2 px-4 text-left whitespace-nowrap" data-sort="customer_name">
                            <a href="{{ route('reports.amendments_report', array_merge(request()->all(), ['sort_by' => 'customer_name', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}">
                                Customer Name
                                @if(request('sort_by') === 'customer_name')
                                    @if(request('sort_order') === 'asc')
                                        &#9650; <!-- Up arrow for ascending -->
                                    @else
                                        &#9660; <!-- Down arrow for descending -->
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th class="sortable py-2 px-4 text-left whitespace-nowrap" data-sort="customer_country">
                            <a href="{{ route('reports.amendments_report', array_merge(request()->all(), ['sort_by' => 'customer_country', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}">
                                Customer Country
                                @if(request('sort_by') === 'customer_country')
                                    @if(request('sort_order') === 'asc')
                                        &#9650; <!-- Up arrow for ascending -->
                                    @else
                                        &#9660; <!-- Down arrow for descending -->
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th class="sortable py-2 px-4 text-left whitespace-nowrap" data-sort="proofing_company_name">
                            <a href="{{ route('reports.amendments_report', array_merge(request()->all(), ['sort_by' => 'proofing_company_name', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}">
                                Proofing Company
                                @if(request('sort_by') === 'proofing_company_name')
                                    @if(request('sort_order') === 'asc')
                                        &#9650; <!-- Up arrow for ascending -->
                                    @else
                                        &#9660; <!-- Down arrow for descending -->
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th class="sortable py-2 px-4 text-left whitespace-nowrap text-center" data-sort="designer_name">
                            <a href="{{ route('reports.amendments_report', array_merge(request()->all(), ['sort_by' => 'designer_name', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}">
                                Designer
                                @if(request('sort_by') === 'designer_name')
                                    @if(request('sort_order') === 'asc')
                                        &#9650; <!-- Up arrow for ascending -->
                                    @else
                                        &#9660; <!-- Down arrow for descending -->
                                    @endif
                                @endif
                            </a>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($amendments as $amendment)
                        <tr class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">

                            <td class="py-2 px-4 border-b">{{ $amendment->id }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ \Carbon\Carbon::parse($amendment->amendment_date)->format('d-m-Y H:i') }}</td>
                            <td class="py-2 px-4 border-b">{{ $amendment->contract_reference }}</td>
                            <td class="py-2 px-4 border-b">{{ $amendment->customer_name }}</td>
                            <td class="py-2 px-4 border-b">{{ $amendment->customer_country }}</td>
                            <td class="py-2 px-4 border-b">{{ $amendment->proofing_company_name }}</td>
                            <td class="text-center border-b">{{ $amendment->designer_name }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
        </div>
            <div class="col-span-1 text-center mt-5">
                <button class="btn btn-primary text-red-800 hover:text-red-600" onclick="history.back()">Back</button>
            </div>
            <div class="col-span-1 text-center mt-5">
                    <button class="btn btn-primary text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 pl-5">
                        <a href="{{ route('reports.download', $report->report_view) }}"> Generate and Download CSV</a>
                    </button>
            </div>
        <div class="col-span-2 flex justify-center">
            {{ $amendments->links() }}
        </div>
</div>

@else
    <div class="text-center mt-4">
        <p class="text-gray-500">No amendments found for the selected date range.</p>
    </div>
@endif

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sortables = document.querySelectorAll('.sortable');
            const form = document.getElementById('filterForm');
            const sortInput = document.getElementById('sort');
            const orderInput = document.getElementById('order');

            console.log('Sortables:', sortables); // Check if sortable elements are found

            sortables.forEach(header => {
                header.addEventListener('click', function() {
                    const sortField = this.dataset.sort;
                    const currentSort = sortInput.value;
                    const currentOrder = orderInput.value;

                    if (sortField === currentSort) {
                        orderInput.value = currentOrder === 'asc' ? 'desc' : 'asc';
                    } else {
                        sortInput.value = sortField;
                        orderInput.value = 'asc';
                    }

                    form.submit();
                });
            });
        });
    </script>
@endpush