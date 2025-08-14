<!-- resources/views/activities/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="p-6">
        <form method="GET" action="{{ route('activities.index') }}" class="mb-6">
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label>Date Range</label>
                    <input type="date" name="start_date" class="form-input">
                    <input type="date" name="end_date" class="form-input">
                </div>
                <div>
                    <label>User ID</label>
                    <input type="number" name="user_id" class="form-input">
                </div>
                <div>
                    <label>Job ID</label>
                    <input type="number" name="job_id" class="form-input">
                </div>
            </div>
            <button type="submit" class="btn-primary mt-2">Filter</button>
        </form>

        <table class="min-w-full">
            <thead>
            <tr>
                <th>Job ID</th>
                <th>User ID</th>
                <th>Activity Type</th>
                <th>IP Address</th>
                <th>Notes</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($activities as $activity)
                <tr>
                    <td>{{ $activity->job_id }}</td>
                    <td>{{ $activity->user_id }}</td>
                    <td>{{ $activity->activity_type }}</td>
                    <td>{{ $activity->ip_address }}</td>
                    <td>{{ Str::limit($activity->notes, 50) }}</td>
                    <td>
                        <a href="{{ route('activities.show', $activity) }}">View</a>
                        <a href="{{ route('activities.edit', $activity) }}">Edit</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $activities->links() }}
    </div>
@endsection