{{-- resources/views/proofs/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <h1>Proofs</h1>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Contract Reference</th>
            <th>Customer Name</th>
            <th>Job ID</th>
            <th>Status</th>
            <th>File</th>
            <th>Sent Date</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($proofs as $proof)
            <tr>
                <td><a href="{{ route('proofs.show', $proof['id']) }}">{{ $proof['id'] }}</a></td>
                <td>{{ $proof['contract_reference'] }}</td>
                <td>{{ $proof['customer_name'] }}</td>
                <td>{{ $proof['job_id'] }}</td>
                <td>{{ $proof['status'] }}</td>
                <td>{{ $proof['file_path'] }}</td>
                <td>{{ $proof['proof_sent'] }}</td>
                <td>
                    <a href="{{ route('proofs.edit', $proof['id']) }}">Edit</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Success message auto-hide functionality
            const successMessage = document.getElementById('successMessage');
            console.log(successMessage); // Debugging
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.display = 'none';
                    console.log('Success message hidden'); // Debugging
                }, 5000); // 5000ms = 5 seconds
            }
        });
    </script>
@endpush
