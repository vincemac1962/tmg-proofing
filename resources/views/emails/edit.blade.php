@extends('layouts.app')

@section('content')
    <h1>Edit Email</h1>
    <form method="POST" action="{{ route('emails.update', $email) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="job_id" value="{{ $email->job_id }}">
        <p>Recipient Email: {{ $email->recipient_email }}</p>
        <label for="subject">Subject:</label>
        <input type="text" name="subject" id="subject" value="{{ $email->subject }}" required>
        <label for="body">Body:</label>
        <textarea class="p-4 text-indent-0 border border-gray-300 rounded" name="body" id="body" required>{{ $email->body }}</textarea>
        <button type="submit">Update Email</button>
    </form>
@endsection