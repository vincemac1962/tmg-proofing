@extends('layouts.app')

@section('content')
    <x-section-heading class="border-teal-400">
        Customers - Index
    </x-section-heading>

    @if (session('success'))
        <div id="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Combined Filter and Sort Form -->
    <form method="GET" class="pt-5" action="{{ route('customers.index') }}" id="filterForm">
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
                <x-dropdown align="left" width="48" :active="request()->has('customer_country')">
                    <x-slot name="trigger">
            <span class="py-1 bg-white dark:bg-gray-800 text-blue-800  hover:text-blue-400 dark:text-gray-200 dark:hover:text-gray-400 text-lg leading-5">
                {{ request('customer_country', __('Filter by Country')) }}
            </span>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('customers.index', array_merge(request()->except('customer_country'), ['customer_country' => '']))">
                            {{ __('All Countries') }}
                        </x-dropdown-link>
                        @foreach ($countries as $country)
                            <x-dropdown-link :href="route('customers.index', array_merge(request()->except('customer_country'), ['customer_country' => $country]))">
                                {{ $country }}
                            </x-dropdown-link>
                        @endforeach
                    </x-slot>
                </x-dropdown>
            </div>

            <div>
                <x-dropdown align="left" width="48" :active="request()->has('perPage')">
                    <x-slot name="trigger">
            <span class="py-1 bg-white dark:bg-gray-800 text-blue-800  hover:text-blue-400 dark:text-gray-200 dark:hover:text-gray-400 text-lg leading-5">
                {{ request('perPage', 25) }} {{ __('Records per Page') }}
            </span>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('customers.index', array_merge(request()->except('perPage'), ['perPage' => 5]))">
                            5
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('customers.index', array_merge(request()->except('perPage'), ['perPage' => 10]))">
                            10
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('customers.index', array_merge(request()->except('perPage'), ['perPage' => 25]))">
                            25
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('customers.index', array_merge(request()->except('perPage'), ['perPage' => 50]))">
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
                <button type="submit" class="hover:text-blue-400 dark:text-gray-200 dark:hover:text-gray-400 leading-5">
                    Apply Filters
                </button>
                <!-- Clear filters -->
                <a href="{{ route('customers.index') }}" class="text-red-800 hover:text-red-600 pl-5">
                    Clear Filters
                </a>
            </div>
    </form>
    <!-- Customers Table -->
    <div class="w-full">
    <table class="w-full border-collapse mt-16 mx-auto">
        <thead>
        <tr class="bg-teal-700  text-white dark:text-gray-300">
            <th class="py-2 px-4 text-left">ID</th>
            <th class="sortable py-2 px-4 text-left whitespace-nowrap" data-sort="contract_reference">
                C/N
                @if (request('sort') === 'contract_reference')
                    {{ request('order') === 'asc' ? '▼' : '▲' }}
                @endif
            <th class="sortable py-2 px-4 text-left whitespace-nowrap" data-sort="company_name">
                Company Name
                @if (request('sort') === 'company_name')
                    {{ request('order') === 'asc' ? '▼' : '▲' }}
                @endif
            </th>
            <th class="sortable py-2 px-4 text-left whitespace-nowrap" data-sort="customer_city">
                City
                @if (request('sort') === 'customer_city')
                    {{ request('order') === 'asc' ? '▼' : '▲' }}
                @endif
            </th>
            <th class="sortable py-2 px-4 text-left whitespace-nowrap" data-sort="customer_country">
                Country
                @if (request('sort') === 'customer_country')
                    {{ request('order') === 'asc' ? '▼' : '▲' }}
                @endif
            </th>
            <th>
            <!-- Action Column -->
            </th>
            <th>
                <!-- Action Column -->
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach ($customers as $customer)
            <tr class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer"
            onclick="window.location='{{ route('customers.show', $customer->id) }}'">

                <td class="py-2 px-4 border-b">{{ $customer->id }}</td>
                <td class="py-2 px-4 border-b">{{ $customer->contract_reference }}</td>
                <td class="py-2 px-4 border-b">{{ $customer->company_name }}</td>
                <td class="py-2 px-4 border-b">{{ $customer->customer_city }}</td>
                <td class="py-2 px-4 border-b">{{ $customer->customer_country }}</td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('customers.show', $customer->id) }}" class="text-blue-600 hover:text-blue-800">
                        View Details
                    </a>
                </td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('customers.edit', $customer->id) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                </td>
            </tr>
        @endforeach



        </tbody>
    </table>
        <!-- Pagination Links -->
        <div class="flex flex-col items-center mt-4">
            <!-- Showing x of y -->
            <div class="text-gray-600 dark:text-gray-400 mb-2">
                Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} results
            </div>
            <!-- Pagination Links -->
            <div class="mt-2">
                {{ $customers->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>



@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Sorting functionality
            const sortables = document.querySelectorAll('.sortable');
            const form = document.getElementById('filterForm');
            const sortInput = document.getElementById('sort');
            const orderInput = document.getElementById('order');

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

            // Success message auto-hide functionality
            const successMessage = document.getElementById('successMessage');
            console.log(successMessage); // Debugging
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.display = 'none';
                    console.log('Success message hidden'); // Debugging
                }, 5000); // 5000ms = 5 seconds
            }
        });
    </script>
@endpush
