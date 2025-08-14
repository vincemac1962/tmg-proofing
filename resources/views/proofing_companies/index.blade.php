<!-- resources/views/proofing_companies/index.php -->

@extends('layouts.app')

@section('content')
<x-section-heading class="border-rose-700">
    Proofing Companies - Index
</x-section-heading>
    <div class="py-2">
        @if(session('success'))
            <div id="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        <form method="GET" action="{{ route('proofing_companies.index') }}" class="flex flex-row items-center space-x-2">
            <label for="show_all" class="text-lg font-medium text-gray-700">Show All Companies</label>
            <input type="checkbox" id="show_all" name="show_all" value="1" onchange="this.form.submit()" {{ request('show_all') ? 'checked' : '' }}>
        </form>
  <!-- proofing companies table-->
    <div class="grid grid-cols-6 mt-5">
                <div class="w-full md:col-span-6 mb-2">
                    <table class="w-full border-collapse mx-auto">
                        <thead>
                        <!-- Header Row -->
                        <tr class="bg-fuchsia-700 text-white">
                            <th class="py-2 px-4 text-center">ID</th>
                            <th class="py-2 px-4 text-center">Name</th>
                            <th class="py-2 px-4 text-center">Email</th>
                            <th class="py-2 px-4 text-center">Active</th>
                            <th class="py-2 px-4 text-center"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Data Rows -->
                        @foreach($proofingCompanies as $company)
                            <tr class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer"
                                onclick="window.location='{{ route('proofing_companies.show', $company) }}'">
                                <td class="py-2 px-4 text-center">{{ $company->id }}</td>
                                <td class="py-2 px-4 text-center">{{ $company->name }}</td>
                                <td class="py-2 px-4 text-center">{{ $company->email_address }}</td>
                                <td class="py-2 px-4 text-center">{{ $company->active ? 'true' : 'false' }}</td>
                                <td>
                                    <a href="{{ route('proofing_companies.edit', $company) }}">Edit</a>
                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>

            <!-- cancel button -->
            <div class="col-span-3 text-center">
                <a class="btn btn-primary text-red-800 hover:text-red-600" onclick="history.back()">Cancel</a>
            </div>
            <div class="col-span-3 text-center">
                <a href="{{ route('proofing_companies.create') }}" class="text-blue-800 hover:text-blue-600">
                    Create New Company
                </a>
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
