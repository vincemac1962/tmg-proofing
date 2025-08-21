<!-- resources/views/activities/show.blade.php -->

@extends('layouts.app')

@section('content')
    <x-section-heading class="border-fuchsia-700">
       Show Activity: {{ $activity->id }} for {{ $activity->proofingJob->customer->company_name }}
    </x-section-heading>
    <div class="grid grid-cols-5 gap-4 pt-5 w-3/4">
            <!-- first row -->
            <div class="mb-2 col-span-1">
                <label for="job_id" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                    Job ID
                </label>
                <div id="job_id" class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    {{ $activity->job_id }}
                </div>
            </div>

            <div class="md:col-span-1 mb-2">
                <label for="contract_reference" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                    Contract Reference
                </label>
                <div id="contract_reference" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    {{ $activity->proofingJob->contract_reference }}
                </div>
            </div>
        <div class="md:col-span-2 mb-2">
                <label for="timestamp" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                    Timestamp
                </label>
                <div id="timestamp" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    {{ $activity->updated_at->format('d-m-Y H:i:s') }}
                </div>
            </div>
            <div class="col-span-1"></div>
            <!-- second row -->
            <div class="mb-2 col-span-2">
                <label for="proofing_company" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                    Proofing Company
                </label>
                <div id="proofing_company" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    {{ $activity->proofingJob->proofingCompany->name }}
                </div>
            </div>
            <div class="col-span-2">
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                    Status
                </label>
                <div id="status" class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    {{ $activity->proofingJob->status }}
                </div>
            </div>
            <div class="col-span-1"></div>
            <!-- third row-->
        @if(!empty($activity->notes))
        <div class="col-span-4">
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                Notes
            </label>
            <div id="status" class="col-span-4 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $activity->notes }}
            </div>
        </div>
        @endif
        <!-- fourth row -->

        <div class="col-span-1 text-center">
            <a class="btn btn-primary text-red-800 hover:text-red-600" onclick="history.back()">Back</a>
        </div>
        <div class="col-span-2 text-center">
            <a class="btn btn-primary text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 pl-5" href="{{ route('activities.edit', $activity) }}">Edit</a>
        </div>
        <div class="col-span-1 text-center">
            <form method="POST" action="{{ route('activities.destroy', $activity) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-primary text-red-800 hover:text-red-600">Delete</button>
            </form>
        </div>
    </div>

@endsection