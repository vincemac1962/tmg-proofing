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
                <div class="col-span-1 w-1/2">
                    <label for="contract_reference" class="block text-sm font-medium text-gray-700 mb-1">
                        C/N
                    </label>
                    <div id="contract_reference" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                        {{ $customer->contract_reference }}
                    </div>
                </div>
                <div class="mb-2 col-span-1">
                    <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">
                        Name
                    </label>
                    <div id="company_name" class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                        {{ $customer->company_name }}
                    </div>
                </div>
                <div class="col-span-4"></div>
                <!-- second row -->
                <div class="md:col-span-1 mb-2">
                    <label for="customer_city" class="block text-sm font-medium text-gray-700 mb-1">
                        City
                    </label>
                    <div id="customer_city" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                        {{ $customer->customer_city }}
                    </div>
                </div>
                <div class="col-span-5"></div>
                <!-- third row -->
                <div class="col-span-1">
                    <label for="customer_country" class="block text-sm font-medium text-gray-700 mb-1">
                        Country
                    </label>
                    <div id="customer_country" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                        {{ $customer->customer_country }}
                    </div>
                </div>
                <div class="col-span-5">
                </div>
               @foreach($activities->groupBy('job_id') as $jobId => $groupedActivities)
                    <div class="col-span-2 text-xl text-emerald-800"><strong>Proofing Job ID: {{ $jobId }}</strong></div>
                    <div class="col-span-4"></div>
                    <div class="col-span-6">
                        <table class="w-full border-collapse mt-16 mx-auto">
                            <thead>
                            <tr class="bg-emerald-800 dark:bg-gray-800 text-white dark:text-gray-300">
                                <th class="py-2 px-4 text-center">ID</th>
                                <th class="py-2 px-4 text-center">Timestamp</th>
                                <th class="py-2 px-4 text-center">User</th>
                                <th class="py-2 px-4 text-center">Activity Type</th>
                                <th class="py-2 px-4 text-center">IP Address</th>
                                <th class="py-2 px-4 text-center">Notes</th>
                                <th class="py-2 px-4 text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($groupedActivities as $activity)
                            <tr>
                                <td class="text-center py-2 px-4 border-b">{{ $activity->job_id }}</td>
                                <td class="text-center py-2 px-4 border-b">{{ $activity->updated_at->format('d-m-Y') }}</td>
                                <td class="text-center py-2 px-4 border-b">{{ $activity->user_name }}</td>
                                <td class="text-center py-2 px-4 border-b">{{ $activity->activity_type }}</td>
                                <td class="text-center py-2 px-4 border-b">{{ $activity->ip_address }}</td>
                                <td class="text-center py-2 px-4 border-b">
                                    @if(!empty($activity->notes))
                                        <a href="{{ route('activities.show', $activity) }}">{{ Str::limit($activity->notes, 10, '...See Notes') }}</a>
                                    @else
                                        <a href="{{ route('activities.edit', $activity->id) }}"><span>No notes - Click to add</span></a>
                                    @endif
                                </td>
                                <td  class="text-center py-2 px-4 border-b">
                                    <a href="{{ route('activities.show', $activity) }}"><span>View</span></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                        <div class="button col-span-6 py-2 text-center" >
                            <a class="btn btn-primary text-red-800 hover:text-red-600" onclick="history.back()">Back</a>
                        </div>
                    </div>
            </div>
                @endforeach
            @else
                <p>No activity found for this customer.</p>
            @endif

@endsection

