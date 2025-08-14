<!-- resources/views/amendments/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="font-bold">Proof ID:</div>
                        <div>{{ $amendment->proof_id }}</div>

                        <div class="font-bold">Customer ID:</div>
                        <div>{{ $amendment->customer_id }}</div>

                        <div class="font-bold">Amendment Notes:</div>
                        <div>{{ $amendment->amendment_notes }}</div>

                        <div class="font-bold">Created At:</div>
                        <div>{{ $amendment->created_at ? $amendment->created_at->format('d/m/Y H:i') : 'N/A' }}</div>

                        <div class="font-bold">Updated At:</div>
                        <div>{{ $amendment->updated_at ? $amendment->updated_at->format('d/m/Y H:i') : 'N/A' }}</div>
                    </div>

                    <div class="mt-6 space-x-4">
                        <a href="{{ route('amendments.edit', $amendment) }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Edit</a>
                        <a href="{{ route('amendments.index', $amendment->customer_id) }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection