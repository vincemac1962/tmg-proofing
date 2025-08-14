{{-- resources/views/reports-maintenance/edit.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Edit Report</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <form action="{{ route('reports-maintenance.update', $report) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('reports-maintenance.form')
                        <div class="mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Update Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
