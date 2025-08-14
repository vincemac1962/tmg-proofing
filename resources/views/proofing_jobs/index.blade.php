@extends('layouts.app')

@section('content')
    <h1>Proofing Jobs for {{ $customer->company_name }}</h1>

    <a href="{{ route('customers.show', $customer->id) }}">Back to Customer</a>
    <a href="{{ route('proofing_jobs.create', ['customerId' => $customer->id]) }}">Create New Proofing Job</a>

    @if($proofingJobs->isEmpty())
        <p>No proofing jobs found.</p>
    @else
        <table>
            <thead>
            <tr>
                <th>Contract Reference</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($proofingJobs as $proofingJob)
                <tr>
                    <td>{{ $proofingJob->contract_reference }}</td>
                    <td>
                        <a href="{{ route('proofing_jobs.edit', ['customerId' => $customer->id, 'proofingJob' => $proofingJob->id]) }}">Edit</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
@endsection