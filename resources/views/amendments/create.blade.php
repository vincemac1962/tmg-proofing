<!-- resources/views/amendments/create.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <form method="POST" action="{{ route('amendments.store') }}">
            @csrf
            <input type="hidden" name="proof_id" value="{{ $proof_id }}">
            <input type="hidden" name="customer_id" value="{{ $customer_id }}">

            <div>
                <label for="content">Amendment Content:</label>
                <textarea class="form-textarea p-4 text-indent-0 border border-gray-300 rounded"name="content" id="content" required>{{ old('content') }}</textarea>
            </div>

            <button type="submit">Create Amendment</button>
        </form>
    </div>
@endsection