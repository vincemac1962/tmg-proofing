<!-- resources/views/proofing_jobs/edit.blade.php -->
@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('approvals.update', $approval) }}">
    @csrf
    @method('PUT')
    <input type="hidden" name="proof_id" value="{{ $approval->proof_id }}">
    <input type="hidden" name="customer_id" value="{{ $approval->customer_id }}">
    <input type="text" name="approved_by" value="{{ $approval->approved_by }}">
    <button type="submit">Update</button>
</form>

@endsection