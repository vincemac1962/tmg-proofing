<!-- resources/views/partials/filters.blade.php -->
<div class="row">
    <div class="col-md-4">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
    </div>
    <div class="col-md-4">
        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
    </div>
    <div class="col-md-4">
        <label for="country">Country:</label>
        <select id="country" name="country" class="form-control">
            <option value="">All Countries</option>
            @foreach ($countries as $country)
                <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
                    {{ $country }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label for="proofing_company">Proofing Company:</label>
        <select id="proofing_company" name="proofing_company" class="form-control">
            <option value="">All Companies</option>
            @foreach ($proofingCompanies as $company)
                <option value="{{ $company }}" {{ request('proofing_company') == $company ? 'selected' : '' }}>
                    {{ $company }}
                </option>
            @endforeach
        </select>
    </div>
</div>
