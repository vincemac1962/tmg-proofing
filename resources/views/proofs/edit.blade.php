{{-- resources/views/proofs/edit.blade.php --}}
@extends('layouts.app')

@section('content')
    <x-section-heading class="border-amber-700">
        Proof - Edit
    </x-section-heading>
    <form method="POST" action="{{ route('proofs.update', $proof) }}" class="w-full" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-4 gap-4 pt-5 w-3/4">
            <!-- first row -->
            <div class="mb-2 col-span-1">
                <label for="id" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                    ID
                </label>
                <div id="id" class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    {{ $proof->id }}
                </div>
            </div>

            <div class="md:col-span-1 mb-2">
                <label for="job_id" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                    Job ID
                </label>
                <div id="job_id" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">

                    {{ $proof->job_id }}
                </div>

            </div>
            <div class="col-span-2"></div>
            <!-- second row -->
            <div class="mb-2 col-span-1">
                <label for="id" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                    Sent Date
                </label>
                <div id="id" class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    {{ $proof->proof_sent->format('d-m-Y') ?? 'Not Sent' }}
                </div>
            </div>
            <div class="col-span-3"></div>
            <!-- third row -->
            @if(!empty($proof->file_path))
            <div class="mb-2 col-span-3">
                <label for="file_path" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                    Previous File Path
                </label>
                <div id="file_path" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    {{ $proof->file_path }}
                </div>
            </div>
                @if(!empty($proof->file_path) && Storage::disk('public')->exists($proof->file_path))
                    <div class="col-span-1 flex items-center">
                        <a href="{{ asset('storage/' . $proof->file_path) }}" class="text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 pl-5" target="_blank">View File</a>
                    </div>
                @else
                    <div class="col-span-1 block text-sm font-medium text-red-700 mb-1">
                        <span>File Not Available</span>
                    </div>
                @endif

            @endif

            <!-- fourth row -->
            <div class="col-span-3 ">
                <label
                        for="file"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1"
                >Select new file to upload - MP4s only - File size below 8Mb</label
                >
                <input
                        class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid border-secondary-500 bg-transparent bg-clip-padding px-3 py-[0.32rem] text-base font-normal text-surface transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:me-3 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-e file:border-solid file:border-inherit file:bg-transparent file:px-3  file:py-[0.32rem] file:text-surface focus:border-primary focus:text-gray-700 focus:shadow-inset focus:outline-none dark:border-white/70 dark:text-white  file:dark:text-white"
                        type="file"
                        name = "file"
                        id="file" />
            </div>
            <div class="col-span-1"></div>

            <!-- fifth row -->
            <div class="col-span-1 text-center">
                <a class="btn btn-primary text-red-800 hover:text-red-600" href="{{ route('proofs.show', $proof->id) }}">Cancel</a>
            </div>
            <div class="col-span-2"></div>
            <div class="col-span-1 text-center">
                        <button type="submit"
                                class="btn btn-primary text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 pl-5"
                        >Save</button>
            </div>



        </div>
        <input type="hidden" name="job_id" value="{{ $proof->job_id }}">
    </form>
@endsection