@extends('layouts.app')

@section('content')
    <x-section-heading class="border-stone-700 dark:border-stone-600">
        Designers - Index
    </x-section-heading>
    @if (session('success'))
        <div id="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    <div class="py-2">
        <form method="GET" action="{{ route('designers.index') }}" class="flex flex-row items-center space-x-2">
            <label for="role" class="text-lg font-medium text-gray-700 dark:text-gray-100">Show All Designers</label>
            <input type="checkbox" name="show_all" onchange="this.form.submit()" {{ $showAll ? 'checked' : '' }}>
        </form>
    </div>
    <table class="w-1/2 border-collapse mt-5 ml-0">
        <thead>
        <tr class="bg-stone-800 dark:bg-stone-600 text-gray-100 dark:text-gray-300">
            <th class="py-2 px-4">ID</th>
            <th class="py-2 px-4">Name</th>
            <th class="py-2 px-4">Email</th>
            <th class="py-2 px-4">Active</th>

        </tr>
        </thead>
        <tbody>
        @foreach($designers as $designer)
            <tr class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer"
                onclick="window.location='{{ route('designers.show', $designer->id) }}'">
                <td class="py-2 px-4 text-center">{{ $designer->id }}</td>
                <td class="py-2 px-4 text-center">{{ $designer->name }}</td>
                <td class="py-2 px-4 text-center">{{ $designer->email }}</td>
                <td class="py-2 px-4 text-center">{{ $designer->active ? 'true' : 'false'  }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="grid-cols-2 text-center w-1/2 mt-5">
        <div class="grid-cols-2">
            @if(Auth::check() && (int)Auth::user()->access_level >= 2)
            <a href="{{ route('designers.create') }}" class="dark:text-gray-100 dark:hover:text-gray-400">
                Create New Designer
            </a>
            @else
                <span class="text-gray-400 cursor-not-allowed">Create New Designer</span>
            @endif
        </div>
    </div>


@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

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
