@extends('layouts.app')

@section('content')
    <h1>Create Email</h1>
    <form method="POST" action="{{ route('emails.store') }}">
        @csrf
        <input type="hidden" name="job_id" value="{{ $job_id }}">
        <p>Recipient Email: {{ $recipient_email }}</p>
        <label for="subject">Subject:</label>
        <input type="text" name="subject" id="subject" required>
        <label for="body">Body:</label>
        <textarea class="p-4 text-indent-0 border border-gray-300 rounded" name="body" id="body" required></textarea>
        <button type="submit">Send Email</button>
    </form>
@endsection