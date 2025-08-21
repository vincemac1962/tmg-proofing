<!-- resources/views/proofing_jobs/show.blade.php -->
@extends('layouts.app')

@section('content')
    <x-section-heading class="border-indigo-400">
        Proofing Job - Details
    </x-section-heading>
    <div class="grid grid-cols-6 gap-4 pt-5 w-full bg-white dark:bg-gray-800 shadow rounded-lg">
        <!-- first row -->
        <div class="mb-2 col-span-1">
            <label for="id" class="block text-sm font-medium text-gray-700 dark:text-gray-100  mb-1">
                ID
            </label>
            <div id="id" class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100  dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $proofingJob->id }}
            </div>
        </div>
        <div class="mb-2 col-span-1">
            <label for="contract_reference" class="block text-sm font-medium text-gray-700 dark:text-gray-100  mb-1">
                Contract Reference
            </label>
            <div id="contract_reference" class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $proofingJob->contract_reference }}
            </div>
        </div>
        <div class="col-span-4"></div>
        <!-- second row -->
        <div class="md:col-span-3 mb-2">
            <label for="company_name" class="block text-sm font-medium text-gray-700 dark:text-gray-100  mb-1">
                Company Name
            </label>
            <!-- third row -->
            <div id="company_name" class="col-span-3 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $customer->company_name }}
            </div>
        </div>
        <div class=col-span-3></div>
        <div class="md:col-span-1 mb-2">
            <label for="created_at" class="block text-sm font-medium text-gray-700 dark:text-gray-100  mb-1">
                Created
            </label>
            <div id="created_at" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $proofingJob->created_at->format('d-m-Y') }}
            </div>
        </div>
        <div class="md:col-span-1 mb-2">
            <label for="updated_at" class="block text-sm font-medium text-gray-700 dark:text-gray-100  mb-1">
                Last Updated
            </label>
            <div id="updated_at" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $proofingJob->updated_at->format('d-m-Y ') }}
            </div>
        </div>
        <div class="col-span-4"></div>
        <div class="md:col-span-2 mb-2">
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-100  mb-1">
                Status
            </label>
            <div id="status" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $proofingJob->status }}
            </div>
        </div>
        <div class="col-span-2"></div>

        <div class="w-full md:col-span-6 text-center mb-2">
            @if ($proofingJob->proofs->isNotEmpty())
                <table class="w-full border-collapse mt-5 mx-auto">
                    <thead>
                    <!-- Title Row -->
                    <tr>
                        <td colspan="6" class="text-center text-lg font-bold py-4 dark:text-gray-100">
                            Proofs sent for {{ $customer->company_name }}
                        </td>
                    </tr>
                    <!-- Header Row -->
                    <tr class="bg-amber-700 text-white">
                        <th class="py-2 px-4 text-center">ID</th>
                        <th class="py-2 px-4 text-center">Proof Sent</th>
                        <th class="py-2 px-4 text-center">File Path</th>

                    </tr>
                    </thead>
                    <tbody>
                    <!-- Data Rows -->
                    @foreach ($proofingJob->proofs as $proof)
                        <tr class="hover:bg-gray-300  dark:hover:bg-gray-700"  onclick="window.location='{{ route('proofs.show', $proof->id) }}'" style="cursor: pointer;">
                            <td class="py-2 px-4 text-center text-gray-900 dark:text-gray-100">{{ $proof->id }}</td>
                            <td class="py-2 px-4 text-center text-gray-900 dark:text-gray-100">
                                @if(is_null($proof->proof_sent))
                                    <span class="text-red-500">Not Sent</span>
                                @else
                                    {{ $proof->proof_sent->format('d-m-Y H:i:s') }}</td>
                            @endif
                            @if(!empty($proof->file_path))
                                <td class="py-2 px-4 text-center text-gray-900 dark:text-gray-100">{{ $proof->file_path }}</td>
                            @else
                                <td class="py-2 px-4 text-center text-gray-900 dark:text-gray-100">
                                    <span class="text-red-500">No File Path</span>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center text-gray-500 mt-4">
                    No proofs have been sent for this job.
                </div>
            @endif
        </div>
        <!-- fourth row -->
        @if ($proofingJob->proofs->isNotEmpty())
            <div class="col-span-1 text-center">
                <a class="text-red-800 hover:text-red-600 pl-5" href="{{ route('customers.show', $proofingJob->customer_id) }}">Cancel</a>
            </div>
            <div class="col-span-1 text-center">
                @if($proofCount < 1)
                    <a href="{{ route('proofs.create', ['jobId' => $proofingJob->id,  $proofingJob->customer_id]) }}"
                       class="text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 pl-5">
                        Send First Proof
                    </a>
                @else
                    <p class="text-neutral-500">Send First Proof</p>
                @endif

            </div>
            <div class="col-span-1 text-center">
                @if($proofCount > 0)
                    <a href="{{ route('proofs.create', ['jobId' => $proofingJob->id,  'customerId' => $proofingJob->customer->id,'proof_type' => 'amended']) }}"
                       class="text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 pl-5">
                        Send Amendment
                    </a>
                @else
                    <p class="text-neutral-500">Send Amendment</p>
                @endif
            </div>
            <div class="col-span-2 text-center">
                @if($proofCount  >= 1)
                    <form action="{{ route('proofs.resendProofEmail', ['proof' => $proofingJob->proofs->last()->id]) }}"
                          method="POST"
                          style="display: inline;">
                        @csrf
                        <button type="submit"
                                class="text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 pl-5"
                        >
                            Resend Last Proof/Amendment
                        </button>
                    </form>
                @else
                    <p class="text-neutral-500">Resend Last Proof/Amendment</p>
                @endif
            </div>
            <div class="col-span-1 text-center">
                <a href="{{ route('activities.job', ['id' => $proofingJob->id]) }}"
                   class="text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 pl-5">
                    View All Activities
                </a>
            </div>
        @endif



    </div>







@endsection