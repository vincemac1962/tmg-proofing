{{-- resources/views/proofs/create.blade.php --}}
@extends('layouts.app')

@section('content')
    <x-section-heading class="border-amber-400">
        Proofs - Upload Proof
    </x-section-heading>
    <form action="{{ route('proofs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="proofing_job_id" value="{{ $jobId }}">
        <input type="hidden" name="customer_id" value="{{ $customerId }}">
    <div class="grid grid-cols-4 gap-4 pt-5 w-full bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="w-full col-span-2 p-6">
            <label
                    for="file"
                    class="mb-2 inline-block text-sm font-medium text-gray-700 dark:text-gray-100 "
            >Select file to upload - MP4s only - File size < 8Mb <span class="text-red-500">*</span></label
            >
            <input
                    class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid border-secondary-500 bg-transparent bg-clip-padding px-3 py-[0.32rem] text-base font-normal text-surface transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:me-3 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-e file:border-solid file:border-inherit file:bg-transparent file:px-3  file:py-[0.32rem] file:text-surface focus:border-primary focus:text-gray-700 focus:shadow-inset focus:outline-none dark:border-white/70 dark:text-white  file:dark:text-white dark:bg-gray-900"
                    type="file"
                    name = "file"
                    id="file" />
        </div>
        <div class="col-span-2"></div>
        <div class="w-full col-span-2 px-6">
            <label for="notes"
            class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
            Notes:</label>
            <textarea name="notes"
                      class="col-span-1 w-full px-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 text-gray-900 dark:text-gray-100"
                      id="notes"></textarea></div>
        <div class="col-span-2"></div>


        <div class="col-span-2 justify-center items-center text-center">
            <div class="btn text-red-800 hover:text-red-600 pl-5" onclick="history.back()">Cancel</div>
        </div>
        <div class="col-span-2 justify-center items-center text-center">
            <input type="hidden" name="proof_type" value="{{ $proofType ?? 'uploaded' }}">
            <button type="submit" class="text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400">Save</button>
        </div>
        @error('file')
        <div class="col-span-2 p-6">
            <div class="error" style="color: red;">{{ $message }}</div>
        </div>
        @enderror
    </div>


    </form>
@endsection