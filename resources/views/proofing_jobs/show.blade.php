<!-- resources/views/proofing_jobs/show.blade.php -->
@extends('layouts.app')

@section('content')
    <x-section-heading class="border-indigo-400">
        Proofing Job - Details
    </x-section-heading>
    <div class="grid grid-cols-6 gap-4 pt-5 w-full bg-white dark:bg-gray-800 shadow rounded-lg">
        <!-- first row -->
        <div class="mb-2 col-span-1">
            <label for="id" class="block text-sm font-medium text-gray-700 mb-1">
                ID
            </label>
            <div id="id" class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                {{ $proofingJob->id }}
            </div>
        </div>
        <div class="mb-2 col-span-1">
            <label for="contract_reference" class="block text-sm font-medium text-gray-700 mb-1">
                Contract Reference
            </label>
            <div id="contract_reference" class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                {{ $proofingJob->contract_reference }}
            </div>
        </div>

        <div class="md:col-span-2 mb-2">
            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">
                Company Name
            </label>
            <div id="company_name" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                {{ $customer->company_name }}
            </div>
        </div>
        <div class="col-span-2"></div>
        <!-- second row -->
        <div class="md:col-span-1 mb-2">
            <label for="created_at" class="block text-sm font-medium text-gray-700 mb-1">
                Created At
            </label>
            <div id="created_at" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                {{ $proofingJob->created_at->format('d-m-Y H:i:s') }}
            </div>
        </div>
        <div class="md:col-span-1 mb-2">
            <label for="updated_at" class="block text-sm font-medium text-gray-700 mb-1">
                Created At
            </label>
            <div id="updated_at" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                {{ $proofingJob->updated_at->format('d-m-Y H:i:s') }}
            </div>
        </div>
            <div class="md:col-span-2 mb-2">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                    Status
                </label>
                <div id="status" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                    {{ $proofingJob->status }}
                </div>
            </div>
        <div class="col-span-2"></div>
        <!-- third row -->
        <div class="w-full md:col-span-4 text-center mb-2">
        @if ($proofingJob->proofs->isNotEmpty())
                    <table class="w-full border-collapse mt-16 mx-auto">
                        <thead>
                        <!-- Title Row -->
                        <tr>
                            <td colspan="6" class="text-center text-lg font-bold py-4">
                                Proofs sent for {{ $customer->company_name }}
                            </td>
                        </tr>
                        <!-- Header Row -->
                        <tr class="bg-amber-700 text-white">
                            <th class="py-2 px-4 text-center">ID</th>
                            <th class="py-2 px-4 text-center">File Path</th>
                            <th class="py-2 px-4 text-center">Proof Sent</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Data Rows -->
                        @foreach ($proofingJob->proofs as $proof)
                            <tr onclick="window.location='{{ route('proofs.show', $proof->id) }}'" style="cursor: pointer;">
                                <td class="py-2 px-4 text-center">{{ $proof->id }}</td>
                                <td class="py-2 px-4 text-center">{{ $proof->file_path }}</td>
                                <td class="py-2 px-4 text-center">
                                    @if(is_null($proof->proof_sent))
                                        <span class="text-red-500">Not Sent</span>
                                    @else
                                    {{ $proof->proof_sent->format('d-m-Y H:i:s') }}</td>
                                    @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
            </div>
        @else
            <!-- fourth row -->
                <p>No proofs assigned to this proofing job.</p>
        </div>
    <div class="col-span-2"></div>
            <!-- fifth row -->
            <div class="col-span-1 text-center">
                <a class="btn btn-primary text-red-800 hover:text-red-600" href="{{ route('customers.show', $proofingJob->customer_id) }}">Cancel</a>
            </div>
            <div class="col-span-1 text-center">
                @if($proofCount < 1)
                    <a href="{{ route('proofs.create', ['jobId' => $proofingJob->id,  $proofingJob->customer_id]) }}"
                       class="btn btn-primary text-blue-800 hover:text-blue-600">
                        Send First Proof
                    </a>
                @else
                    <p class="text-neutral-500">Send First Proof</p>
                @endif

            </div>
            <div class="col-span-1 text-center">
                @if($proofCount < 0)
                    <a href="{{ route('proofs.create', ['jobId' => $proofingJob->id,  'customerId' => $proofingJob->customer->id,'proof_type' => 'amended']) }}"
                       class="btn btn-primary text-blue-800 hover:text-blue-600">
                        Send Amendment
                    </a>
                @else
                    <p class="text-neutral-500">Send Amendment</p>
                @endif
            </div>
            <div class="col-span-1 text-center">
                @if($proofCount  >= 1)
                    <form action="{{ route('proofs.resendProofEmail', ['proof' => $proofingJob->proofs->last()->id]) }}"
                          method="POST"
                          style="display: inline;">
                        @csrf
                        <button type="submit"
                                class="btn btn-secondary btn btn-primary text-blue-800 hover:text-blue-600"
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
                   class="btn btn-info btn btn-primary text-blue-800 hover:text-blue-600">
                    View All Activities
                </a>
            </div>
        @endif
        <div class="col-span-2"></div>
    </div>




    </div>


@endsection