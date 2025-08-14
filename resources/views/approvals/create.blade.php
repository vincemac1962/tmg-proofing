<!-- resources/views/proofing_jobs/create.blade.php -->
@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('approvals.store') }}">
    @csrf
    <input type="hidden" name="proof_id" value="{{ request('proof_id') }}">
    <input type="hidden" name="customer_id" value="{{ request('customer_id') }}">
    <input type="text" name="approved_by">
    <button type="submit">Save</button>
</form>

@endsection