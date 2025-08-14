@extends('layouts.app')

@section('content')
    <h1>Edit Proofing Job</h1>

    <form action="{{ route('proofing_jobs.update', ['customerId' => $customer->id, 'proofingJob' => $proofingJob->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="proofing_company_id">Proofing Company:</label>
        <select id="proofing_company_id" name="proofing_company_id">
            <option value="">Select a Proofing Company</option>
            @foreach($proofingCompanies as $company)
                <option value="{{ $company->id }}" {{ $proofingJob->proofing_company_id == $company->id ? 'selected' : '' }}>
                    {{ $company->name }}
                </option>
            @endforeach
        </select>
        @error('proofing_company_id')
        <div class="error">{{ $message }}</div>
        @enderror

        <label for="contract_reference">Contract Reference:</label>
        <input type="text" name="contract_reference" id="contract_reference" value="{{ $proofingJob->contract_reference }}">
        @error('contract_reference')
        <div class="error">{{ $message }}</div>
        @enderror
        <label for="advert_location">Location:</label>
        <textarea id="advert_location" name="advert_location">{{ $proofingJob->advert_location }}</textarea>
        @error('advert_location')
        <div class="error">{{ $message }}</div>
        @enderror
        <label for="description">Description:</label>
        <textarea id="description" name="description">{{ $proofingJob->description }}</textarea>
        @error('description')
        <div class="error">{{ $message }}</div>
        @enderror

        <button type="submit">Update</button>
        <a href="{{ route('proofing_jobs.index', ['customerId' => $customer->id]) }}">Cancel</a>
    </form>
@endsection