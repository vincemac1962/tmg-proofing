<!-- resources/views/activities/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="p-6">
        <form method="POST" action="{{ route('activities.store') }}">
            @csrf
            <input type="hidden" name="job_id" value="{{ request('job_id') }}">
            <input type="hidden" name="user_id" value="{{ request('user_id') }}">

            <div class="mb-4">
                <label>Activity Type</label>
                <select name="activity_type" class="form-select">
                    @foreach($activityTypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label>Notes</label>
                <textarea name="notes" class="form-textarea p-4 text-indent-0 border border-gray-300 rounded"></textarea>
            </div>

            <button type="submit" class="btn-primary">Create Activity</button>
        </form>
    </div>

@endsection