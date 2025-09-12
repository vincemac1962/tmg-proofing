<!-- resources/views/users/show.blade.php -->

@extends('layouts.app')

@section('content')
    <x-section-heading class="border-teal-400">
        Users - Show
    </x-section-heading>
    <!-- first row -->
    <div class="grid grid-cols-6 gap-4 pt-5 w-full">
        <!-- first row -->
        <div class="mb-2 col-span-1">
            <label for="id" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                ID
            </label>
            <div id="id" class="col-span-1 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $user->id}}
            </div>
        </div>
        <div class="md:col-span-2 mb-2">
            <label for="user_name" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                User Name
            </label>
            <div id="user_name" class="col-span-2 w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $user->name }}
            </div>
        </div>
        <div class="col-span-3"></div>
        <!-- second row -->
        <div class="md:col-span-2 mb-2">
            <label for="user_email" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                Email
            </label>
            <div id="user_email" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $user->email }}
            </div>
        </div>
        <div class="col-span-4"></div>
        <!-- third row -->
        <div class="col-span-1">
            <label for="created_at" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                Created At
            </label>
            <div id="created_at" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $user->created_at->format('d-m-Y') }}
            </div>
        </div>
        <div class="col-span-1">
            <label for="updated_at" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">
                Updated At
            </label>
            <div id="updated_at" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                {{ $user->updated_at->format('d-m-Y') }}
            </div>
        </div>
        <div class="col-span-4"></div>
        <div class="col-span-1">
            <a class="text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 pl-5" href="{{ route('users.index') }}">Back to Users</a>
        </div>
        <div class="col-span-1">
            <a class="text-blue-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-gray-400 pl-5" href="{{ route('users.edit', $user->id) }}">Edit</a>
        </div>
        <div class="col-span-1">
            @if(Auth::check() && (int)Auth::user()->access_level >= 2)
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this user?')"
                            class="text-red-800 hover:text-red-600 pl-5">Delete</button>
                </form>
            @else
                <span class="text-gray-400 cursor-not-allowed">Delete</span>
            @endif
        </div>
        <div class="col-span-3"></div>

@endsection
