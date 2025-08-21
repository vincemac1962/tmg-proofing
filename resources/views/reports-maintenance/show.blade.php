{{-- resources/views/reports-maintenance/show.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-grey-100 dark:bg-grey-900 shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Report Details</h3>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-100">Category</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">{{ $report->report_category }}</dd>
                        </div>
                        <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-gray-50 dark:bg-gray-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-100">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">{{ $report->report_name }}</dd>
                        </div>
                        <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-100">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">{{ $report->report_description }}</dd>
                        </div>
                    </dl>
                </div>
                <div class="px-4 py-5 sm:px-6 flex gap-4">
                    <a href="{{ route('reports-maintenance.edit', ['report' => $report]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        Edit
                    </a>
                    <button onclick="confirmDelete()" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                        Delete
                    </button>
                    <form id="delete-form" action="{{ route('reports-maintenance.destroy', ['report' => $report]) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this report?')) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>
@endsection