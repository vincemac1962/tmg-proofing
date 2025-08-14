<!-- resources/views/amendments/edit.blade.php -->
@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('amendments.update', $amendment->id) }}">
    @csrf
    @method('PUT')
    <input type="hidden" name="proof_id" value="{{ $amendment->proof_id }}">
    <input type="hidden" name="customer_id" value="{{ $amendment->customer_id }}">

    <div>
        <label for="content">Amendment Content:</label>
        <textarea class="form-textarea p-4 text-indent-0 border border-gray-300 rounded" name="content" id="content" required>{{ old('content', $amendment->content) }}</textarea>
    </div>

    <button type="submit">Update Amendment</button>
</form>
@endsection