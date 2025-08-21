<!-- resources/views/reports/customer_activity.blade.php -->

@extends('layouts.app')

@section('content')
    <x-section-heading class="border-emerald-700 mb-5">
        Reports - Customer Activity History
    </x-section-heading>
        @if($activities->isNotEmpty())
            <!-- first row -->
            <div class="grid grid-cols-6 gap-4 pt-5 w-full">
                <!-- first row -->
                <div class="col-span-1 w-full">
                    <label for="contract_reference" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                        C/N
                    </label>
                    <div id="contract_reference" class="px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        {{ $customer->contract_reference }}
                    </div>
                </div>
                <div class="mb-2 col-span-3">
                    <label for="company_name" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                        Name
                    </label>
                    <div id="company_name" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        {{ $customer->company_name }}
                    </div>
                </div>
                <div class="col-span-2"></div>
                <!-- second row -->
                <div class="md:col-span-2 mb-2">
                    <label for="customer_city" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                        City
                    </label>
                    <div id="customer_city" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        {{ $customer->customer_city }}
                    </div>
                </div>
                <div class="col-span-2">
                    <label for="customer_country" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                        Country
                    </label>
                    <div id="customer_country" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        {{ $customer->customer_country }}
                    </div>
                </div>
                <div class="col-span-2"></div>
                <!-- third row -->
               @foreach($activities->groupBy('job_id') as $jobId => $groupedActivities)
                    <div class="col-span-2 text-xl text-gray-900 dark:text-gray-100"><strong>Proofing Job ID: {{ $jobId }}</strong></div>
                    <div class="col-span-4"></div>
                    <div class="col-span-6">
                        <table class="w-full border-collapse  mx-auto">
                            <thead>
                            <tr class="bg-emerald-800 dark:bg-gray-600 text-white dark:text-gray-300">
                                <th class="py-2 px-4 text-center text-gray-900 dark:text-gray-100">ID</th>
                                <th class="py-2 px-4 text-center text-gray-900 dark:text-gray-100">Timestamp</th>
                                <th class="py-2 px-4 text-center text-gray-900 dark:text-gray-100">User</th>
                                <th class="py-2 px-4 text-center text-gray-900 dark:text-gray-100">Activity Type</th>
                                <th class="py-2 px-4 text-center text-gray-900 dark:text-gray-100">IP Address</th>
                                <th class="py-2 px-4 text-center text-gray-900 dark:text-gray-100">Notes</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($groupedActivities as $activity)
                            <tr onclick="window.location='{{ route('activities.show', $activity->id) }}'" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                <td class="py-2 px-4 text-center text-gray-900 dark:text-gray-100">{{ $activity->job_id }}</td>
                                <td class="py-2 px-4 text-center text-gray-900 dark:text-gray-100">{{ $activity->updated_at->format('d-m-Y') }}</td>
                                <td class="py-2 px-4 text-center text-gray-900 dark:text-gray-100">{{ $activity->user_name }}</td>
                                <td class="py-2 px-4 text-center text-gray-900 dark:text-gray-100">{{ $activity->activity_type }}</td>
                                <td class="py-2 px-4 text-center text-gray-900 dark:text-gray-100">{{ $activity->ip_address }}</td>
                                <td class="py-2 px-4 text-center text-gray-900 dark:text-gray-100">
                                    @if(!empty($activity->notes))
                                        <a href="{{ route('activities.show', $activity) }}" class="text-red-800 hover:text-red-600 pl-5">{{ Str::limit($activity->notes, 10, '...See Notes') }}</a>
                                    @else
                                        <a href="{{ route('activities.edit', $activity->id) }} " class="text-red-800 hover:text-red-600 pl-5"><span>No notes - Click to add</span></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                        <div class="button col-span-6 py-2 text-center" >
                            <hr class="my-4 border-gray-300 dark:border-gray-600">
                        </div>
                    </div>
                        <div class="button col-span-6 text-center" >
                            <a class="btn btn-primary text-red-800 hover:text-red-600" onclick="history.back()">Back</a>
                        </div>
                    </div>
            </div>
                @endforeach
            @else
                <p>No activity found for this customer.</p>
            @endif

@endsection

