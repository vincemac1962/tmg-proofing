@php
    use Illuminate\Support\Facades\Storage;
@endphp
{{-- Show proof details --}}
{{-- This file is part of the Proofing Jobs application. --}}

{{-- @extends directive is used to extend a layout file --}}
{{-- The content section will be injected into the layout's content section --}}
{{-- The section name 'content' should match the one defined in the layout file --}}

@extends('layouts.app')

@section('content')
    <x-section-heading class="border-amber-700">
        Proof - Details
    </x-section-heading>

    @if (session('success'))
        <div id="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-5 gap-4 pt-5 w-3/4">
        <!-- first row -->
        <div class="mb-2 col-span-1">
            <label for="id" class="block text-sm font-medium text-gray-700 mb-1">
                ID
            </label>
            <div id="id" class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                {{ $proof->id }}
            </div>
        </div>

        <div class="md:col-span-1 mb-2">
            <label for="job_id" class="block text-sm font-medium text-gray-700 mb-1">
                Job ID
            </label>
            <div id="job_id" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                {{ $proof->job_id }}
            </div>
        </div>
        <div class="col-span-3"></div>
        <!-- second row -->
        <div class="mb-2 col-span-3">
            <label for="file_path" class="block text-sm font-medium text-gray-700 mb-1">
                File Path
            </label>
            <div id="file_path" class="col-span-3 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                {{ $proof->file_path }}
            </div>
        </div>
        <div class="col-span-1 flex items-center">
            @if(!empty($proof->file_path) && Storage::disk('public')->exists($proof->file_path))
                <a href="{{ asset('storage/' . $proof->file_path) }}" class="text-blue-800 hover:text-blue-600" target="_blank">View File</a>
            @else
                <span>File N/A</span>
            @endif
        </div>
        <div class="col-span-1"></div>
        <!-- third row -->
        <div class="mb-2 col-span-1">
            <label for="id" class="block text-sm font-medium text-gray-700 mb-1">
                Sent Date
            </label>
            <div id="id" class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                @if(is_null($proof->proof_sent))
                    <span class="text-red-500">Not Sent</span>
                @else
                    {{ $proof->proof_sent->format('d-m-Y') }}
                @endif
            </div>
        </div>
        <div class="col-span-4"></div>
        <!-- fourth row -->
        <div class="col-span-1 text-center">
            <a class="btn btn-primary text-red-800 hover:text-red-600" href="{{ route('proofing_jobs.show', $proof->job_id) }}">Cancel</a>
        </div>
        <div class="col-span-1 text-center">
            @if(is_null($proof->proof_sent))
                <form method="POST" action="{{ route('proofs.sendProofEmail', $proof->id) }}">
                    @csrf
                    <button type="submit"
                            class="btn btn-primary text-blue-800 hover:text-blue-600"
                    >Send Proof</button>
                </form>
            @else
                <p class="text-neutral-500">Send Proof</p>
            @endif
        </div>
        <div class="col-span-1 text-center">
            @if(!is_null($proof->proof_sent))
                <form method="POST" action="{{ route('proofs.sendProofEmail', $proof->id) }}">
                    @csrf
                    <button type="submit"
                            class="btn btn-primary text-blue-800 hover:text-blue-600"
                    >Resend Proof</button>
                </form>
            @else
                <p class="text-neutral-500">Resend Proof</p>
            @endif
        </div>
        <div class="col-span-1 text-center">
            <a class="btn btn-primary text-blue-800 hover:text-blue-600" href="{{ route('proofs.edit', $proof) }}">Edit</a>
        </div>
        <div class="col-span-1 text-center">
            <form action="{{ route('proofs.destroy', $proof) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="btn btn-primary text-red-800 hover:text-red-600"
                >Delete</button>
            </form>
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