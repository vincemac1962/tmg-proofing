<!-- resources/views/activities/index_by_job_id.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="p-6">
        <x-section-heading class="border-fuchsia-700">
            Activity - Log for {{ $activities->first()->company_name }}
        </x-section-heading>

        @if (session('success'))
            <x-alert-success>
                {{ session('success') }}
            </x-alert-success>
        @endif

        <div class="grid grid-cols-5 gap-4 pt-5 w-full">
            @if($activities->isNotEmpty())

                    <!-- first row -->
                    <div class="mb-2 col-span-1">
                        <label for="job_id" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                            Job ID
                        </label>
                        <div id="job_id" class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            {{ $activities->first()->job_id }}
                        </div>
                    </div>

                    <div class="md:col-span-1 mb-2">
                        <label for="contract_reference" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                            Contract Reference
                        </label>
                        <div id="contract_reference" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            {{ $activities->first()->contract_reference }}
                        </div>
                    </div>
                    <div class="col-span-3"></div>
                    <!-- second row -->
                    <div class="mb-2 col-span-2">
                        <label for="proofing_company" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                            Proofing Company
                        </label>
                        <div id="proofing_company" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            {{ $activities->first()->proofing_company_name }}
                        </div>
                    </div>
                    <div class="col-span-2">
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                            Status
                        </label>
                        <div id="status" class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            {{ $activities->first()->status }}
                        </div>
                    </div>
                    <div class="col-span-2"></div>
                    <!-- third row - activity table-->
                    <div class="w-full md:col-span-5 mb-2">
                            <table class="w-full border-collapse mt-5 mx-auto">
                                <thead>
                                <!-- Title Row -->
                                <tr>
                                    <td colspan="7" class="text-center text-lg font-bold py-4 dark:text-gray-100">
                                        Proofs sent for {{ $activities->first()->company_name }}
                                    </td>
                                </tr>
                                <!-- Header Row -->
                                <tr class="bg-fuchsia-700 text-white">
                                    <th class="py-2 px-4 text-center">ID</th>
                                    <th class="py-2 px-4 text-center">Timestamp</th>
                                    <th class="py-2 px-4 text-center">User</th>
                                    <th class="py-2 px-4 text-center">Activity Type</th>
                                    <th class="py-2 px-4 text-center">IP Address</th>
                                    <th class="py-2 px-4 text-center">Notes</th>
                                    <th class="py-2 px-4 text-center"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <!-- Data Rows -->
                                @foreach($activities as $activity)
                                    <tr>
                                        <td class="py-2 px-4 text-center dark:text-gray-100">{{ $activity->job_id }}</td>
                                        <td class="py-2 px-4 text-center dark:text-gray-100">{{ $activity->updated_at->format('d-m-Y H:i:s') }}</td>
                                        <td class="py-2 px-4 text-center dark:text-gray-100">{{ $activity->user_name }}</td>
                                        <td class="py-2 px-4 text-center dark:text-gray-100">{{ $activity->activity_type }}</td>
                                        <td class="py-2 px-4 text-center dark:text-gray-100">{{ $activity->ip_address }}</td>
                                        <td class="py-2 px-4 text-center dark:text-gray-100">
                                            @if(!empty($activity->notes))
                                                <a href="{{ route('activities.show', $activity) }}">{{ Str::limit($activity->notes, 10, '...See Notes') }}</a>
                                            @else
                                                <a href="{{ route('activities.edit', $activity->id) }}"><span>No notes - Click to add</span></a>
                                        @endif
                                        <td class="py-2 px-4 text-center text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400">
                                            <a href="{{ route('activities.show', $activity) }}"><span>View</span></a>
                                        </td>
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                    </div>
                <div class="col-span-1"></div>
            @else
                            <p class="text-center text-gray-500">No activity logged for this job.</p>
            @endif
            <div class="col-span-5 text-center">
                <a class="btn btn-primary text-red-800 hover:text-red-600" onclick="history.back()">Cancel</a>
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