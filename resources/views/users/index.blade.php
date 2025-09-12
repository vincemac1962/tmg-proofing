<!-- resources/views/users/index.blade.php -->

@extends('layouts.app')

@section('content')
    <x-section-heading class="border-lime-700">
        Users - Index
    </x-section-heading>
    @if (session('success'))
        <div id="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Combined Filter and Sort Form -->
    <form method="GET" action="{{ route('users.index') }}" id="filterForm" class="mb-4">
        <div class="flex flex-row items-center mt-4 space-x-6">
            <!-- Date Range Pickers -->
            <div class="flex space-x-2">
                <x-text-input
                        type="text"
                        id="search"
                        name="search"
                        value=""
                        class="w-40"
                        placeholder="Search by name or email" />
            </div>

            <!-- User Type Dropdown -->
            <div>
                <x-dropdown align="left" width="48" :active="request()->has('role')">
                    <x-slot name="trigger">
            <span class="py-1 text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 leading-5">
                {{ ucfirst(request('role', __('Filter by Role'))) }}
            </span>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('users.index', array_merge(request()->except('role'), ['role' => '']))">
                            {{ __('All Roles') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('users.index', array_merge(request()->except('role'), ['role' => 'admin']))">
                            Admin
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('users.index', array_merge(request()->except('role'), ['role' => 'customer']))">
                            Customer
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('users.index', array_merge(request()->except('role'), ['role' => 'designer']))">
                            Designer
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            </div>


            <!-- Records Per Page Dropdown -->
            <div>
                <x-dropdown align="left" width="48" :active="request()->has('perPage')">
                    <x-slot name="trigger">
                        <span class="py-1 text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 leading-5">
                            {{ request('perPage', 25) }} {{ __('Records per Page') }}
                        </span>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('users.index', array_merge(request()->except('perPage'), ['perPage' => 5]))">
                            5
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('users.index', array_merge(request()->except('perPage'), ['perPage' => 10]))">
                            10
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('users.index', array_merge(request()->except('perPage'), ['perPage' => 25]))">
                            25
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('users.index', array_merge(request()->except('perPage'), ['perPage' => 50]))">
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
            <a href="{{ route('users.index') }}" class="text-red-800 hover:text-red-600 pl-5">
                Reset Filters
            </a>
        </div>
        <input type="hidden" id="sort" name="sort" value="{{ request('sort', 'name') }}">
        <input type="hidden" id="order" name="order" value="{{ request('order', 'asc') }}">
    </form>

    <table class="w-full border-collapse mt-16 mx-auto">
        <thead>
        <tr class="bg-lime-800 dark:bg-lime-800 text-white dark:text-gray-300">
            <th class="py-2 px-4">ID</th>
            <th class="py-2 px-4 sortable" data-sort="name">
                Name
                @if (request('sort') === 'name')
                    {{ request('order') === 'asc' ? '▲' : '▼' }}
                @endif
            </th>
            <th class="py-2 px-4 sortable" data-sort="email">
                Email
                @if (request('sort') === 'email')
                    {{ request('order') === 'asc' ? '▲' : '▼' }}
                @endif
            </th>
            <th class="py-2 px-4 sortable" data-sort="role">
                Role
                @if (request('sort') === 'role')
                    {{ request('order') === 'asc' ? '▲' : '▼' }}
                @endif
            </th>
            <th class="py-2 px-4 sortable" data-sort="is_active">
                Active
                @if (request('sort') === 'is_active')
                    {{ request('order') === 'asc' ? '▲' : '▼' }}
                @endif
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 text-center" onclick="window.location='{{ route('users.show', $user->id) }}'">
                <td class="py-2 px-4 border-b">{{ $user->id }}</td>
                <td class="py-2 px-4 border-b">{{ $user->name }}</td>
                <td class="py-2 px-4 border-b">{{ $user->email }}</td>
                <td class="py-2 px-4 border-b">{{ $user->role }}</td>
                <td class="py-2 px-4 border-b">{{ $user->is_active ? 'true' : 'false' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <!-- Pagination Links -->
    <div class="flex flex-col items-center mt-4">
        <!-- Showing x of y -->
        <div class="text-gray-600 dark:text-gray-400 mb-2">
            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
        </div>
        <!-- Pagination Links -->
        <div class="mt-2">
            {{ $users->links('vendor.pagination.tailwind') }}
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
