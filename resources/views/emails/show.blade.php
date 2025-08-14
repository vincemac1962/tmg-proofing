@extends('layouts.app')

@section('content')
    <h1>Email Details</h1>
    <p><strong>Recipient Email:</strong> {{ $email->recipient_email }}</p>
    <p><strong>Subject:</strong> {{ $email->subject }}</p>
    <p><strong>Body:</strong> {{ $email->body }}</p>
    <a href="{{ route('emails.index') }}">Back to List</a>
@endsection