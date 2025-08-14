<!-- resources/views/proofing_jobs/show.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="font-bold">Proof ID:</div>
                        <div>{{ $approval->proof_id }}</div>

                        <div class="font-bold">Customer ID:</div>
                        <div>{{ $approval->customer_id }}</div>

                        <div class="font-bold">Approved By:</div>
                        <div>{{ $approval->approved_by }}</div>

                        <div class="font-bold">Approved At:</div>
                        <div>{{ $approval->approved_at ? $approval->approved_at->format('d/m/Y H:i') : 'Not approved yet' }}</div>

                        <div class="font-bold">Created At:</div>
                        <div>{{ $approval->created_at ? $approval->created_at->format('d/m/Y H:i') : 'N/A' }}</div>

                        <div class="font-bold">Updated At:</div>
                        <div>{{ $approval->updated_at ? $approval->updated_at->format('d/m/Y H:i') : 'N/A' }}</div>
                    </div>

                    <div class="mt-6 space-x-4">
                        <a href="{{ route('approvals.edit', $approval) }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Edit</a>
                        <a href="{{ route('approvals.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection