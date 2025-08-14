<!-- resources/views/amendments/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                @foreach($amendments as $amendment)
                    <div class="mb-4">
                        <p>ID: {{ $amendment->id }}</p>
                        <p>Content: {{ $amendment->content }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection