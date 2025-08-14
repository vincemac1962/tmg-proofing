<!-- resources/views/approvals/index.blade.php -->
@extends('layouts.app')

@section('content')

<form method="GET" action="{{ route('approvals.index') }}">
    <input type="date" name="start_date" value="{{ request('start_date') }}">
    <input type="date" name="end_date" value="{{ request('end_date') }}">
    <button type="submit">Filter</button>
</form>

@foreach($approvals as $approval)
    <div>
        <a href="{{ route('approvals.show', $approval) }}">
            Approval #{{ $approval->id }}
        </a>
    </div>
@endforeach

{{ $approvals->links() }}

@endsection