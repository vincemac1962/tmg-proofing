@extends('layouts.app')

@section('content')
    <h1>Email Records</h1>
    <form method="GET" action="{{ route('emails.index') }}">
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" id="start_date">
        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" id="end_date">
        <button type="submit">Filter by Date</button>
    </form>

    <form method="GET" action="{{ route('emails.index') }}">
        <label for="job_id">Job ID:</label>
        <input type="number" name="job_id" id="job_id">
        <button type="submit">Filter by Job ID</button>
    </form>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Recipient Email</th>
            <th>Subject</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($emails as $email)
            <tr>
                <td>{{ $email->id }}</td>
                <td>{{ $email->recipient_email }}</td>
                <td>{{ $email->subject }}</td>
                <td>
                    <a href="{{ route('emails.show', $email) }}">View</a>
                    <a href="{{ route('emails.edit', $email) }}">Edit</a>
                    <form method="POST" action="{{ route('emails.destroy', $email) }}" style="display:inline;">
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection